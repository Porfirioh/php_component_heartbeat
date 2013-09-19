<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

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
    public function testAttachHeartbeat()
    {
        $monitor = $this->getNewMonitor();
        $client = $this->getNewMockHeartbeatClient();

        $this->assertEquals(
            $monitor,
            $monitor->attach($client)
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
        $client = $this->getNewMockHeartbeatClient();
        $client->shouldReceive('getPulse')
            ->andReturn(15)
            ->twice();

        $monitor->attach($client);
        $monitor->attach($client);
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
        $client = $this->getNewMockHeartbeatClient();

        $this->assertEquals(
            $monitor,
            $monitor->detach($client)
        );
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function testDetach()
    {
        $monitor = $this->getNewMonitor();
        $client = $this->getNewMockHeartbeatClient();
        $client->shouldReceive('getPulse')
            ->andReturn(15)
            ->twice();

        $monitor->attach($client);

        $this->assertEquals(
            $monitor,
            $monitor->detach($client)
        );
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function testListenWithTwoClientsAndWithoutSleep()
    {
        $monitor = $this->getNewMonitor();

        $threeSecondPulseClient = $this->getNewMockHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(3)
            ->once();
        $threeSecondPulseClient->shouldReceive('knock')
            ->twice();

        $zeroSecondPulseClient = $this->getNewMockHeartbeatClient();
        $zeroSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(0)
            ->once();
        $zeroSecondPulseClient->shouldReceive('knock')
            ->twice();

        $monitor->attach($threeSecondPulseClient);
        $monitor->attach($zeroSecondPulseClient);

        $monitor->listen();
        $monitor->listen();
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function testListenWithTwoClientsAndWithOneSleep()
    {
        $monitor = $this->getNewMonitor();
        $timestamp = $this->getNewMockTimestamp();
        $timestamp->shouldReceive('getTimestampDifference')
            ->andReturnValues(array(0, 1))
            ->twice();
        $monitor->setTimestamp($timestamp);

        $threeSecondPulseClient = $this->getNewMockHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(3)
            ->once();
        $threeSecondPulseClient->shouldReceive('knock')
            ->once();

        $zeroSecondPulseClient = $this->getNewMockHeartbeatClient();
        $zeroSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(0)
            ->once();
        $zeroSecondPulseClient->shouldReceive('knock')
            ->twice();

        $monitor->attach($threeSecondPulseClient);
        $monitor->attach($zeroSecondPulseClient);

        $monitor->listen();
        $monitor->listen();
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function testListenWithTwoClientsAndTwoSleeps()
    {
echo __METHOD__ . PHP_EOL;
        $monitor = $this->getNewMonitor();
        $timestamp = $this->getNewMockTimestamp();
        $timestamp->shouldReceive('getTimestampDifference')
            ->andReturnValues(array(0, 2, 1))
            ->times(3);
        $monitor->setTimestamp($timestamp);

        $threeSecondPulseClient = $this->getNewMockHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(3)
            ->once();
        $threeSecondPulseClient->shouldReceive('knock')
            ->twice();

        $zeroSecondPulseClient = $this->getNewMockHeartbeatClient();
        $zeroSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(0)
            ->once();
        $zeroSecondPulseClient->shouldReceive('knock')
            ->times(3);

        $monitor->attach($threeSecondPulseClient);
        $monitor->attach($zeroSecondPulseClient);

        $monitor->listen();
        $monitor->listen();
        $monitor->listen();
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function AtestListenWithFourClientsAndThreeSleeps()
    {
        $monitor = $this->getNewMonitor();
        $timestamp = $this->getNewMockTimestamp();
        $timestamp->shouldReceive('getTimestampDifference')
            ->andReturnValues(array(0, 4, 2))
            ->times(3);
        $monitor->setTimestamp($timestamp);

        $threeSecondPulseClient = $this->getNewMockHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(3)
            ->once();
        $threeSecondPulseClient->shouldReceive('knock')
            ->times(2);

        $oneSecondPulseClient = $this->getNewMockHeartbeatClient();
        $oneSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(1)
            ->once();
        $oneSecondPulseClient->shouldReceive('knock')
            ->times(3);

        $sixSecondPulseClient = $this->getNewMockHeartbeatClient();
        $sixSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(6)
            ->once();
        $sixSecondPulseClient->shouldReceive('knock')
            ->twice();

        $twoSecondPulseClient = $this->getNewMockHeartbeatClient();
        $twoSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(2)
            ->once();
        $twoSecondPulseClient->shouldReceive('knock')
            ->times(3);

        $monitor->attach($threeSecondPulseClient);
        $monitor->attach($oneSecondPulseClient);
        $monitor->attach($sixSecondPulseClient);
        $monitor->attach($twoSecondPulseClient);

        $monitor->listen();
        $monitor->listen();
        $monitor->listen();
    }
}