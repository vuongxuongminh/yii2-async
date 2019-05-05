<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use Yii;

use vxm\async\Task;

/**
 * Class TaskRunnerTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class TaskRunnerTest extends Task
{
    /**
     * @var \yii\console\Request
     */
    public $request;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->request = Yii::$app->getRequest();

        parent::init();
    }

    public function run()
    {
        return get_class($this->request) === 'yii\console\Request';
    }
}
