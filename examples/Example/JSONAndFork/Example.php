<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-22
 */

namespace Example\JSONAndFork;

use Net\Bazzline\Component\Utility\Timestamp;

require_once __DIR__ . '/../../../vendor/autoload.php';

Example::create()
    ->setLoops(10)
    ->setupProcesses(10, 3, 2)
    //->setupProcesses(5, 0, 4)
    ->printSettings()
    ->andRun();

/**
 * Class Example
 *
 * @package Example\JSONAndFork
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-22
 */
class Example
{
    /**
     * @var int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    protected $currentLoop;

    /**
     * @var int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    protected $loops;

    /**
     * @var \Net\Bazzline\Component\Heartbeat\Monitor
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    protected $monitor;

    /**
     * @var int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-24
     */
    protected $numberOfExpectedFails;

    /**
     * @var int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-24
     */
    protected $numberOfExpectedFailsCritical;

    /**
     * @var int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    protected $sleep;

    /**
     * @return Example
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    public static function create()
    {
        $self = new self();

        return $self;
    }

    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    public function __construct()
    {
        $this->monitor = new Monitor();
        $this->monitor->setTimestamp(new Timestamp());
        $this->monitor->detachAll();
        $this->sleep = 1;
        $this->numberOfExpectedFails = 0;
        $this->numberOfExpectedFailsCritical = 0;
    }

    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    public function __destruct()
    {
        $this->monitor->deleteFile();
    }

    /**
     * @param int $duration
     * @return $this
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    public function setLoops($duration = 20)
    {
        $this->loops = (int) $duration;
        $this->currentLoop = 0;

        return $this;
    }

    /**
     * @param int $numberOfProcesses
     * @param int $numberOfWarning
     * @param int $numberOfCritical
     * @return $this
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    public function setupProcesses($numberOfProcesses = 3, $numberOfWarning = 0, $numberOfCritical = 0)
    {
        echo 'Calling process with parameters pid loopsPerClient fails(1/0) failsCritical(1/0) failsAtLoop' . PHP_EOL;
        for ($i = 0; $i < $numberOfProcesses; $i++) {
            $loopsPerClient = $this->loops + $numberOfProcesses - $i;
            $fails = 0;
            $failsCritical = 0;
            $failsAtLoop = rand($this->loops, $loopsPerClient - 1);
            if ($numberOfWarning > 0) {
                $fails = 1;
                $numberOfWarning--;
                $this->numberOfExpectedFails++;
            } else if ($numberOfCritical > 0) {
                $fails = 1;
                $failsCritical = 1;
                $numberOfCritical--;
                $this->numberOfExpectedFails++;
                $this->numberOfExpectedFailsCritical++;
            }

            $pid = 'process_' . $i;
            $processCall = __DIR__ . '/Client.php ' .  $pid . ' ' . $loopsPerClient . ' ' . $fails . ' ' . $failsCritical . ' ' . $failsAtLoop;
            echo 'Process call: ' . $processCall . PHP_EOL;
            exec('php ' . $processCall . '  > /dev/null &');
            //usleep(500000);
            sleep(1);
        }

        return $this;
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-22
     */
    public function printSettings()
    {
        echo PHP_EOL;
        echo str_repeat('-', 40) . PHP_EOL;
        echo 'number of heartbeats: ' . count($this->monitor->getAll()) . PHP_EOL;
        echo 'loops: ' . $this->loops . PHP_EOL;
        echo 'sleep: ' . $this->sleep . PHP_EOL;
        echo 'number of expected fails: ' . $this->numberOfExpectedFails . PHP_EOL;
        echo 'number of expected fails critical: ' . $this->numberOfExpectedFailsCritical . PHP_EOL;
        echo str_repeat('-', 40) . PHP_EOL;
        echo PHP_EOL;

        return $this;
    }

    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-17
     */
    public function andRun()
    {
        while ($this->currentLoop < $this->loops) {
            echo PHP_EOL;
            echo 'loop: ' . $this->currentLoop . '/' . $this->loops . PHP_EOL;
            echo 'number of heartbeats: ' . count($this->monitor->getAll()) . PHP_EOL;
            $this->monitor->listen();
            sleep($this->sleep);
            $this->currentLoop++;
        }
    }
}
