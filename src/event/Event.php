<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\async\event;

use yii\base\Event as BaseEvent;

/**
 * Async event.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Event extends BaseEvent
{

    /**
     * @var \vxm\async\Async object triggered this.
     */
    public $sender;

}
