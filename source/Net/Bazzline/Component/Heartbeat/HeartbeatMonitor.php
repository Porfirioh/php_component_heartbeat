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
     * @var array[$pulse => $clients]
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    protected $clientsPerPulse;

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
    public function attach(HeartbeatClientInterface $client)
    {
        $pulse = $this->getPulse($client);
        $hash = spl_object_hash($client);

        //no entries available for this pulse, create a empty array for it
        if (!isset($this->clientsPerPulse[$pulse])) {
            $this->clientsPerPulse[$pulse] = array();
        }
        //prevent from adding the same object twice
        if (isset($this->clientsPerPulse[$pulse][$hash])) {
            throw new InvalidArgumentException(
                'Can not add already attached heartbeat'
            );
        }
        //add client to array by provided pulse and hash
        $this->clientsPerPulse[$pulse][$hash] = $client;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(HeartbeatClientInterface $heartbeat)
    {
        $pulse = $this->getPulse($heartbeat);
        $hash = spl_object_hash($heartbeat);

        //validate if an entry for the provided pulse exist
        //validate if an entry for the provided pulse and hash exists
        if ((!isset($this->clientsPerPulse[$pulse]))
            || (!isset($this->clientsPerPulse[$pulse][$hash]))) {
            throw new InvalidArgumentException(
                'Can not detach not attached heartbeat'
            );
        }
        //remove client from array
        unset($this->clientsPerPulse[$pulse][$hash]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $heartbeats = array();
        foreach ($this->clientsPerPulse as $pulse => $clients) {
            foreach ($clients as $client) {
                $heartbeats[] = $client;
            }
        }

        return $heartbeats;
    }

    /**
     * {@inheritdoc}
     */
    public function detachAll()
    {
        $this->clientsPerPulse = array();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listen()
    {
        $currentTimestamp = time();
        $maximumPulse = $currentTimestamp - $this->lastListenTimestamp;
        $availablePulses = array_keys($this->clientsPerPulse);

        foreach ($availablePulses as $pulse) {
            if ($maximumPulse <= $pulse) {
                foreach ($this->clientsPerPulse[$pulse] as $clients) {
                    /**
                     * @var $clients HeartbeatClientInterface
                     */
                    try {
                        $clients->knock();
                    } catch (RuntimeException $exception) {
                        $clients->handleException($exception);
                        if ($exception instanceof CriticalRuntimeException) {
                            $this->detach($clients);
                        }
                    }
                }
            }
        }

        $this->lastListenTimestamp = $currentTimestamp;

        return $this;
    }

    /**
     * @param HeartbeatClientInterface $client
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    private function getPulse(HeartbeatClientInterface $client)
    {
        if ($client instanceof PulseableInterface) {
            $pulse = $client->getPulse();
        } else {
            $pulse = 0;
        }

        return $pulse;
    }
}