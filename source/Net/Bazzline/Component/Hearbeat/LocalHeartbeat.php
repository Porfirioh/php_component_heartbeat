<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-15
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class LocalHeartbeat
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-15
 */
class LocalHeartbeat extends HeartbeatAbstract
{
    /**
     * {@inheritDoc}
     */
    public function knock()
    {
        return time();
    }
}