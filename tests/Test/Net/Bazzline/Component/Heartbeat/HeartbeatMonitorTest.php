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
    public function AtestListenWithTwoClientsAndWithoutSleep()
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
    public function AtestListenWithTwoClientsAndWithOneSleep()
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
        $currentTimestamp = time();
        $currentTimestampPlusOneSecond = $currentTimestamp + 1;
        $currentTimestampPlusThreeSeconds = $currentTimestamp + 3;
        $monitor = $this->getNewMonitor();
        $timestamp = $this->getNewMockTimestamp();
        $timestamp->shouldReceive('getCurrentTimestamp')
            ->andReturnValues(
                array(
                    //return value for first listen call
                    $currentTimestamp, $currentTimestamp, $currentTimestamp,
                    //return value for second listen call
                    $currentTimestampPlusOneSecond, $currentTimestampPlusOneSecond, $currentTimestampPlusOneSecond,
                    //return value for third listen call
                    $currentTimestampPlusThreeSeconds, $currentTimestampPlusThreeSeconds, $currentTimestampPlusThreeSeconds
                )
            )
            ->times(9);
        $monitor->setTimestamp($timestamp);

        $threeSecondPulse = $this->getNewMockPulse();
        $threeSecondPulse->shouldReceive('getLastPulsedTimestamp')
            ->andReturnValues(array($currentTimestamp, $currentTimestamp + 3, $currentTimestamp + 6))
            ->times(3);
        $threeSecondPulse->shouldReceive('setLastPulsedTimestamp')
            ->with($currentTimestamp)
            ->once();
        $threeSecondPulse->shouldReceive('setLastPulsedTimestamp')
            ->with($currentTimestampPlusThreeSeconds)
            ->once();
        $threeSecondPulseClient = $this->getNewMockHeartbeatClientWithPulse();
        $threeSecondPulseClient->shouldReceive('hasPulse')
            ->andReturn(true)
            ->times(4);
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn($threeSecondPulse)
            ->times(4);
        $threeSecondPulseClient->shouldReceive('knock')
            ->times(2);

        $zeroSecondPulse = $this->getNewMockPulse();
        $zeroSecondPulse->shouldReceive('getLastPulsedTimestamp')
            ->andReturnValues(array($currentTimestamp, $currentTimestampPlusOneSecond, $currentTimestampPlusThreeSeconds))
            ->times(3);
        $zeroSecondPulse->shouldReceive('setLastPulsedTimestamp')
            ->with($currentTimestamp)
            ->once();
        $zeroSecondPulse->shouldReceive('setLastPulsedTimestamp')
            ->with($currentTimestampPlusOneSecond)
            ->once();
        $zeroSecondPulse->shouldReceive('setLastPulsedTimestamp')
            ->with($currentTimestampPlusThreeSeconds)
            ->once();
        $zeroSecondPulseClient = $this->getNewMockHeartbeatClientWithPulse();
        $zeroSecondPulseClient->shouldReceive('hasPulse')
            ->andReturn(true)
            ->times(6);
        $zeroSecondPulseClient->shouldReceive('getPulse')
            ->andReturn($zeroSecondPulse)
            ->times(6);
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