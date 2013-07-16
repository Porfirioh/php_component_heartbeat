<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatMonitorInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
interface HeartbeatMonitorInterface
{
    /**
     * Adds a client to the observer
     *
     * @param HeartbeatInterface $heartbeat
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function attach(HeartbeatInterface $heartbeat);

    /**
     * Removes a client to the observer
     *
     * @param HeartbeatInterface $heartbeat
     * @return $this
     * @throws \InvalidArgumentException - if you want to detach a non attached
     *  heartbeat
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function detach(HeartbeatInterface $heartbeat);

    /**
     * Listen to each added client. Implement handling of error here.
     *
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function listen();
}