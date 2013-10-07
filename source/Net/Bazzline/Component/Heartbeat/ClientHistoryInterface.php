<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class ClientHistoryInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */
interface ClientHistoryInterface
{
    /**
     * @param ClientInterface $heartbeatClient
     * @param null|RuntimeException $exception
     * @return $this
     * @throws InvalidArgumentException
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function addEntry(ClientInterface $heartbeatClient, $exception = null);

    /**
     * @return array|ClientHistoryEntryInterface[]
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getEntries();
}
