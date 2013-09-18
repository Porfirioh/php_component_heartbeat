<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class TimestampInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */
interface TimestampInterface 
{
    /**
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function getCurrentTimestamp();

    /**
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-19
     */
    public function getInitialTimestamp();

    /**
     * Returns difference between provided timestamp and initial timestamp.
     * If null is provided, the current timestamp is used.
     *
     * @param null|int $time
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function getTimestampDifference($time = null);

    /**
     * Adds manipulation for initial timestamp.
     * If null is provided, the current timestamp is used.
     *
     * @param null|int $time
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-19
     */
    public function setInitialTimestamp($time = null);
}