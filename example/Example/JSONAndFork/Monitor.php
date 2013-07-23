<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-22
 */

namespace Example\JSONAndFork;

use ___PHPSTORM_HELPERS\object;
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
        $file = $this->getFile();
        $content = json_decode($file->fpassthru());
        if (!is_array($content)) {
            $content = array();
        }
echo 'Trying to attach: ' . var_export(array('hash' => $hash, 'heartbeat' => $heartbeat), true) . PHP_EOL;
        if (!isset($content['heartbeats'])) {
            $content['heartbeats'] = array();
        }
        $content['heartbeats'][$hash] = array(
            'pid' => $heartbeat->getIdentity()->getId(),
            'uptime' => $heartbeat->getUptime(),
            'memoryUsage' => $heartbeat->getMemoryUsage()
        );
        $file->fwrite(json_encode($content));
        $file->fflush();
        $file->flock(LOCK_UN);
        unset($file);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(HeartbeatInterface $heartbeat)
    {
        $hash = spl_object_hash($heartbeat);
        $file = $this->getFile();
        $content = json_decode($file->fpassthru());
echo 'Trying to detach: ' . var_export(array('hash' => $hash, 'heartbeat' => $heartbeat), true) . PHP_EOL;
        if (isset($content['heartbeats'])){
            unset($content['heartbeats'][$hash]);
            $file->fwrite(json_encode($content));
            $file->fflush();
            $file->flock(LOCK_UN);
            unset($file);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        $file = $this->getFile();
        $content = json_decode($file->fpassthru());
        unset($file);

        return (isset($content['heartbeats'])) ? $content['heartbeats'] : array();
    }

    /**
     * {@inheritDoc}
     */
    public function detachAll()
    {
        $file = $this->getFile();
        $content = json_decode($file->fpassthru());
        if (isset($content['heartbeats'])){
            unset($content['heartbeats']);
            $file->fwrite(json_encode($content));
            $file->fflush();
            $file->flock(LOCK_UN);
            unset($file);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function listen()
    {
        $currentTimestamp = time();
        $file = $this->getFile();
        $content = json_decode($file->fpassthru());

        if (isset($content['heartbeats'])) {
            foreach ($content['heartbeats'] as $heartbeatData) {
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
}