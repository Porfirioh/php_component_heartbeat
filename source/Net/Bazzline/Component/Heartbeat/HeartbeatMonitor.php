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
class HeartbeatMonitor implements HeartbeatMonitorInterface, TimestampAwareInterface
{
    /**
     * @var SplObjectStorage
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    protected $storage;

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
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function __construct()
    {
        //use this method to centralize storage creation
        $this->detachAll();
    }

    /**
     * {@inheritdoc}
     */
    public function attach(HeartbeatClientInterface $client)
    {
        //prevent from adding the same object twice
        if ($this->storage->contains($client)) {
            throw new InvalidArgumentException(
                'Can not add already attached heartbeat'
            );
        }
        //add client to array by provided pulse and hash
        $this->storage->attach($client);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(HeartbeatClientInterface $client)
    {
        //validate if an entry for the provided pulse exist
        //validate if an entry for the provided pulse and hash exists
        if (!$this->storage->contains($client)) {
            throw new InvalidArgumentException(
                'Can not detach not attached heartbeat'
            );
        }
        //remove client from array
        $this->storage->detach($client);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return (array) $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function detachAll()
    {
        $this->storage = new SplObjectStorage();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listen()
    {
        $clients = $this->getClientsToKnock();
        $this->knockClients($clients);
        $this->updateClientsAfterKnocking($clients);

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
    protected function getClientsToKnock()
    {
        $this->storage->rewind();

        if ($this->hasTimestamp()) {
            $clientsToKnock = array();
            $currentTimestamp = $this->timestamp->getCurrentTimestamp();
echo 'current timestamp ' . $currentTimestamp . PHP_EOL;
            foreach ($this->storage as $client) {
                /**
                 * @var HeartbeatClientInterface $client
                 */
                $nextKnockTimestamp = $this->getNextKnockTimestamp($client);
echo 'next knock timestamp ' . $nextKnockTimestamp . PHP_EOL;
                if ($nextKnockTimestamp <= $currentTimestamp) {
                    $clientsToKnock[] = $client;
                }
            }
        } else {
            $clientsToKnock = array($this->storage);
        }

        return $clientsToKnock;
    }

    /**
     * @return array
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-24
     */
    protected function legacyCode()
    {
        //instead of doing fancy calculation, we should simple remember
        // (for each) pulse interval, when it was last triggered or
        // when it should be triggered next time (and then simple check
        // if current timestamp is less or equal then calculated/expected
        // pulse time).
        //
        //this would also simplify adaptation when implementing a database
        // based monitor. that's why we should implement a "calculateNextPulseTimestamp",
        // "storeNextPulseTimestamp" and so on.
        //----
        //first step, we need to get all available pulse times
        $availablePulses = array_keys($this->storage);
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
     * @param array $clients
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    protected function knockClients(array $clients)
    {
        //iterate over all available clients
        foreach ($clients as $client) {
            /**
             * @var HeartbeatClientInterface $client
             */
            try {
                $client->knock();
            } catch (RuntimeException $exception) {
                $client->handleException($exception);
                if ($exception instanceof CriticalRuntimeException) {
                    $this->detach($client);
                }
            }
        }
    }

    /**
     * @param array $clients
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-34
     */
    protected function updateClientsAfterKnocking(array $clients)
    {
        if ($this->hasTimestamp()) {
            //iterate over all available clients
            foreach ($clients as $client) {
                /**
                 * @var HeartbeatClientInterface $client
                 */
                if ($client instanceof PulseAwareInterface
                    && $client->hasPulse()) {
                    $client->getPulse()->setLastPulsedTimestamp();
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
        if ($client instanceof PulseAwareInterface) {
            $pulse = $client->getPulse();
        }

        $pulse = (!isset($pulse) || is_null($pulse) || $pulse <= 0) ? 1 : $pulse;

        return $pulse;
    }

    /**
     * @param HeartbeatClientInterface $client
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    protected function getNextKnockTimestamp(HeartbeatClientInterface $client)
    {
        if ($client instanceof PulseAwareInterface
            && $client->hasPulse()) {
echo 'has pulse' . PHP_EOL;
            $timestamp = $client->getPulse()->getLastPulsedTimestamp();
        }

        //do we have a timestamp object and get the current timestamp or should we use zero?
        $default = ($this->hasTimestamp()) ? $this->timestamp->getCurrentTimestamp() : 0;

        //do we have a valid timestamp or should we use the default value?
        $timestamp = (!isset($timestamp) || $timestamp < 0) ? $default : $timestamp;
echo 'timestamp ' . $timestamp . PHP_EOL;

        return $timestamp;
    }
}
