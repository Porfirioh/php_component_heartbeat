<?php

namespace Net\Bazzline\Component\Heartbeat

interface HeartbeatClientInterface
{
    /**
     * @return integer - current timestamp, if not, the heart is broken
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function knock();

    /**
     * @return integer - tells how often (in seconds) the knock should be called
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function getPulse();

    /**
     * @param integer - sets how often (in seconds) the knock should be called)
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function setPulse();

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
