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
     * @since 2013-09-24
     */
    public function getAll()
    {
        $monitor = $this->getNewMonitor();
        $firstClient = $this->getNewMockHeartbeatClient();
        $secondClient = $this->getNewMockHeartbeatClient();

        $monitor->attach($firstClient);
        $monitor->attach($secondClient);

        $expectedGetAll = array($firstClient, $secondClient);

        $this->assertEquals($expectedGetAll, $monitor->getAll());
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-24
     */
    public function detachAll()
    {
        $monitor = $this->getNewMonitor();
        $firstClient = $this->getNewMockHeartbeatClient();
        $secondClient = $this->getNewMockHeartbeatClient();

        $expectedGetAll = array();
        $this->assertEquals($expectedGetAll, $monitor->getAll());

        $monitor->attach($firstClient);
        $monitor->attach($secondClient);
        $monitor->detachAll();

        $this->assertEquals($expectedGetAll, $monitor->getAll());
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function testListenWithTwoClientsAndThreeSleeps()
    {
        $currentTimestamp = time();
        $monitor = $this->getNewMonitor();
        $timestamp = $this->getNewMockTimestamp();
        $timestamp->shouldReceive('getCurrentTimestamp')
            ->andReturn($currentTimestamp)
            ->times(9); //3 times for monitor internal stuff and up to 3 times for each attached client
        $monitor->setTimestamp($timestamp);

        $threeSecondPulseClient = $this->getNewMockHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('knock')
            ->times(3);

        $zeroSecondPulseClient = $this->getNewMockHeartbeatClient();
        $zeroSecondPulseClient->shouldReceive('knock')
            ->times(3);

        $monitor->attach($threeSecondPulseClient);
        $monitor->attach($zeroSecondPulseClient);

        $monitor->listen();
        $monitor->listen();
        $monitor->listen();
    }

    /**
     * When a client implements the pulse aware interface but has no pulse, he
     *  is treated like a client that do not implement the pulse aware interface
     *
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function testListenWithTwoClientsOfPulseAwareInterfaceAndThreeSleeps()
    {
        $currentTimestamp = time();
        $monitor = $this->getNewMonitor();
        $timestamp = $this->getNewMockTimestamp();
        $timestamp->shouldReceive('getCurrentTimestamp')
            ->andReturn($currentTimestamp)
            ->times(9); //3 times for monitor internal stuff and up to 3 times for each attached client
        $monitor->setTimestamp($timestamp);

        $firstClient = $this->getNewMockHeartbeatClientWithPulse();
        $firstClient->shouldReceive('hasPulse')
            ->andReturn(false)
            ->times(6);
        $firstClient->shouldReceive('knock')
            ->times(3);

        $secondClient = $this->getNewMockHeartbeatClientWithPulse();
        $secondClient->shouldReceive('hasPulse')
            ->andReturn(false)
            ->times(6);
        $secondClient->shouldReceive('knock')
            ->times(3);

        $monitor->attach($firstClient);
        $monitor->attach($secondClient);

        $monitor->listen();
        $monitor->listen();
        $monitor->listen();
    }

    /**
     * If a client implements the pulse aware interface and has a pulse, we can
     *  use the pulse to use knock in wished intervals
     *
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function testListenWithTwoClientsOfPulseAwareInterfaceAndPulseAndThreeSleeps()
    {
        $currentTimestamp = time();
        $currentTimestampPlusOneSecond = $currentTimestamp + 1;
        $currentTimestampPlusThreeSeconds = $currentTimestamp + 3;
        $currentTimestampPlusFourSeconds = $currentTimestamp + 4;
        $monitor = $this->getNewMonitor();
        $timestamp = $this->getNewMockTimestamp();
        $timestamp->shouldReceive('getCurrentTimestamp')
            ->andReturnValues(
                array(
                    //return value for first listen call
                    $currentTimestamp, $currentTimestamp,
                    //return value for second listen call
                    $currentTimestampPlusOneSecond, $currentTimestampPlusOneSecond,
                    //return value for third listen call
                    $currentTimestampPlusFourSeconds, $currentTimestampPlusFourSeconds
                )
            )
            ->times(9); //3 times for monitor internal stuff and up to 3 times for each attached client
        $monitor->setTimestamp($timestamp);

        $threeSecondPulse = $this->getNewMockPulse();
        $threeSecondPulse->shouldReceive('getNextPulseTimestamp')
            ->andReturnValues(array($currentTimestamp, $currentTimestampPlusThreeSeconds, $currentTimestampPlusThreeSeconds))
            ->times(3);
        $threeSecondPulse->shouldReceive('updateLastPulsedTimestamp')
            ->times(2);
        $threeSecondPulseClient = $this->getNewMockHeartbeatClientWithPulse();
        $threeSecondPulseClient->shouldReceive('hasPulse')
            ->andReturn(true)
            ->times(5);
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn($threeSecondPulse)
            ->times(5);
        $threeSecondPulseClient->shouldReceive('knock')
            ->times(2);

        $zeroSecondPulse = $this->getNewMockPulse();
        $zeroSecondPulse->shouldReceive('getNextPulseTimestamp')
            ->andReturnValues(array($currentTimestamp, $currentTimestampPlusOneSecond, $currentTimestampPlusThreeSeconds))
            ->times(3);
        $zeroSecondPulse->shouldReceive('updateLastPulsedTimestamp')
            ->times(3);
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