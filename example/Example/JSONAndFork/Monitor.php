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
use Net\Bazzline\Component\Utility\Json;
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
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function __construct()
    {
        $this->file = new Json();
        $this->fileName = 'monitor.json';
        parent::__construct();
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
        /**
         * @var Heartbeat $heartbeat
         */
        $hash = spl_object_hash($heartbeat);
        $content = $this->getFileContent();
        $content->heartbeats[$hash] = array(
            'pid' => $heartbeat->getIdentity()->getId(),
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
        $heartbeats = (array) $content->heartbeats;
        unset($heartbeats[$hash]);
        $content->heartbeats = $heartbeats;
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
        return $this->file->getContent($this->fileName);
    }

    /**
     * @param stdClass $content
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    protected function setFileContent(stdClass $content)
    {
        return $this->file->setContent($this->fileName, $content);
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