<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class ClientHistoryAwareInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */
interface ClientHistoryAwareInterface
{
    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function hasHeartbeatClientHistory();

    /**
     * @return null|ClientHistoryInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function getHeartbeatClientHistory();

    /**
     * @param ClientHistoryInterface $heartbeatClient
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function setHeartbeatClientHistory(ClientHistoryInterface $heartbeatClient);
}