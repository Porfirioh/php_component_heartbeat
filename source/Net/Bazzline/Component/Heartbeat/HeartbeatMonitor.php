<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

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
        $this->lastListen = time();
    }

    /**
     * {@inheritdoc}
     */
    public function attach(HeartbeatClientInterface $heartbeat)
    {
        $pulse = $this->getPulse($heartbeat);
        $hash = spl_object_hash($heartbeat);

        if (!isset($this->heartbeats[$pulse])) {
            $this->heartbeats[$pulse] = array();
        }
        if (isset($this->heartbeats[$pulse][$hash])) {
            throw new InvalidArgumentException(
                'Can not add already attached heartbeat'
            );
        }
        $this->heartbeats[$pulse][$hash] = $heartbeat;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(HeartbeatClientInterface $heartbeat)
    {
        $pulse = $this->getPulse($heartbeat);
        $hash = spl_object_hash($heartbeat);

        if ((!isset($this->heartbeats[$pulse]))
            || (!isset($this->heartbeats[$pulse][$hash]))) {
            throw new InvalidArgumentException(
                'Can not detach not attached heartbeat'
            );
        }
        unset($this->heartbeats[$pulse][$hash]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $heartbeats = array();
        foreach ($this->heartbeats as $heartbeatsPerPulse) {
            foreach ($heartbeatsPerPulse as $heartbeat) {
                $heartbeats[] = $heartbeat;
            }
        }

        return $heartbeats;
    }

    /**
     * {@inheritdoc}
     */
    public function detachAll()
    {
        $this->heartbeats = array();

        return $this;
    }

    /**
     * {@inheritdoc}
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
                     * @var $heartbeat HeartbeatClientInterface
                     */
                    try {
                        $heartbeat->knock();
                    } catch (RuntimeCriticalException $exception) {
                        $heartbeat->handleException($exception);
                        if ($exception instanceof CriticalRuntimeException) {
                            $this->detach($heartbeat);
                        }
                    }
                }
            }
        }

        $this->lastListenTimestamp = $currentTimestamp;

        return $this;
    }

    /**
     * @param HeartbeatClientInterface $heartbeat
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    private function getPulse(HeartbeatClientInterface $heartbeat)
    {
        if ($heartbeat instanceof PulseableInterface) {
            $pulse = $heartbeat->getPulse();
        } else {
            $pulse = 0;
        }

        return $pulse;
    }
}