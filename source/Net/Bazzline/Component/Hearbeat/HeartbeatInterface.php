<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-11
 */
namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-11
 */
interface HeartbeatInterface
{
    /**
     * This method returns the current timestamp as heartbeat.
     * The knock method in each client takes care of the following situations:
     *  - How to handle timeout?
     *  - How to handle return value of null?
     *  - How to handle valid return value?
     *
     * @return integer - current timestamp, if not, the heart is broken
     * @throws \RuntimeException
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function knock();
}
