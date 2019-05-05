<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use Yii;

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
        $result = false;

        Yii::$app->async->run(new TaskRunnerTest, [
            'success' => function ($output) use (&$result) {
                $result = $output;
            }
        ])->wait();

        $this->assertEquals('yii\console\Request', $result);
    }

}
