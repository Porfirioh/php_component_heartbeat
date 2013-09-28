<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatClientHistoryAwareInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */
interface HeartbeatClientHistoryAwareInterface
{
    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function hasHeartbeatClientHistory();

    /**
     * @return null|HeartbeatClientInterface $heartbeatClient
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function getHeartbeatClientHistory();

    /**
     * @param HeartbeatClientInterface $heartbeatClient
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function setHeartbeatClientHistory(HeartbeatClientInterface $heartbeatClient);
}