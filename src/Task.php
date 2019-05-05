<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\async;

use yii\base\BaseObject;

/**
 * Async task executable.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
abstract class Task extends BaseObject
{

    /**
     * Call before run task for setting up environment.
     */
    abstract public function configure(): void;

    /**
     * Task executable.
     *
     * @return mixed
     */
    abstract public function run();

    /**
     * Magic call in child runtime process.
     *
     * @return mixed result of this task.
     * @see [[run()]]
     */
    public function __invoke()
    {
        $this->configure();

        return $this->run();
    }

}
