<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

use Net\Bazzline\Component\Utility\TimestampInterface;
use Net\Bazzline\Component\Utility\TimestampAwareInterface;
use SplObjectStorage;

/**
 * Class MonitorWithHistory
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
class MonitorWithHistory extends Monitor implements HeartbeatClientHistoryAwareInterface
{
    /**
     * @var HeartbeatClientHistoryInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    protected $heartbeatClientHistory;

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function hasHeartbeatClientHistory()
    {
        return (!is_null($this->heartbeatClientHistory));
    }

    /**
     * @return null|HeartbeatClientHistoryInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function getHeartbeatClientHistory()
    {
        return $this->heartbeatClientHistory;
    }

    /**
     * @param HeartbeatClientHistoryInterface $heartbeatClient
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function setHeartbeatClientHistory(HeartbeatClientHistoryInterface $heartbeatClient)
    {
        $this->heartbeatClientHistory = $heartbeatClient;

        return $this;
    }


    /**
     * @param ClientInterface $client
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    protected function preUpdateClientAfterKnocking(ClientInterface $client)
    {
        $this->addEntry($client);
    }

    /**
     * @param ClientInterface $client
     * @param RuntimeException $exception
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-05
     */
    protected function handleClientException(ClientInterface $client, RuntimeException $exception)
    {
        parent::handleClientException($client, $exception);
        $this->addEntry($client, $exception);
    }

    /**
     * @param ClientInterface $client
     * @param RuntimeException $exception
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-05
     */
    private function addEntry(ClientInterface $client, RuntimeException $exception = null)
    {
        if ($this->hasHeartbeatClientHistory()) {
            $this->heartbeatClientHistory
                ->addEntry($client, $exception);
        }
    }
}
