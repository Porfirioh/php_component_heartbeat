<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

use Net\Bazzline\Component\Heartbeat\HeartbeatMonitor;
use Mockery;

/**
 * Class HeartbeatMonitorTest
 *
 * @package Test\Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */
class HeartbeatMonitorTest extends TestCase
{
    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function testAttachHeartbeat()
    {
        $monitor = $this->getNewMonitor();
        $heartbeat = $this->getNewHeartbeat();

        $this->assertEquals(
            $monitor,
            $monitor->attach($heartbeat)
        );
    }

    /**
     * @expectedException \Net\Bazzline\Component\Heartbeat\InvalidArgumentException
     * @expectedExceptionMessage Can not add already attached heartbeat
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function testAttachSameHeartbeatTwice()
    {
        $monitor = $this->getNewMonitor();
        $heartbeat = $this->getNewHeartbeat();
        $heartbeat->shouldReceive('getPulse')
            ->andReturn(15)
            ->twice();

        $monitor->attach($heartbeat);
        $monitor->attach($heartbeat);
    }

    /**
     * @expectedException \Net\Bazzline\Component\Heartbeat\InvalidArgumentException
     * @expectedExceptionMessage Can not detach not attached heartbeat
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function testDetachWithNotAttachedHeartbeat()
    {
        $monitor = $this->getNewMonitor();
        $heartbeat = $this->getNewHeartbeat();

        $this->assertEquals(
            $monitor,
            $monitor->detach($heartbeat)
        );
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function testDetach()
    {
        $monitor = $this->getNewMonitor();
        $heartbeat = $this->getNewHeartbeat();
        $heartbeat->shouldReceive('getPulse')
            ->andReturn(15)
            ->twice();

        $monitor->attach($heartbeat);

        $this->assertEquals(
            $monitor,
            $monitor->detach($heartbeat)
        );
    }

    /**
     * @return \Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\HeartbeatClientInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    private function getNewHeartbeat()
    {
        $heartbeat = Mockery::mock('Net\Bazzline\Component\Heartbeat\HeartbeatAbstract');
        $heartbeat->shouldReceive('getPulse')
            ->andReturn(15)
            ->once()
            ->byDefault();

        return $heartbeat;
    }

    /**
     * @return HeartbeatMonitor
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    private function getNewMonitor()
    {
        $monitor = new HeartbeatMonitor();

        return $monitor;
    }
}