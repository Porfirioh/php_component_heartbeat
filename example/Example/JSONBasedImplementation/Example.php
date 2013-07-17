<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Example\JSONBasedImplementation;

use Net\Bazzline\Component\ProcessIdentity\Identity;

require_once __DIR__ . '/../../../vendor/autoload.php';

Example::create()
    ->setLoops(10)
    ->setupHeartbeats(10, 3, 2)
    ->printSettings()
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
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $currentLoop;

    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $loops;

    /**
     * @var \Net\Bazzline\Component\Heartbeat\HeartbeatMonitor
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $monitor;

    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $sleep;

    /**
     * @var Heartbeat[]
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected $heartbeats;

    /**
     * @return Example
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public static function create()
    {
        $self = new self();
        $self->setupMonitor()
            ->setLoops(30)
            ->setSleep(1);

        return $self;
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function __destruct()
    {
        foreach ($this->heartbeats as $heartbeat)
        {
            $fileName = $heartbeat->getIdentity()->getId() . '.json';
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
    }

    /**
     * @param int $duration
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function setLoops($duration = 20)
    {
        $this->loops = (int) $duration;
        $this->currentLoop = 0;

        return $this;
    }

    /**
     * @param int $sleep
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function setSleep($sleep = 1)
    {
        $this->sleep = $sleep;

        return $this;
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
     * @param int $numberOfWarning
     * @param int $numberOfCritical
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function setupHeartbeats($numberOfHeartbeats = 3, $numberOfWarning = 0, $numberOfCritical = 0)
    {
        for ($i = 0; $i < $numberOfHeartbeats; $i++) {
            $identity = new Identity();
            $identity->setId('heartbeat_' . $i);
            touch($identity->getId() . '.json');
            $heartbeat = new Heartbeat($identity);

            if ($numberOfWarning > 0) {
                $heartbeat->setFailsOnBeatNumber(rand(1, ($this->loops - 1)));
                $numberOfWarning--;
            } else if ($numberOfCritical > 0) {
                $heartbeat->setFailsOnBeatNumber(rand(1, ($this->loops - 1)), true);
                $numberOfCritical--;
            }

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
    public function printSettings()
    {
        echo str_repeat('-', 40) . PHP_EOL;
        echo 'number of heartbeats: ' . count($this->monitor->getAll()) . PHP_EOL;
        echo 'loops: ' . $this->loops . PHP_EOL;
        echo 'sleep: ' . $this->sleep . PHP_EOL;
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
        while ($this->currentLoop < $this->loops) {
            echo 'loop: ' . $this->currentLoop . '/' . $this->loops . PHP_EOL;
            foreach ($this->heartbeats as $heartbeat) {
                $heartbeat->beat();
            }
            $this->monitor->listen();
            sleep($this->sleep);
            $this->currentLoop++;
        }

        echo 'number of heartbeats: ' . count($this->monitor->getAll()) . PHP_EOL;
    }
}