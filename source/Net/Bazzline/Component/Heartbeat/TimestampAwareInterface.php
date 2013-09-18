<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class TimestampAwareInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-18
 */
interface TimestampAwareInterface 
{
    /**
     * @return null|TimestampInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function getTimestamp();

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function hasTimestamp();

    /**
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function removeTimestamp();

    /**
     * @param TimestampInterface $timestamp
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function setTimestamp(TimestampInterface $timestamp);
}