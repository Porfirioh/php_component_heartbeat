<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatMonitorFactory
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */
class HeartbeatMonitorFactory implements FactoryInterface
{
    /**
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function create()
    {
        $heartbeatMonitor = new HeartbeatMonitor();
        $timestamp = new Timestamp();
        $heartbeatMonitor->setTimestamp($timestamp);

        return $heartbeatMonitor;
    }
}