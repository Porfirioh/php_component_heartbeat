<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Example\JSONAndFork;

use Net\Bazzline\Component\Heartbeat\CriticalRuntimeException;
use Net\Bazzline\Component\Heartbeat\HeartbeatAbstract;
use Net\Bazzline\Component\Heartbeat\RuntimeException;
use Net\Bazzline\Component\Heartbeat\WarningRuntimeException;
use Net\Bazzline\Component\ProcessIdentity\IdentityAwareInterface;
use Net\Bazzline\Component\ProcessIdentity\IdentityInterface;
use Net\Bazzline\Component\Utility\Json;
use stdClass;

/**
 * Class Heartbeat
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-22
 */
class Heartbeat extends HeartbeatAbstract implements IdentityAwareInterface
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
     * @since 2013-07-22
     */
    protected $identity;

    /**
     * @var Json
     * @author sleibelt
     * @since 2013-07-23
     */
    protected $file;

    /**
     * @var string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $fileName;

    /**
     * @var bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $failsCritical;

    /**
     * @var
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $failsOnBeatNumber;

    /**
     * @var
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $lastTimeStamp;

    /**
     * @param IdentityInterface $identity
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function __construct(IdentityInterface $identity)
    {
        $this->setIdentity($identity);
        $this->file = new Json();
        $this->fileName = $this->getIdentity()->getId() . '.json';
        $this->lastTimeStamp = time();
        $this->currentBeatNumber = 0;
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     * This method returns the current timestamp as heartbeat.
     *
     * @return integer - timestamp of last beat
     * @throws RuntimeException|CriticalRuntimeException|WarningRuntimeException
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function knock()
    {
        if (!file_exists($this->fileName)) {
            //this should never happen, have you done a beat before?
            throw new RuntimeException(
                'no data process file (' . $this->fileName . ') for data exchange found'
            );
        }
        $content = $this->getFileContent();

        $timeDifference = $this->lastTimeStamp - $content->timestamp;
        if ($timeDifference >= 5) {
            throw new CriticalRuntimeException(
                'time difference is greater five seconds'
            );
        } else if ($timeDifference >= 2) {
            throw new WarningRuntimeException(
                'time difference is greater two seconds'
            );
        }
        $this->lastTimeStamp = time();

        return $content->timestamp;
    }

    /**
     * {@inheritDoc}
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

        $content = $this->getFileContent();
        $content->timestamp = $currentTime;
        $content->uptime = $this->getUptime();
        $content->memoryUsage = $this->getMemoryUsage();
        $content->currentBeatNumber = $this->currentBeatNumber;
        $this->setFileContent($content);

        $this->currentBeatNumber++;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function handleHeartAttack(RuntimeException $exception)
    {
        $indent = "\t";
        echo $indent . str_repeat('-', 20) . PHP_EOL;
        if ($exception instanceof CriticalRuntimeException) {
            echo $indent . 'Heartbeat with identity ' . $this->getIdentity()->getId() . ' had a heart attack.' . PHP_EOL;
        } else {
            echo $indent . 'Heartbeat with identity ' . $this->getIdentity()->getId() . ' had an arrythmia.' . PHP_EOL;
        }
        echo $indent . 'Exception class ' . get_class($exception) . PHP_EOL;
        echo $indent . 'Exception message ' . $exception->getMessage() . PHP_EOL;
        if ($exception instanceof CriticalRuntimeException) {
            if (file_exists($this->fileName)) {
                echo $indent . str_repeat('-', 10) . PHP_EOL;
                echo $indent . 'Removing file ' . $this->fileName . PHP_EOL;
                $this->removeFile();
            }
        }
        echo $indent . str_repeat('-', 20) . PHP_EOL;
        echo PHP_EOL;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * {@inheritDoc}
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
     * @since 2013-07-22
     */
    public function setFailsOnBeatNumber($numberOfBeats, $failsCritical = false)
    {
        $this->failsOnBeatNumber = (int) $numberOfBeats;
        $this->failsCritical = $failsCritical;

        return $this;
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-23
     */
    public function deleteFile()
    {
        $this->removeFile();

        return $this;
    }

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-23
     */
    public function shouldFail()
    {
        return (!is_null($this->failsOnBeatNumber));
    }

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-23
     */
    public function shouldFailCritical()
    {
        return (!is_null($this->failsCritical) && ($this->failsCritical));
    }

    /**
     * @return stdClass
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-23
     */
    protected function getFileContent()
    {
        return $this->file->getContent($this->fileName, false);
    }

    /**
     * @param stdClass $content
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-23
     */
    protected function setFileContent(stdClass $content)
    {
        return $this->file->setContent($this->fileName, $content);
    }

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-23
     */
    protected function removeFile()
    {
        return file_exists($this->fileName) ? unlink($this->fileName) : true;
    }
}