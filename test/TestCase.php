<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use Yii;

use Symfony\Component\Stopwatch\Stopwatch;

use PHPUnit\Framework\TestCase as BaseTestCase;

use yii\helpers\ArrayHelper;

/**
 * Class TestCase
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class TestCase extends BaseTestCase
{
    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockApplication();
        $this->stopwatch = new Stopwatch;
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->destroyApplication();
        $this->stopwatch = null;
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\console\Application'): void
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'test',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'components' => [
                'async' => [
                    'class' => 'vxm\async\Async',
                    'timeout' => 5,
                    'appConfigFile' => __DIR__ . '/child-app-config.php'
                ]
            ]
        ], $config));
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication(): void
    {
        Yii::$app = null;
    }


}
