<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-22
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class Pulse
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-22
 */
class Pulse implements PulseInterface
{
    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    protected $interval;

    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    protected $lastPulseTimestamp;

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    public function __construct()
    {
        $this->interval = 0;
    }

    /**
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    public function getInterval()
    {
        // TODO: Implement getInterval() method.
    }

    /**
     * @param int $interval - in seconds
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    public function setInterval($interval)
    {
        // TODO: Implement setInterval() method.
    }

    /**
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    public function getLastPulsedTimestamp()
    {
        // TODO: Implement getLastPulsedTimestamp() method.
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    public function setLastPulsedTimestamp()
    {
        // TODO: Implement setLastPulsedTimestamp() method.
    }
}