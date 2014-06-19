<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

use Net\Bazzline\Component\Utility\TimestampInterface;
use Net\Bazzline\Component\Utility\TimestampAwareInterface;
use SplObjectStorage;

/**
 * Class Monitor
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-14
 */
class Monitor implements MonitorInterface, TimestampAwareInterface
{
    /**
     * @var SplObjectStorage
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-14
     */
    protected $storage;

    /**
     * @var int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-18
     */
    protected $lastTimestampValue;

    /**
     * @var TimestampInterface
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-18
     */
    protected $timestamp;

    /**
     * @author stev leibelt <artodeto@bazzline.net>
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
    public function attach(ClientInterface $client)
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
    public function detach(ClientInterface $client)
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
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-18
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return bool
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-18
     */
    public function hasTimestamp()
    {
        return (!is_null($this->timestamp));
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@bazzline.net>
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
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-18
     */
    public function setTimestamp(TimestampInterface $timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return array
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-18
     */
    protected function getClientsToKnock()
    {
        $this->storage->rewind();

        if ($this->hasTimestamp()) {
            $clientsToKnock = array();
            $currentTimestamp = $this->timestamp->getCurrentTimestamp();
            foreach ($this->storage as $client) {
                /**
                 * @var ClientInterface $client
                 */
                $nextKnockTimestamp = $this->getNextKnockTimestamp($client);
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
     * @param ClientInterface $client
     * @param RuntimeException $exception
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-10-05
     */
    protected function handleClientException(ClientInterface $client, RuntimeException $exception)
    {
        if ($exception instanceof CriticalRuntimeException) {
            $this->detach($client);
        }
    }

    /**
     * @param array $clients
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-18
     */
    private function knockClients(array $clients)
    {
        //iterate over all available clients
        foreach ($clients as $client) {
            /**
             * @var ClientInterface $client
             */
            try {
                $client->knock();
            } catch (RuntimeException $exception) {
                $client->handleException($exception);
                $this->handleClientException($client, $exception);
            }
        }
    }

    /**
     * @param array $clients
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-34
     */
    private function updateClientsAfterKnocking(array $clients)
    {
        if ($this->hasTimestamp()) {
            //iterate over all available clients
            foreach ($clients as $client) {
                /**
                 * @var ClientInterface $client
                 */
                $this->preUpdateClientAfterKnocking($client);
                if ($client instanceof PulseAwareInterface
                    && $client->hasPulse()) {
                    $client->getPulse()->updateLastPulsedTimestamp();
                }
                $this->postUpdateClientAfterKnocking($client);
            }
        }
    }

    /**
     * @param ClientInterface $client
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-10-01
     */
    protected function preUpdateClientAfterKnocking(ClientInterface $client)
    {
    }

    /**
     * @param ClientInterface $client
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-10-01
     */
    protected function postUpdateClientAfterKnocking(ClientInterface $client)
    {
    }

    /**
     * @param ClientInterface $client
     * @return int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-15
     */
    protected function getNextKnockTimestamp(ClientInterface $client)
    {
        if ($client instanceof PulseAwareInterface
            && $client->hasPulse()) {
            $timestamp = $client->getPulse()->getNextPulseTimestamp();
        }

        //do we have a timestamp object and get the current timestamp or should we use zero?
        $default = ($this->hasTimestamp()) ? $this->timestamp->getCurrentTimestamp() : 0;

        //do we have a valid timestamp or should we use the default value?
        $timestamp = (!isset($timestamp) || $timestamp < 0) ? $default : $timestamp;

        return $timestamp;
    }
}
