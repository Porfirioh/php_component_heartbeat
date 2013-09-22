<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

use Net\Bazzline\Component\Utility\TimestampInterface;
use Net\Bazzline\Component\Utility\TimestampAwareInterface;

/**
 * Class HeartbeatMonitor
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
class HeartbeatMonitor implements HeartbeatMonitorInterface, TimestampAwareInterface
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
     * @since 2013-09-18
     */
    protected $lastTimestampValue;

    /**
     * @var TimestampInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    protected $timestamp;

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
        $pulses = $this->getPulses();
        $this->knockPulses($pulses);

        return $this;
    }

    /**
     * @return null|TimestampInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function hasTimestamp()
    {
        return (!is_null($this->timestamp));
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function removeTimestamp()
    {
        $this->timestamp = null;

        return $this;
    }

    /**
     * @param TimestampInterface $timestamp
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function setTimestamp(TimestampInterface $timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return array
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    protected function getPulses()
    {
        //instead of doing fanzy calculation, we should simple rember
        // (for each) pulse interval, when it was last triggered or
        // when it should be triggered next time (and then simple check
        // if current timestamp is less or equal then caluclated/expected
        // pulse time).
        //
        //this would also simplify adaptation when implementing a database
        // based monitor. thats why we should implement a "calculateNextPulseTimestamp",
        // "storeNextPulseTimestamp" and so on.
        //----
        //first step, we need to get all available pulse times
        $availablePulses = array_keys($this->clientsPerPulse);
        //if time difference > 1 second,
        // get back all pulses by using step of 1 second
        // to calculate all available pulses
        //only get back each pulse once
        if ($this->hasTimestamp()) {
            //to speed things up, we create a second array with all pulse
            // intervals as keys
            $pulsesAsKeys = array();
            $timeDifference = $this->timestamp->getTimestampDifference();
            while ($timeDifference > 0) {
echo var_export(array(
        'diff' => $timeDifference
    ), true) . PHP_EOL;
                //calculate which pulses should be called
                foreach ($availablePulses as $pulse) {
                    if (!isset($pulsesAsKeys[$pulse])) {
                        //calculate seconds between last run and current time
                        //the result is the minimal number of seconds that should be passed
                        // before a next knock/request is done on the client
                        //if pulse is smaller or equal to the maximum pulse, it should be
                        // knocked
                        /*
                        $knockClientsForThisPulse = (($pulse == 0)
                            || (($timeDifference % $pulse) === 0));
                        */
                        $knockClientsForThisPulse = (($timeDifference % $pulse) === 0);
                        if ($knockClientsForThisPulse) {
                            $pulsesAsKeys[$pulse] = true;
                        }
                    }
                }
                $timeDifference--;
            }
            $pulses = array_keys($pulsesAsKeys);
        } else {
            $pulses = $availablePulses;
        }

        return $pulses;
    }

    /**
     * @param array $pulses
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    protected function knockPulses(array $pulses)
    {
        //iterate over all available pulse/minimum number of passed seconds
        foreach ($pulses as $pulse) {
//echo 'pulse ' . $pulse . PHP_EOL;
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

    /**
     * @param HeartbeatClientInterface $client
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    protected function getPulse(HeartbeatClientInterface $client)
    {
        if ($client instanceof PulseableInterface) {
            $pulse = $client->getPulse();
        }

        $pulse = (!isset($pulse) || is_null($pulse) || $pulse <= 0) ? 1 : $pulse;

        return $pulse;
    }
}
