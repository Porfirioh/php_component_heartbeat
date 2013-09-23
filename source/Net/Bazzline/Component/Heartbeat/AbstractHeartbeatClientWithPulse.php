<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatAbstract.
 * This class is a heartbeat with benefits. You can use the runtime information
 *  to write statistic data in your beat method implementation.
 *
 * You have to implement the knock method. The implementation depends on your client.
 *  If you need to call a url or what ever.
 * You have to implement the beat method.
 * You have to implement the handleHeartAttack method.
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
abstract class AbstractHeartbeatClientWithPulse implements HeartbeatClientInterface, PulseAwareInterface, RuntimeInformationInterface
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
     * {@inheritdoc}
     */
    public function getUptime()
    {
        return (time() - $this->startTime);
    }

    /**
     * {@inheritdoc}
     */
    public function getMemoryUsage()
    {
        return memory_get_usage(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getPulse()
    {
        return $this->pulse;
    }

    /**
     * {@inheritdoc}
     */
    public function setPulse(PulseInterface $pulse)
    {
        $this->pulse = $pulse;

        return $this;
    }

    /**
     * @return bool
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-22
     */
    public function hasPulse()
    {
        return (!is_null($this->pulse));
    }
}