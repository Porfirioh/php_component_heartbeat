<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class HeartbeatMonitor
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
class HeartbeatMonitor implements HeartbeatMonitorInterface
{
    /**
     * @var array
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
    public function attach(HeartbeatInterface $heartbeat)
    {
        $pulse = $this->getPulse($heartbeat);
        $hash = spl_object_hash($heartbeat);

        if (!isset($this->heartbeats[$pulse])) {
            $this->heartbeats[$pulse][] = array();
        }
        if (isset($this->heartbeats[$pulse][$hash])) {
            throw new InvalidArgumentException(
                'Can not add attached heartbeat.'
            );
        }
        $this->heartbeats[$pulse][$hash] = $heartbeat;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(HeartbeatInterface $heartbeat)
    {
        $pulse = $this->getPulse($heartbeat);
        $hash = spl_object_hash($heartbeat);

        if ((!isset($this->heartbeats[$pulse]))
            || (isset($this->heartbeats[$pulse][$hash]))) {
            throw new InvalidArgumentException(
                'Can not detach not attached heartbeat.'
            );
        }
        unset($this->heartbeats[$pulse][$hash]);

        return $this;
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
                    /**
                     * @var $heartbeat HeartbeatInterface
                     */
                    try {
                        $heartbeat->knock();
                    } catch (RuntimeException $exception) {
                        $heartbeat->handleHeartAttack();
                    }
                }
            }
        }

        $this->lastListenTimestamp = $currentTimestamp;

        return $this;
    }

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