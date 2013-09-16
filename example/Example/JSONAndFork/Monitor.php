<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-22
 */

namespace Example\JSONAndFork;

use Net\Bazzline\Component\Heartbeat\HeartbeatClientInterface;
use Net\Bazzline\Component\Heartbeat\HeartbeatMonitor;
use Net\Bazzline\Component\Heartbeat\RuntimeException;
use Net\Bazzline\Component\Heartbeat\CriticalRuntimeException;
use Net\Bazzline\Component\ProcessIdentity\Identity;
use Net\Bazzline\Component\Utility\Json;

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
        $this->lastListen = time();
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
     * {@inheritdoc}
     */
    public function attach(HeartbeatClientInterface $heartbeat)
    {
        /**
         * @var Heartbeat $heartbeat
         */
        $hash = $heartbeat->getIdentity()->getId();
        $content = $this->getFileContent();
        $this->exitOnJsonLastError();
        $content['heartbeats'][$hash] = array(
            'pid' => $heartbeat->getIdentity()->getId(),
            'uptime' => $heartbeat->getUptime(),
            'memoryUsage' => $heartbeat->getMemoryUsage()
        );
        $this->setFileContent($content);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(HeartbeatClientInterface $heartbeat)
    {
        /**
         * @var Heartbeat $heartbeat
         */
        $hash = $heartbeat->getIdentity()->getId();
        $content = $this->getFileContent();
        if (isset($content['heartbeats'])){
            unset($content['heartbeats'][$hash]);
            $this->setFileContent($content);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $content = $this->getFileContent();
        $this->exitOnJsonLastError();

        return (!is_null($content) && isset($content['heartbeats']) && !empty($content['heartbeats'])) ? $content['heartbeats'] : array();
    }

    /**
     * {@inheritdoc}
     */
    public function detachAll()
    {
        $content = $this->getFileContent();
        $this->exitOnJsonLastError();
        if (!is_null($content) && isset($content['heartbeats'])){
            $content['heartbeats'] = array();
            $this->setFileContent($content);
            $this->exitOnJsonLastError();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listen()
    {
        $currentTimestamp = time();
        $content = $this->getFileContent();
        $this->exitOnJsonLastError();

        if (isset($content['heartbeats'])
            && !empty($content['heartbeats'])) {
            $heartbeats = array();
            echo 'beats: ' . implode(', ', array_keys($content['heartbeats'])) . PHP_EOL;
            foreach ($content['heartbeats'] as $hash => $heartbeatData) {
                $identity = new Identity();
                $identity->setId($heartbeatData['pid']);
                $heartbeat = new Heartbeat($identity);
                /**
                 * @var $heartbeat Heartbeat
                 */
                try {
                    $heartbeat->knock();
                    $heartbeats[$hash] = array(
                        'pid' => $heartbeatData['pid'],
                        'uptime' => $heartbeat->getUptime(),
                        'memoryUsage' => $heartbeat->getMemoryUsage()
                    );
                } catch (RuntimeException $exception) {
                    $heartbeats[$hash] = array(
                        'pid' => $heartbeatData['pid'],
                        'uptime' => $heartbeat->getUptime(),
                        'memoryUsage' => $heartbeat->getMemoryUsage()
                    );
                    $heartbeat->handleException($exception);
                    if ($exception instanceof CriticalRuntimeException) {
                        unset($heartbeats[$hash]);
                        $this->detach($heartbeat);
                    }
                }
            }
            $content['heartbeats'] = $heartbeats;
            $this->setFileContent($content);
        }

        $this->lastListenTimestamp = $currentTimestamp;

        return $this;
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

    protected function getFileContent()
    {
        return $this->file->getContent($this->fileName, true);
    }

    protected function setFileContent($content)
    {
        return $this->file->setContent($this->fileName, $content);
    }

    /**
     * @return \SplFileObject
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-23
     */
    protected function getFile()
    {
        $file = new \SplFileObject($this->fileName, 'c+');
        $file->flock(LOCK_EX);

        return $file;
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-23
     */
    protected function exitOnJsonLastError()
    {
        if (json_last_error() > 0) {
            echo 'ERROR:: ' . json_last_error() . PHP_EOL;
            echo var_export(debug_backtrace(), true) . PHP_EOL;
            exit(1);
        }
    }
}