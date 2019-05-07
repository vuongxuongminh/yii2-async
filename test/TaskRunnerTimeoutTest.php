<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use vxm\async\Task;

/**
 * Class TaskRunnerTimeoutTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class TaskRunnerTimeoutTest extends Task
{

    public function run()
    {
        sleep(6);
    }

}
