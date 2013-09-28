<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

/**
 * Class AbstractHeartbeatClientWithPulseTest
 *
 * @package Test\Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */
class AbstractHeartbeatClientWithPulseTest extends TestCase
{
    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function testConstruct()
    {
        $client = $this->getNewAbstractHeartbeatClientWithPulse();
        $expectedInitialPulse = null;

        $this->assertFalse($client->hasPulse());
        $this->assertEquals($expectedInitialPulse, $client->getPulse());
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function testHasGetSetPulse()
    {
        $client = $this->getNewAbstractHeartbeatClientWithPulse();
        $pulse = $this->getNewMockPulse();

        $this->assertEquals($client, $client->setPulse($pulse));
        $this->assertEquals($pulse, $client->getPulse());
        $this->assertTrue($client->hasPulse());
    }
}