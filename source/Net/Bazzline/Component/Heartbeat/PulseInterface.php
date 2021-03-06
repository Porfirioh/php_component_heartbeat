<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-22
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class PulseInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-09-22
 */
interface PulseInterface 
{
    /**
     * @return int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function getInterval();

    /**
     * @param int $interval - in seconds
     * @return $this
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function setInterval($interval);

    /**
     * @return int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function getLastPulsedTimestamp();

    /**
     * @return $this
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function updateLastPulsedTimestamp();

    /**
     * @return int
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-23
     */
    public function getNextPulseTimestamp();
}