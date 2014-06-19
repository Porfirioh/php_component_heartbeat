<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-29
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

/**
 * Class AbstractClientWithPulseTest
 *
 * @package Test\Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-29
 */
class AbstractClientWithPulseTest extends TestCase
{
    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-29
     */
    public function testConstruct()
    {
        $client = $this->getNewAbstractClientWithPulse();
        $expectedInitialPulse = null;

        $this->assertFalse($client->hasPulse());
        $this->assertEquals($expectedInitialPulse, $client->getPulse());
    }

    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-29
     */
    public function testHasGetSetPulse()
    {
        $client = $this->getNewAbstractClientWithPulse();
        $pulse = $this->getNewMockPulse();

        $this->assertEquals($client, $client->setPulse($pulse));
        $this->assertEquals($pulse, $client->getPulse());
        $this->assertTrue($client->hasPulse());
    }
}