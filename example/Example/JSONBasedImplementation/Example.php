<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Example\JSONBasedImplementation;

require_once __DIR__ . '/../../../vendor/autoload.php';

Example::create()
    ->andSetupMonitor()
    ->andSetupHeartbeats(1)
    ->andRun();

/**
 * Class Example
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */
class Example
{
    /**
     * @var \Net\Bazzline\Component\Heartbeat\HeartbeatMonitorInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $monitor;

    /**
     * @var \Net\Bazzline\Component\Heartbeat\HeartbeatInterface[]
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $heartbeats;

    public static function create()
    {
        $self = new self();

        return $self;
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function andSetupMonitor()
    {
        $this->monitor = new Monitor();

        return $this;
    }

    /**
     * @param int $numberOfHeartbeats
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function andSetupHeartbeats($numberOfHeartbeats = 3)
    {
        for ($i = 0; $i < $numberOfHeartbeats; $i++) {
            $heartbeat = new Heartbeat();

            $this->heartbeats[] = $heartbeat;

            $this->monitor->attach($heartbeat);
        }

        return $this;
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function andRun()
    {
        //add creation of heartbeats
        //add creation of monitor

        echo 'Hello';
    }
}