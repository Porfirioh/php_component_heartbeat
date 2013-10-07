<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-16
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

use Net\Bazzline\Component\Heartbeat\Monitor;
use Net\Bazzline\Component\Heartbeat\MonitorFactory;
use Mockery;
use Net\Bazzline\Component\Heartbeat\Pulse;
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
     * @return \Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\ClientInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected function getNewMockClient()
    {
        $client = Mockery::mock('Net\Bazzline\Component\Heartbeat\AbstractClient');
        $client->shouldReceive('hasPulse')
            ->never()
            ->byDefault();
        $client->shouldReceive('getPulse')
            ->never()
            ->byDefault();

        return $client;
    }

    /**
     * @return \Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\AbstractClientWithPulse
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    protected function getNewMockClientWithPulse()
    {
        $client = Mockery::mock('Net\Bazzline\Component\Heartbeat\AbstractClientWithPulse');
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
     * @return Monitor
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
     * @return MonitorFactory
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-25
     */
    protected function getNewMonitorFactory()
    {
        return new MonitorFactory();
    }

    /**
     * @return Pulse
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    protected function getNewPulse()
    {
        return new Pulse();
    }

    /**
     * @return Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\AbstractClient
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    protected function getNewAbstractClient()
    {
        return Mockery::mock('Net\Bazzline\Component\Heartbeat\AbstractClient[knock,beat,handleException]');
    }

    /**
     * @return Mockery\MockInterface|\Net\Bazzline\Component\Heartbeat\AbstractClientWithPulse
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    protected function getNewAbstractClientWithPulse()
    {
        return Mockery::mock('Net\Bazzline\Component\Heartbeat\AbstractClientWithPulse[knock,beat,handleException]');
    }
}