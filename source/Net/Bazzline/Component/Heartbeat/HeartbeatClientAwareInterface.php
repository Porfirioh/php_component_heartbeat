<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-15
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatClientAwareInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-15
 */
interface HeartbeatClientAwareInterface
{
    /**
     * Gets a heartbeat client
     *
     * @return \Net\Bazzline\Component\Heartbeat\ClientInterface
     * @throws \RuntimeException - if no heartbeat is set
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    public function getHeartbeatClient();

    /**
     * Validates if heartbeat client is set or not
     *
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-16
     */
    public function hasHeartbeatClient();

    /**
     * Sets a heartbeat client
     *
     * @param ClientInterface $heartbeat
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    public function setHeartbeatClient(ClientInterface $heartbeat);
}