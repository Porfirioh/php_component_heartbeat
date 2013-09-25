<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-25
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatMonitorFactoryTest
 *
 * @package Test\Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-25
 */
class HeartbeatMonitorFactoryTest extends TestCase
{
    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-25
     */
    public function testCreate()
    {
        $factory = $this->getNewMonitorFactory();
        $monitor = $factory->create();

        $this->assertInstanceOf('\Net\Bazzline\Component\Heartbeat\HeartbeatMonitor', $monitor);
        $this->assertTrue($monitor->hasTimestamp());
    }
}