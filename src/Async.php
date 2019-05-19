<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\async;

use Yii;
use Closure;
use Throwable;

use yii\base\Component;

use Spatie\Async\Pool;
use Spatie\Async\Process\Runnable;

use vxm\async\event\Event;
use vxm\async\event\ErrorEvent;
use vxm\async\event\SuccessEvent;
use vxm\async\runtime\ParentRuntime;

/**
 * Support run code async. To use it, you just config it to your application components in configure file:
 *
 * ```php
 * 'components' => [
 *      'async' => 'vxm\async\Async'
 * ]
 *
 * ```
 *
 * And after that you can run an async code:
 *
 * ```php
 *
 * Yii::$app->async->run(function () {
 *
 *      sleep(5);
 * });
 *
 * ```
 *
 * If you want to wait until task done, you just to call [[wait]].
 *
 * ```php
 *
 * Yii::$app->async->run(function () {
 *
 *      sleep(5);
 * })->wait();
 *
 * ```
 *
 * Run multi tasks:
 *
 * ```php
 *
 * Yii::$app->async->run(function () {
 *
 *      sleep(5);
 * });
 *
 * Yii::$app->async->run(function () {
 *
 *      sleep(5);
 * });
 *
 * Yii::$app->async->wait();
 * ```
 *
 * @property string $autoload path of autoload file for runtime task execute environment.
 * @property int $sleepTimeWait time to sleep on wait tasks execute.
 * @property int $concurrency tasks executable.
 * @property int $timeout of task executable.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Async extends Component
{

    /**
     * @event SuccessEvent an event that is triggered when task done.
     */
    const EVENT_SUCCESS = 'success';

    /**
     * @event ErrorEvent an event that is triggered when task error.
     */
    const EVENT_ERROR = 'error';

    /**
     * @event \yii\base\Event an event that is triggered when task timeout.
     */
    const EVENT_TIMEOUT = 'timeout';

    /**
     * @var Pool handling tasks.
     */
    protected $pool;

    /**
     * @var string a file config of an application run in child process.
     * Note: If an autoload file's set, it will not affect, you need to invoke an app in your autoload if needed.
     */
    protected $appConfigFile;

    /**
     * Async constructor.
     *
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        $pool = $this->pool = Yii::createObject(Pool::class);
        $pool->autoload(__DIR__ . '/runtime/AutoloadRuntime.php');

        parent::__construct($config);
    }

    /**
     * Execute async task.
     *
     * @param callable|\Spatie\Async\Task|Task $callable need to execute.
     * @param array $callbacks event. Have key is an event name, value is a callable triggered when event happen,
     * have three events `error`, `success`, `timeout`.
     * @return static
     */
    public function run($callable, array $callbacks = []): self
    {
        $process = $this->createProcess($callable);

        foreach ($callbacks as $name => $callback) {

            switch (strtolower($name)) {
                case 'success':
                    $process->then(Closure::fromCallable($callback));
                    break;
                case 'error':
                case 'catch':
                    $process->catch(Closure::fromCallable($callback));
                    break;
                case 'timeout':
                    $process->timeout(Closure::fromCallable($callback));
                    break;
                default:
                    break;
            }

        }

        $process
            ->then(Closure::fromCallable([$this, 'success']))
            ->catch(Closure::fromCallable([$this, 'error']))
            ->timeout(Closure::fromCallable([$this, 'timeout']));

        $this->pool->add($process);

        return $this;
    }

    /**
     * This method is called when task executed success.
     * When overriding this method, make sure you call the parent implementation to ensure the
     * event is triggered.
     *
     * @param mixed $output of task executed.
     * @throws \yii\base\InvalidConfigException
     */
    public function success($output): void
    {
        $event = Yii::createObject([
            'class' => SuccessEvent::class,
            'output' => $output
        ]);

        $this->trigger(self::EVENT_SUCCESS, $event);
    }

    /**
     * This method is called when task executed error.
     * When overriding this method, make sure you call the parent implementation to ensure the
     * event is triggered.
     *
     * @param Throwable $throwable when executing task.
     * @throws \yii\base\InvalidConfigException
     */
    public function error(Throwable $throwable): void
    {
        $event = Yii::createObject([
            'class' => ErrorEvent::class,
            'throwable' => $throwable
        ]);

        $this->trigger(self::EVENT_ERROR, $event);
    }

    /**
     * This method is called when task executed timeout.
     * When overriding this method, make sure you call the parent implementation to ensure the
     * event is triggered.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function timeout(): void
    {
        $event = Yii::createObject(Event::class);

        $this->trigger(self::EVENT_TIMEOUT, $event);
    }

    /**
     * Wait until all tasks done.
     */
    public function wait(): void
    {
        $this->pool->wait();
    }

    /**
     * Set concurrency process do tasks.
     *
     * @param int $concurrency
     */
    public function setConcurrency(int $concurrency): void
    {
        $this->pool->concurrency($concurrency);
    }

    /**
     * Set timeout of task when execute.
     *
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->pool->timeout($timeout);
    }

    /**
     * Set sleep time when wait tasks execute.
     *
     * @param int $sleepTimeWait
     */
    public function setSleepTimeWait(int $sleepTimeWait): void
    {
        $this->pool->sleepTime($sleepTimeWait);
    }

    /**
     * Set autoload for environment tasks execute.
     * @param string $autoload it can use at an alias.
     */
    public function setAutoload(string $autoload): void
    {
        $this->pool->autoload(Yii::getAlias($autoload));
    }

    /**
     * Set an application config file for invoke in child runtime process.
     *
     * @param string $appConfigFile it can use at an alias.
     */
    public function setAppConfigFile(string $appConfigFile): void
    {
        $this->appConfigFile = Yii::getAlias($appConfigFile);
    }

    /**
     * Create an async process.
     *
     * @param callable|\Spatie\Async\Task|Task $callable need to execute.
     * @return Runnable process.
     */
    protected function createProcess($callable): Runnable
    {
        return ParentRuntime::createProcess([$callable, $this->appConfigFile]);
    }

}
