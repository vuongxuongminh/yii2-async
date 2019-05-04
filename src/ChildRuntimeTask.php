<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\async;

use Yii;

use yii\base\Configurable;

/**
 * Async task executable in child runtime process.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ChildRuntimeTask extends Task implements Configurable
{
    /**
     * @var \yii\console\Application|\yii\web\Application for configure environment.
     */
    public $app;

    /**
     * @var \Spatie\Async\Task|Task|callable task need to run.
     */
    public $callable;

    /**
     * Task constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        Yii::configure($this, $config);
    }

    /**
     * @inheritDoc
     */
    public function configure()
    {
        Yii::$app = $this->app;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        return call_user_func($this->callable);
    }

}
