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
    protected function getNewMonitor()
    {
        $factory = new HeartbeatMonitorFactory();
        $monitor = $factory->create();

        return $monitor;
    }}