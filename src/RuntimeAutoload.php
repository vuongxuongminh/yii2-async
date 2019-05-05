<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 *
 * Autoload in environment task executable.
 */
new class
{
    const AUTOLOAD_PATHS = [
        [
            __DIR__ . '/../../../autoload.php',
            __DIR__ . '/../../autoload.php',
            __DIR__ . '/../vendor/autoload.php',
            __DIR__ . '/../../vendor/autoload.php'
        ],
        [
            __DIR__ . '/../../../yiisoft/yii2/Yii.php',
            __DIR__ . '/../../yiisoft/yii2/Yii.php',
            __DIR__ . '/../vendor/yiisoft/yii2/Yii.php',
            __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php'
        ]
    ];

    /**
     *  Constructor require an autoload files.
     */
    public function __construct()
    {
        foreach (self::AUTOLOAD_PATHS as $paths) {

            foreach ($paths as $path) {

                if (file_exists($path)) {

                    require($path);

                    break;
                }

            }

        }
    }
};
