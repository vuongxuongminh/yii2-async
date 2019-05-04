<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\async;

use yii\base\Event;

/**
 * Class AwaitEvent
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class AwaitEvent extends Event
{

    /**
     * @var Async
     */
    public $sender;

    /**
     * @var bool
     */
    public $isValid = true;

    /**
     * @var null|callable
     */
    public $intermediateCallback;

}
