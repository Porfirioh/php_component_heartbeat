<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-11
 */
namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatClientInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-11
 */
interface HeartbeatClientInterface
{
    /**
     * @return integer - current timestamp, if not, the heart is broken
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function knock();

    /**
     * @return integer - runtime in seconds
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function getUptime();

    /**
     * @return integer - memory usage in bytes
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function getMemoryUsage();
}
