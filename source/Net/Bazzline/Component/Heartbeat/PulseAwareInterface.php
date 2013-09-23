<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14 
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class PulseAwareInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
interface PulseAwareInterface
{
    /**
     * @return null|PulseInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function getPulse();

    /**
     * @param PulseInterface $pulse
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function setPulse(PulseInterface $pulse);

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    public function hasPulse();
}