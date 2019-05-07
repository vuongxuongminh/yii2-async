<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use Yii;

use vxm\async\event\ErrorEvent;

/**
 * Class TaskTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class TaskTest extends TestCase
{

    public function testExecute()
    {
        $result = null;

        Yii::$app->async->run(new TaskRunnerTest, [
            'success' => function ($output) use (&$result) {

                $result = $output;
            }
        ])->wait();

        $this->assertEquals('yii\console\Application', $result);
    }

    public function testError()
    {
        $result = null;

        Yii::$app->async->on('error', function (ErrorEvent $event) use (&$result) {
            $result = get_class($event->throwable);
        });

        Yii::$app->async->run(new TaskRunnerErrorTest())->wait();

        $this->assertEquals('yii\console\Exception', $result);
    }

    public function testTimeout()
    {
        $result = false;

        Yii::$app->async->on('timeout', function () use (&$result) {
            $result = true;
        });

        Yii::$app->async->run(new TaskRunnerTimeoutTest)->wait();

        $this->assertTrue($result);
    }

}
