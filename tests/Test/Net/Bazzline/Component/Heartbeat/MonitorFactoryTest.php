<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-25
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

/**
 * Class MonitorFactoryTest
 *
 * @package Test\Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-25
 */
class MonitorFactoryTest extends TestCase
{
    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-25
     */
    public function testCreate()
    {
        $factory = $this->getNewMonitorFactory();
        $monitor = $factory->create();

        $this->assertInstanceOf('\Net\Bazzline\Component\Heartbeat\Monitor', $monitor);
        $this->assertTrue($monitor->hasTimestamp());
    }
}