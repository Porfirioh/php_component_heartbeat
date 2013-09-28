<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-28
 */

namespace Test\Net\Bazzline\Component\Heartbeat;

/**
 * Class PulseTest
 *
 * @package Test\Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-28
 */
class PulseTest extends TestCase
{
    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function testConstruct()
    {
        $pulse = $this->getNewPulse();

        $lastPulsedTimestamp = $pulse->getLastPulsedTimestamp();
        $expectedInitialInterval = 0;
        $expectedNextPulsedTimestamp = $expectedInitialInterval + $lastPulsedTimestamp;

        $this->assertEquals($expectedInitialInterval, $pulse->getInterval());
        $this->assertEquals($expectedNextPulsedTimestamp, $pulse->getNextPulseTimestamp());
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function testSetAndGetInterval()
    {
        $pulse = $this->getNewPulse();
        $interval = 3;

        $this->assertEquals($pulse, $pulse->setInterval($interval));
        $this->assertEquals($interval, $pulse->getInterval());
    }
}