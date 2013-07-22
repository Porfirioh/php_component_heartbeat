<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-22
 */

namespace Example\JSONAndFork;

use Net\Bazzline\Component\Heartbeat\HeartbeatInterface;
use Net\Bazzline\Component\Heartbeat\HeartbeatMonitor;
use Net\Bazzline\Component\Heartbeat\RuntimeException;
use Net\Bazzline\Component\Heartbeat\CriticalRuntimeException;
use Net\Bazzline\Component\ProcessIdentity\Identity;
use stdClass;

/**
 * Class Monitor
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-22
 */
class Monitor extends HeartbeatMonitor
{
    /**
     * @var string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected $fileName;

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function __construct()
    {
        parent::__construct();

        $this->fileName = 'monitor.json';
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function createFile()
    {
        $this->removeFile();

        $content = new stdClass();
        $content->numberOfHeartbeats = 0;
        $content->heartbeats = array();

        $this->setFileContent($content);

        return $this;
    }

    /**
     * @return $this;
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function deleteFile()
    {
        $this->removeFile();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(HeartbeatInterface $heartbeat)
    {
        $hash = spl_object_hash($heartbeat);
        $content = $this->getFileContent();
        $content->heartbeats[$hash] = array(
            'pid' => $heartbeat->getIdentity()->getPid(),
            'uptime' => $heartbeat->getUptime(),
            'memoryUsage' => $heartbeat->getMemoryUsage()
        );
        $this->setFileContent($content);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(HeartbeatInterface $heartbeat)
    {
        $hash = spl_object_hash($heartbeat);
        $content = $this->getFileContent();
        unset($content->heartbeats[$hash]);
        $this->setFileContent($content);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        $content = $this->getFileContent();

        return $content->heartbeats;
    }

    /**
     * {@inheritDoc}
     */
    public function detachAll()
    {
        $content = $this->getFileContent();
        $content->heartbeats = array();
        $this->setFileContent($content);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function listen()
    {
        $currentTimestamp = time();
        $content = $this->getFileContent();

        foreach ($content->heartbeats as $heartbeatData) {
            $identity = new Identity();
            $identity->setId($heartbeatData->pid);
            $heartbeat = new Heartbeat($identity);
            /**
             * @var $heartbeat HeartbeatInterface
             */
            try {
                $heartbeat->knock();
            } catch (RuntimeException $exception) {
                $heartbeat->handleHeartAttack($exception);
                if ($exception instanceof CriticalRuntimeException) {
                    $this->detach($heartbeat);
                }
            }
        }

        $this->lastListenTimestamp = $currentTimestamp;

        return $this;
    }

    /**
     * @return stdClass
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected function getFileContent()
    {
        $content = (file_exists($this->fileName))
            ? json_decode(file_get_contents($this->fileName)) : new stdClass();

        return $content;
    }

    /**
     * @param stdClass $content
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected function setFileContent(stdClass $content)
    {
        return file_put_contents($this->fileName, json_encode($content));
    }

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected function removeFile()
    {
        return file_exists($this->fileName) ? unlink($this->fileName) : true;
    }
}