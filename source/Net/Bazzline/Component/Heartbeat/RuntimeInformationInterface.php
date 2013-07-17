<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-15
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class RuntimeInformationInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-15
 */
interface RuntimeInformationInterface
{
    /**
     * Returns uptime of the current client in seconds.
     *
     * @return integer - runtime in seconds
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function getUptime();

    /**
     * Returns memory usage of the current client.
     *
     * @return integer - memory usage in bytes
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function getMemoryUsage();
}