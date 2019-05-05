<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use Yii;
use Exception;

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
        $time = microtime(false);

        Yii::$app->async->run(function () {
            usleep(1000);
        });

        $this->assertTrue((microtime(false) - $time) < 500);
    }

    public function testWait()
    {
        $time = microtime(false);

        Yii::$app->async->run(function () {
            usleep(1000);
        })->wait();

        $this->assertTrue((microtime(false) - $time) > 500);
    }

    public function testSuccessEvent()
    {
        $output = '';
        $callback = function (SuccessEvent $event) use (&$output) {
            $output .= $event->output;
        };

        Yii::$app->async->on(Async::EVENT_SUCCESS, $callback);

        Yii::$app->async->run(function () {

            return 123;
        }, ['success' => $callback])->wait();

        $this->assertEquals(123123, $output);
    }

    public function testErrorEvent()
    {
        Yii::$app->async->on(Async::EVENT_ERROR, function (ErrorEvent $event) {

            $this->assertEquals('Error', $event->throwable->getMessage());
        });

        Yii::$app->async->run(function () {

            throw new Exception('Error');
        }, [
            'error' => function (Exception $exception) {

                $this->assertEquals('Error', $exception->getMessage());
            }
        ])->wait();
    }

    public function testTimeoutEvent()
    {
        Yii::$app->async->on(Async::EVENT_ERROR, function (Event $event) {

            echo 'global passed';
            $this->assertTrue(true);
        });

        Yii::$app->async->run(function () {

            sleep(6);
        }, [
            'timeout' => function () {

                echo 'custom passed';
                $this->assertTrue(true);
            }
        ])->wait();
    }
}
