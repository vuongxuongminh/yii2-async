<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

define('YII_ENV', 'dev');
define('YII_DEBUG', true);

return [
    'id' => 'async-app',
    'basePath' => __DIR__,
    'runtimePath' => __DIR__ . '/runtime',
    'aliases' => [
        '@vxm/test/unit/async' => __DIR__,
        '@vxm/async' => dirname(__DIR__) . '/src'
    ]
];
