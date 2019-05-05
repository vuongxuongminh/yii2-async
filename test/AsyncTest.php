<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use vxm\async\Event;
use Yii;
use Exception;

use vxm\async\Async;
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
        $time = time();

        Yii::$app->async(function () {
            sleep(10);
        });

        $this->assertTrue((time() - $time) < 10);
    }

    public function testAwait()
    {
        $time = time();

        Yii::$app->async(function () {
            sleep(10);
        })->await();

        $this->assertTrue((time() - $time) >= 10);
    }

    public function testSuccessEvent()
    {
        Yii::$app->async->on(Async::EVENT_SUCCESS, function (SuccessEvent $event) {

            $this->assertEquals(123, $event->output);
        });

        Yii::$app->async(function () {

            return 123;
        }, [
            'success' => function ($result) {

                $this->assertEquals(123, $result);
            }
        ]);
    }

    public function testErrorEvent()
    {
        Yii::$app->async->on(Async::EVENT_ERROR, function (ErrorEvent $event) {

            $this->assertEquals(Exception::class, get_class($event->throwable));
        });

        Yii::$app->async(function () {

            throw new Exception('Error');
        }, [
            'error' => function (Exception $exception) {

                $this->assertEquals(Exception::class, get_class($exception));
            }
        ]);
    }

    public function testTimeoutEvent()
    {
        Yii::$app->async->on(Async::EVENT_ERROR, function (Event $event) {

            echo 'global passed';
        });

        Yii::$app->async(function () {

            sleep(30);
        }, [
            'timeout' => function () {

                echo 'custom passed';
            }
        ])->await();
    }
}
