<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Example\JSONBasedImplementation;

use Net\Bazzline\Component\Heartbeat\AbstractHeartbeatClient;
use Net\Bazzline\Component\Heartbeat\RuntimeCriticalException;
use Net\Bazzline\Component\Heartbeat\RuntimeWarningException;
use Net\Bazzline\Component\ProcessIdentity\IdentityAwareInterface;
use Net\Bazzline\Component\ProcessIdentity\IdentityInterface;
use stdClass;

/**
 * Class Heartbeat
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */
class Heartbeat extends AbstractHeartbeatClient implements IdentityAwareInterface
{
    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $currentBeatNumber;

    /**
     * @var \Net\Bazzline\Component\ProcessIdentity\IdentityInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $identity;

    /**
     * @var string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $fileName;

    /**
     * @var bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $failsCritical;

    /**
     * @var
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-18
     */
    protected $failsOnBeatNumber;

    /**
     * @var
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-18
     */
    protected $lastTimeStamp;

    /**
     * @param IdentityInterface $identity
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function __construct(IdentityInterface $identity)
    {
        $this->setIdentity($identity);
        $this->fileName = $this->getIdentity()->getId() . '.json';
        $this->lastTimeStamp = time();
        $this->currentBeatNumber = 0;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     * This method returns the current timestamp as heartbeat.
     *
     * @return integer - timestamp of last beat
     * @throws RuntimeCriticalException|RuntimeWarningException
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function knock()
    {
        if (!file_exists($this->fileName)) {
            //this should never happen, have you done a beat before?
            throw new RuntimeCriticalException(
                'no data process file (' . $this->fileName . ') for data exchange found'
            );
        }
        $file = json_decode(file_get_contents($this->fileName));

        $timeDifference = $this->lastTimeStamp - $file->timestamp;
        if ($timeDifference >= 5) {
            throw new RuntimeCriticalException(
                'time difference is greater five seconds'
            );
        } else if ($timeDifference >= 2) {
            throw new RuntimeWarningException(
                'time difference is greater two seconds'
            );
        }
        $this->lastTimeStamp = time();

        return $file->timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function beat()
    {
        $currentTime = time();
        if (!is_null($this->failsOnBeatNumber)
            && ($this->currentBeatNumber >= $this->failsOnBeatNumber)) {
            if ($this->failsCritical) {
                $currentTime -= 6;
            } else {
                $currentTime -= 3;
            }
        }

        $file = (file_exists($this->fileName))
            ? json_decode(file_get_contents($this->fileName)) : new stdClass();
        if (!is_object($file)) {
            $file = new stdClass();
        }
        $file->timestamp = $currentTime;
        $file->uptime = $this->getUptime();
        $file->memoryUsage = $this->getMemoryUsage();
        $file->currentBeatNumber = $this->currentBeatNumber;

        file_put_contents($this->fileName, json_encode($file));
        $this->currentBeatNumber++;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function handleException(RuntimeCriticalException $exception)
    {
        $indent = "\t";
        echo $indent . str_repeat('-', 20) . PHP_EOL;
        if ($exception instanceof RuntimeCriticalException) {
            echo $indent . 'Heartbeat with identity ' . $this->getIdentity()->getId() . ' had a heart attack.' . PHP_EOL;
        } else {
            echo $indent . 'Heartbeat with identity ' . $this->getIdentity()->getId() . ' had an arrythmia.' . PHP_EOL;
        }
        echo $indent . 'Exception class ' . get_class($exception) . PHP_EOL;
        echo $indent . 'Exception message ' . $exception->getMessage() . PHP_EOL;
        if ($exception instanceof RuntimeCriticalException) {
            if (file_exists($this->fileName)) {
                echo $indent . str_repeat('-', 10) . PHP_EOL;
                echo $indent . 'Removing file ' . $this->fileName . PHP_EOL;
                unlink ($this->fileName);
            }
        }
        echo $indent . str_repeat('-', 20) . PHP_EOL;
        echo PHP_EOL;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentity(IdentityInterface $identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @param int $numberOfBeats
     * @param bool $failsCritical
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function setFailsOnBeatNumber($numberOfBeats, $failsCritical = false)
    {
        $this->failsOnBeatNumber = (int) $numberOfBeats;
        $this->failsCritical = $failsCritical;

        return $this;
    }
}