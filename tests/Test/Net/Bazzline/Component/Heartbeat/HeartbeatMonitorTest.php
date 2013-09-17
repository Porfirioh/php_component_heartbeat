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
        $client = $this->getNewHeartbeatClient();

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
        $client = $this->getNewHeartbeatClient();
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
        $client = $this->getNewHeartbeatClient();

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
        $client = $this->getNewHeartbeatClient();
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

        $threeSecondPulseClient = $this->getNewHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(3)
            ->once();
        $threeSecondPulseClient->shouldReceive('knock')
            ->twice();

        $zeroSecondPulseClient = $this->getNewHeartbeatClient();
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

        $threeSecondPulseClient = $this->getNewHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(3)
            ->once();
        $threeSecondPulseClient->shouldReceive('knock')
            ->once();

        $zeroSecondPulseClient = $this->getNewHeartbeatClient();
        $zeroSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(0)
            ->once();
        $zeroSecondPulseClient->shouldReceive('knock')
            ->twice();

        $monitor->attach($threeSecondPulseClient);
        $monitor->attach($zeroSecondPulseClient);

        $monitor->listen();
        //since the internal heartbeat monitor measures with a accuracy of one
        // second, we have to wait at least one second
        sleep(1);
        $monitor->listen();
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function testListenWithTwoClientsAndTwoSleeps()
    {
        $monitor = $this->getNewMonitor();

        $threeSecondPulseClient = $this->getNewHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(3)
            ->once();
        $threeSecondPulseClient->shouldReceive('knock')
            ->twice();

        $zeroSecondPulseClient = $this->getNewHeartbeatClient();
        $zeroSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(0)
            ->once();
        $zeroSecondPulseClient->shouldReceive('knock')
            ->times(3);

        $monitor->attach($threeSecondPulseClient);
        $monitor->attach($zeroSecondPulseClient);

        $monitor->listen();
        //we have to wait at least three second to trigger $clientOne two times
        sleep(2);
        $monitor->listen();
        sleep(1);
        $monitor->listen();
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-17
     */
    public function testListenWithFourClientsAndThreeSleeps()
    {
        $monitor = $this->getNewMonitor();

echo __METHOD__ . PHP_EOL;
        $threeSecondPulseClient = $this->getNewHeartbeatClient();
        $threeSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(3)
            ->once();
        $threeSecondPulseClient->shouldReceive('knock')
            ->times(2);

        $oneSecondPulseClient = $this->getNewHeartbeatClient();
        $oneSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(1)
            ->once();
        $oneSecondPulseClient->shouldReceive('knock')
            ->times(3);

        $sixSecondPulseClient = $this->getNewHeartbeatClient();
        $sixSecondPulseClient->shouldReceive('getPulse')
            ->andReturn(6)
            ->once();
        $sixSecondPulseClient->shouldReceive('knock')
            ->twice();

        $twoSecondPulseClient = $this->getNewHeartbeatClient();
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
        //we have to wait at least six seconds to trigger $clientThree two times
        sleep(4);
        $monitor->listen();
        sleep(2);
        $monitor->listen();
    }

    /**
     * @return \Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\HeartbeatClientInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    private function getNewHeartbeatClient()
    {
        $client = Mockery::mock('Net\Bazzline\Component\Heartbeat\AbstractHeartbeatClient');
        $client->shouldReceive('getPulse')
            ->andReturn(15)
            ->once()
            ->byDefault();

        return $client;
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