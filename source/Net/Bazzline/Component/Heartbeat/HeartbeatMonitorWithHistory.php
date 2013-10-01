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
 * Class HeartbeatMonitor
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
class HeartbeatMonitorWithHistory extends HeartbeatMonitor implements HeartbeatClientHistoryAwareInterface
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
     * @return null|HeartbeatClientInterface $heartbeatClient
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function getHeartbeatClientHistory()
    {
        return $this->heartbeatClientHistory;
    }

    /**
     * @param HeartbeatClientInterface $heartbeatClient
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function setHeartbeatClientHistory(HeartbeatClientInterface $heartbeatClient)
    {
        $this->heartbeatClientHistory = $heartbeatClient;

        return $this;
    }


    /**
     * @param HeartbeatClientInterface $client
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    protected function preUpdateClientAfterKnocking(HeartbeatClientInterface $client)
    {
        if ($this->hasHeartbeatClientHistory()) {
            $this
                ->heartbeatClientHistory
                ->addEntry($client);
        }
    }
}
