<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Example\JSONBasedImplementationWithMonitor;

use Net\Bazzline\Component\Heartbeat\HeartbeatMonitorWithHistory;
use Net\Bazzline\Component\Heartbeat\RuntimeHeartbeatClientHistory;

/**
 * Class Monitor
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */
class Monitor extends HeartbeatMonitorWithHistory
{
    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHeartbeatClientHistory(
            new RuntimeHeartbeatClientHistory()
        );
    }
}