<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatMonitorAbstract
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
abstract class HeartbeatMonitorAbstract implements HeartbeatMonitorInterface
{
    /**
     * @var array
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    protected $clients;

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function __construct()
    {
        $this->clients = array();
    }

    /**
     * Adds a client to the observer
     *
     * @param HeartbeatInterface $heartbeat
     *
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since  2013-07-14
     */
    public function addHeartbeat(HeartbeatInterface $heartbeat)
    {
        if ($heartbeat instanceof PulseableInterface) {
            $pulse = $heartbeat->getPulse();
        } else {
            $pulse = 0;
        }

        if (!isset($this->clients[$pulse])) {
            $this->clients[$pulse][] = array();
        }
        $this->clients[$pulse][] = $heartbeat;
    }
}