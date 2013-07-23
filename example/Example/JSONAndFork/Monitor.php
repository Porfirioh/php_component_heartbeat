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
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-22
     */
    public function createFile()
    {
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
        //$hash = spl_object_hash($heartbeat);
        $hash = $heartbeat->getIdentity()->getId();
        $content = $this->getFileContent();
        /*
        $file = $this->getFile();
        $fileContent = '';
        while(!$file->eof()) {
            $fileContent .= $file->fgets();
        }
        $content = json_decode($fileContent, true);
        $this->exitOnJsonLastError();
echo 'content: ' . var_export($content, true) . PHP_EOL;
        */
        $this->exitOnJsonLastError();
        /*
        if (!is_array($content)
            || empty($content)) {
            $content = array();
        }
        */
//echo 'Trying to attach: ' . var_export(array('hash' => $hash, 'heartbeat' => $heartbeat->getIdentity()->getId()), true) . PHP_EOL;
        /*
        if (!isset($content['heartbeats'])) {
            $content['heartbeats'] = array();
        }
        */
        $content['heartbeats'][$hash] = array(
            'pid' => $heartbeat->getIdentity()->getId(),
            'uptime' => $heartbeat->getUptime(),
            'memoryUsage' => $heartbeat->getMemoryUsage()
        );
        $this->setFileContent($content);
        /*
        $fileContent = json_encode($content);
echo __LINE__ . PHP_EOL;
echo 'content: ' . var_export($content, true) . PHP_EOL;
echo 'file content: ' . var_export($fileContent, true) . PHP_EOL;
        $this->exitOnJsonLastError();
        $file->fwrite($fileContent);
        $file->fflush();
        $file->flock(LOCK_UN);
        unset($file);
        */

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(HeartbeatInterface $heartbeat)
    {
        /**
         * @var Heartbeat $heartbeat
         */
        //$hash = spl_object_hash($heartbeat);
        $hash = $heartbeat->getIdentity()->getId();
        $content = $this->getFileContent();
        /*
        $file = $this->getFile();
        $fileContent = '';
        while(!$file->eof()) {
            $fileContent .= $file->fgets();
        }
        $content = json_decode($fileContent, true);
        */
//echo 'Trying to detach: ' . var_export(array('hash' => $hash, 'heartbeat' => $heartbeat->getIdentity()->getId()), true) . PHP_EOL;
        if (isset($content['heartbeats'])){
            unset($content['heartbeats'][$hash]);
            $fileContent = json_encode($content);
/*
echo __LINE__ . PHP_EOL;
echo 'content: ' . var_export($content, true) . PHP_EOL;
echo 'file content: ' . var_export($fileContent, true) . PHP_EOL;
*/
            $this->setFileContent($content);
            /*
            $this->exitOnJsonLastError();
            $file->fwrite($fileContent);
            $file->fflush();
            $file->flock(LOCK_UN);
            unset($file);
            */
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        $content = $this->getFileContent();
        /*
        $file = $this->getFile();
        $fileContent = '';
        while(!$file->eof()) {
            $fileContent .= $file->fgets();
        }
        $content = json_decode($fileContent, true);
        $file->flock(LOCK_UN);
        unset($file);
        */
/*
echo __LINE__ . PHP_EOL;
echo '====' . PHP_EOL;
//echo 'fileContent:: ' . var_export($fileContent, true) . PHP_EOL;
echo 'content:: ' . var_export($content, true) . PHP_EOL;
echo 'json last error:: ' . var_export(json_last_error(), true) . PHP_EOL;
echo '====' . PHP_EOL;
*/
        $this->exitOnJsonLastError();

        return (!is_null($content) && isset($content['heartbeats']) && !empty($content['heartbeats'])) ? $content['heartbeats'] : array();
    }

    /**
     * {@inheritDoc}
     */
    public function detachAll()
    {
        $content = $this->getFileContent();
        /*
        $file = $this->getFile();
        $fileContent = '';
        while(!$file->eof()) {
            $fileContent .= $file->fgets();
        }
        $content = json_decode($fileContent, true);
        */
        $this->exitOnJsonLastError();
        if (!is_null($content) && isset($content['heartbeats'])){
            $content['heartbeats'] = array();
            $fileContent = json_encode($content);
/*
echo __LINE__ . PHP_EOL;
echo 'fileContent:: ' . var_export($fileContent, true) . PHP_EOL;
echo 'content:: ' . var_export($content, true) . PHP_EOL;
*/
            $this->setFileContent($content);
            /*
            $this->exitOnJsonLastError();
            $file->fwrite($fileContent);
            */
            $this->exitOnJsonLastError();
            /*
            $file->fflush();
            $file->flock(LOCK_UN);
            unset($file);
            */
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function listen()
    {
        $currentTimestamp = time();
        $content = $this->getFileContent();
        /*
        $file = $this->getFile();
        $fileContent = '';
        while(!$file->eof()) {
            $fileContent .= $file->fgets();
        }
        $content = json_decode($fileContent, true);
        */
        $this->exitOnJsonLastError();

        if (isset($content['heartbeats'])
            && !empty($content['heartbeats'])) {
            $heartbeats = array();
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
                    $heartbeat->handleHeartAttack($exception);
                    if ($exception instanceof CriticalRuntimeException) {
                        $this->detach($heartbeat);
                    }
                }
            }
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