<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-29
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

/**
 * Class AbstractClientTest
 *
 * @package Test\Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-29
 */
class AbstractClientTest extends TestCase
{
    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-29
     */
    public function testGetUptime()
    {
        $client = $this->getNewAbstractClient();
        $expectedMaximalUptime = 1; //the test should not run longer than a second
        $expectedMinimalUptime = 0;

        $this->assertGreaterThanOrEqual($expectedMinimalUptime, $client->getUptime());
        $this->assertLessThanOrEqual($expectedMaximalUptime, $client->getUptime());
    }

    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-29
     */
    public function testGetMemoryUsage()
    {
        $client = $this->getNewAbstractClient();
        $expectedMinimalMemoryUsage = memory_get_usage(true);

        $this->assertGreaterThanOrEqual($expectedMinimalMemoryUsage, $client->getMemoryUsage());
    }
}