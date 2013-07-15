<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class HeartbeatMonitorAbstract
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
abstract class HeartbeatMonitorAbstract implements HeartbeatMonitorInterface
{
    /**
     * @var array|
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    protected $heartbeats;

    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    protected $lastListenTimestamp;

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function __construct()
    {
        $this->heartbeats = array();
        $this->lastListen = time();
    }

    /**
     * {@inheritDoc}
     */
    public function attachHeartbeat(HeartbeatInterface $heartbeat)
    {
        $pulse = $this->getPulse($heartbeat);

        if (!isset($this->heartbeats[$pulse])) {
            $this->heartbeats[$pulse][] = array();
        }
        $this->heartbeats[$pulse][] = $heartbeat;
    }

    /**
     * {@inheritDoc}
     */
    public function detachHeartbeat(HeartbeatInterface $heartbeat)
    {
        $pulse = $this->getPulse($heartbeat);

        if (!isset($this->heartbeats[$pulse])) {
            $this->heartbeats[$pulse][] = array();
        }
        $this->heartbeats[$pulse][] = $heartbeat;
    }

    /**
     * {@inheritDoc}
     */
    public function listen()
    {
        $currentTimestamp = time();
        $maximumPulse = $currentTimestamp - $this->lastListenTimestamp;
        $availablePulses = array_keys($this->heartbeats);

        foreach ($availablePulses as $pulse) {
            if ($maximumPulse <= $pulse) {
                foreach ($this->heartbeats[$pulse] as $heartbeat) {
                    try {
                        $heartbeat->knock();
                    } catch (RuntimeException $exception) {
                        $this->handleHeartAttack($heartbeat);
                    }
                }
            }
        }

        $this->lastListenTimestamp = $currentTimestamp;

        return $this;
    }



    /**
     * @param HeartbeatInterface $heartbeat
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    abstract protected function handleHeartAttack(HeartbeatInterface $heartbeat);

    /**
     * @param HeartbeatInterface $heartbeat
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    private function getPulse(HeartbeatInterface $heartbeat)
    {
        if ($heartbeat instanceof PulseableInterface) {
            $pulse = $heartbeat->getPulse();
        } else {
            $pulse = 0;
        }

        return $pulse;
    }
}