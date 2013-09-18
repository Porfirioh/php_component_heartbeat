<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class Timestamp
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */
class Timestamp implements TimestampInterface
{
    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    protected $initialTimestamp;

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function __construct()
    {
        $this->setInitialTimestamp();
    }

    /**
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function getCurrentTimestamp()
    {
        return time();
    }

    /**
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-19
     */
    public function getInitialTimestamp()
    {
        return $this->initialTimestamp;
    }

    /**
     * Returns difference between provided timestamp and initial timestamp.
     * If null is provided, the current timestamp is used.
     *
     * @param null|int $time
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function getTimestampDifference($time = null)
    {
        $time = (is_null($time)) ? $this->getCurrentTimestamp() : $time;

        return ($time - $this->initialTimestamp);
    }

    /**
     * Adds manipulation for initial timestamp.
     * If null is provided, the current timestamp is used.
     *
     * @param null|int $time
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-19
     */
    public function setInitialTimestamp($time = null)
    {
        $time = (is_null($time)) ? $this->getCurrentTimestamp() : $time;

        $this->initialTimestamp = $time;

        return $this;
    }
}