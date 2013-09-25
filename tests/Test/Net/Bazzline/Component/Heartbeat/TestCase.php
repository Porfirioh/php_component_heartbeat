<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-16
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

use Net\Bazzline\Component\Heartbeat\HeartbeatMonitor;
use Net\Bazzline\Component\Heartbeat\HeartbeatMonitorFactory;
use Mockery;
use PHPUnit_Framework_TestCase;

/**
 * Class TestCase
 *
 * @package Test\Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-16
 */
class TestCase extends PHPUnit_Framework_TestCase
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
     * @return \Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\HeartbeatClientInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected function getNewMockHeartbeatClient()
    {
        $client = Mockery::mock('Net\Bazzline\Component\Heartbeat\AbstractHeartbeatClient');
        $client->shouldReceive('hasPulse')
            ->never()
            ->byDefault();
        $client->shouldReceive('getPulse')
            ->never()
            ->byDefault();

        return $client;
    }

    /**
     * @return \Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\AbstractHeartbeatClientWithPulse
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected function getNewMockHeartbeatClientWithPulse()
    {
        $client = Mockery::mock('Net\Bazzline\Component\Heartbeat\AbstractHeartbeatClientWithPulse');
        $client->shouldReceive('hasPulse')
            ->never()
            ->byDefault();
        $client->shouldReceive('getPulse')
            ->never()
            ->byDefault();

        return $client;
    }

    /**
     * @return \Mockery\MockInterface|\Net\Bazzline\Component\Utility\TimestampInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    protected function getNewMockTimestamp()
    {
        $timestamp = Mockery::mock('Net\Bazzline\Component\Utility\Timestamp');

        return $timestamp;
    }

    /**
     * @return Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\Pulse
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-24
     */
    protected function getNewMockPulse()
    {
        $pulse = Mockery::mock('Net\Bazzline\Component\Heartbeat\Pulse');

        return $pulse;
    }

    /**
     * @return HeartbeatMonitor
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected function getNewMonitor()
    {
        $factory = $this->getNewMonitorFactory();
        $monitor = $factory->create();

        return $monitor;
    }

    /**
     * @return HeartbeatMonitorFactory
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-25
     */
    protected function getNewMonitorFactory()
    {
        return new HeartbeatMonitorFactory();
    }
}