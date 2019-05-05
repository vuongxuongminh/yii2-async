<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\async;

use PHPUnit\TextUI\ResultPrinter as BaseResultPrinter;

/**
 * Class ResultPrinter overrides \PHPUnit\TextUI\ResultPrinter constructor
 * to change default output to STDOUT and prevent some tests from fail when
 * they can not be executed after headers have been sent.
 *
 * @see https://github.com/yiisoft/yii2/blob/master/tests/ResultPrinter.php
 */
class ResultPrinter extends BaseResultPrinter
{
    public function __construct(
        $out = null,
        $verbose = false,
        $colors = \PHPUnit\TextUI\ResultPrinter::COLOR_DEFAULT,
        $debug = false,
        $numberOfColumns = 80,
        $reverse = false
    )
    {
        if ($out === null) {
            $out = STDOUT;
        }
        parent::__construct($out, $verbose, $colors, $debug, $numberOfColumns, $reverse);
    }

    public function flush(): void
    {
        if ($this->out !== STDOUT) {
            parent::flush();
        }
    }
}
