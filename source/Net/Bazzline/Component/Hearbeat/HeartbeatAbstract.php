<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatAbstract.
 * You have to implement the knock method. The implementation depends on your client.
 * If you need to call a url or what ever.
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
abstract class HeartbeatAbstract implements HeartbeatInterface, PulseableInterface, RuntimeInformationInterface
{
    /**
     * @var integer
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    protected $pulse;

    /**
     * @var integer
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    protected $startTime;

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function __construct()
    {
        $this->pulse = 5;
        $this->startTime = time();
    }

    /**
     * {@inheritDoc}
     */
    public function getUptime()
    {
        return (time() - $this->startTime);
    }

    /**
     * {@inheritDoc}
     */
    public function getMemoryUsage()
    {
        return memory_get_usage(true);
    }

    /**
     * {@inheritDoc}
     */
    public function getPulse()
    {
        return $this->pulse;
    }

    /**
     * {@inheritDoc}
     */
    public function setPulse($seconds)
    {
        $this->pulse = (int) $seconds;

        return $this;
    }
}