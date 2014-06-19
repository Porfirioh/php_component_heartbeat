<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-17 
 */

namespace Example\JSONBasedImplementationWithMonitor;

use Net\Bazzline\Component\Heartbeat\MonitorWithHistory as ParentMonitor;
use Net\Bazzline\Component\Heartbeat\RuntimeClientHistory;

/**
 * Class Monitor
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-17
 */
class Monitor extends ParentMonitor
{
    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-10-01
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHeartbeatClientHistory(
            new RuntimeClientHistory()
        );
    }
}