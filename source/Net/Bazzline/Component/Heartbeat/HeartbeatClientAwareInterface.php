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
interface HeartbeatClientAwareInterface
{
    /**
     * Gets a heartbeat
     *
     * @return \Net\Bazzline\Component\Heartbeat\ClientInterface
     * @throws \RuntimeException - if no heartbeat is set
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    public function getHeartbeatClient();

    /**
     * Sets a heartbeat
     *
     * @param ClientInterface $heartbeat
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    public function setHeartbeatClient(ClientInterface $heartbeat);
}