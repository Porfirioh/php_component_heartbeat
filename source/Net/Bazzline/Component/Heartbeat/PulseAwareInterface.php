<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14 
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class PulseableInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
interface PulseAwareInterface
{
    /**
     * @return null|int - tells how often (in seconds) the knock should be called
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function getPulse();

    /**
     * @param int $seconds - sets how often (in seconds) the knock should be called)
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function setPulse($seconds);

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    public function hasPulse();
}