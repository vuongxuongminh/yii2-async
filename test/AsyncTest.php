<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use Yii;

use vxm\async\Async;
use vxm\async\Event;
use vxm\async\ErrorEvent;
use vxm\async\SuccessEvent;

/**
 * Class AsyncTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class AsyncTest extends TestCase
{

    public function testAsync()
    {
        $this->stopwatch->start('test');

        Yii::$app->async->run(function () {
            usleep(1000);
        });

        $this->assertLessThan(500, $this->stopwatch->stop('test')->getDuration());
    }

    public function testWait()
    {
        $this->stopwatch->start('test');

        Yii::$app->async->run(function () {
            usleep(1000);
        })->wait();

        $this->assertGreaterThan(500, $this->stopwatch->stop('test')->getDuration());
    }

    public function testSuccessEvent()
    {
        $result = 0;

        Yii::$app->async->on(Async::EVENT_SUCCESS, function (SuccessEvent $event) use (&$result) {
            $result += $event->output;
        });

        Yii::$app->async->run(function () {

            return 5;
        }, [
            'success' => function ($output) use (&$result) {
                $result += $output;
            }
        ])->wait();

        $this->assertEquals(10, $result);
    }

    public function testErrorEvent()
    {
        /** @var TestException[] $exceptions */
        $exceptions = [];
        Yii::$app->async->on(Async::EVENT_ERROR, function (ErrorEvent $event) use (&$exceptions) {

            $exceptions[] = $event->throwable;
        });

        Yii::$app->async->run(function () {

            throw new TestException('Error');
        }, [
            'error' => function (TestException $exception) use (&$exceptions) {

                $exceptions[] = $exception;
            }
        ])->wait();

        foreach ($exceptions as $exception) {
            $this->assertEquals('Error', $exception->getMessage(), implode(PHP_EOL, $exception->getTrace()));
            $this->assertEquals(TestException::class, get_class($exception));
        }

    }

    public function testTimeoutEvent()
    {
        $counter = 0;

        Yii::$app->async->on(Async::EVENT_TIMEOUT, function (Event $event) use (&$counter) {
            $counter++;
        });

        Yii::$app->async->run(function () {

            sleep(6);
        }, [
            'timeout' => function () use (&$counter) {
                $counter++;
            }
        ])->wait();

        $this->assertEquals(2, $counter);
    }
}
