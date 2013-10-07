<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */

namespace Net\Bazzline\Component\Heartbeat;

use Net\Bazzline\Component\Utility\Timestamp;

/**
 * Class MonitorFactory
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */
class MonitorFactory implements FactoryInterface
{
    /**
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function create()
    {
        $monitor = new Monitor();
        $timestamp = new Timestamp();
        $monitor->setTimestamp($timestamp);

        return $monitor;
    }
}