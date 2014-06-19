<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-22
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class Pulse
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-22
 */
class Pulse implements PulseInterface
{
    /**
     * @var int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    protected $interval;

    /**
     * @var int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    protected $lastPulseTimestamp;

    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function __construct()
    {
        $this->setInterval(0);
    }

    /**
     * @return int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param int $interval - in seconds
     * @return $this
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function setInterval($interval)
    {
        $this->interval = (int) $interval;

        return $this;
    }

    /**
     * @return int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function getLastPulsedTimestamp()
    {
        return $this->lastPulseTimestamp;
    }

    /**
     * @return $this
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function updateLastPulsedTimestamp()
    {
        $this->lastPulseTimestamp = time();

        return $this;
    }

    /**
     * @return int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-23
     */
    public function getNextPulseTimestamp()
    {
        return ($this->lastPulseTimestamp + $this->interval);
    }
}