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
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-19
     */
    public function getInitialTimestamp();

    /**
     * @param null $time
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-19
     */
    public function setInitialTimestamp($time = null);

    /**
     * @param null $time
     * @return mixed
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-18
     */
    public function getTimeDifference($time = null);
}