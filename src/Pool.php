<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\async;

use Spatie\Async\Pool as BasePool;

/**
 * Pool control async processes.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Pool extends BasePool
{

    /**
     * Flush the pool.
     */
    public function flush(): void
    {
        $this->results = [];
        $this->failed = [];
        $this->queue = [];
        $this->finished = [];
        $this->timeouts = [];
        $this->inProgress = [];
    }

}
