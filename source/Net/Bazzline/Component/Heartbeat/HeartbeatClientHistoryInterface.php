<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatClientHistoryInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */
interface HeartbeatClientHistoryInterface
{
    /**
     * @param HeartbeatClientInterface $heartbeatClient
     * @param null|RuntimeException $exception
     * @return $this
     * @throws InvalidArgumentException
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function addEntry(HeartbeatClientInterface $heartbeatClient, $exception = null);
}