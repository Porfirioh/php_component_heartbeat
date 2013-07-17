<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Example\JSONBasedImplementation;

require_once __DIR__ . '/../../../vendor/autoload.php';

Example::create()
    ->setupMonitor()
    ->setupHeartbeats(1)
    ->printStatistic()
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
    public function setupMonitor()
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
    public function setupHeartbeats($numberOfHeartbeats = 3)
    {
        for ($i = 0; $i < $numberOfHeartbeats; $i++) {
            $heartbeat = new Heartbeat();

            $this->heartbeats[] = $heartbeat;

            $this->monitor->attach($heartbeat);
        }

        return $this;
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function printStatistic()
    {
        echo str_repeat('-', 40) . PHP_EOL;
        echo 'number of heartbeats: ' . count($this->monitor->getAll()) . PHP_EOL;
        echo str_repeat('-', 40) . PHP_EOL;
        echo PHP_EOL;

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