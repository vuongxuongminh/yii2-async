<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\async;

use Yii;

use yii\base\Component;

use Spatie\Async\Pool;

/**
 * Class Async
 *
 * @property int $awaitSleepTime
 * @property int $concurrency
 * @property int $timeout
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Async extends Component
{
    /**
     * @var Pool
     */
    protected $pool;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->pool = Yii::createObject(Pool::class);

        parent::init();
    }

    /**
     * @param $callable
     * @param array $callbacks
     * @return static
     */
    public function __invoke($callable, array $callbacks = [])
    {
        $process = $this->pool->add($callable);

        foreach ($callbacks as $callback) {

            switch (strtolower($callback)) {
                case 'timeout':
                    $process->timeout($callback);
                    break;
                case 'success':
                    $process->then($callback);
                    break;
                case 'error':
                case 'catch':
                    $process->catch($callback);
                    break;
                default:
                    break;
            }

        }

        return $this;
    }

    /**
     * @param null $intermediateCallback
     */
    public function await($intermediateCallback = null): void
    {
        $this->pool->wait($intermediateCallback);
    }

    public function setConcurrency(int $concurrency): void
    {
        $this->pool->concurrency($concurrency);
    }

    public function setTimeout(int $timeout): void
    {
        $this->pool->timeout($timeout);
    }

    public function setAwaitSleepTime(int $awaitSleepTime): void
    {
        $this->pool->sleepTime($awaitSleepTime);
    }

}
