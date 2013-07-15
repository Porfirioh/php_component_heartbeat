<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-15
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatAwareInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-15
 */
interface HeartbeatAwareInterface
{
    /**
     * Gets a heartbeat
     *
     * @return \Net\Bazzline\Component\Heartbeat\HeartbeatInterface
     * @throws \RuntimeException - if no heartbeat is set
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    public function getHeartbeat();

    /**
     * Sets a heartbeat
     *
     * @param HeartbeatInterface $heartbeat
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    public function setHeartbeat(HeartbeatInterface $heartbeat);
}