<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class AbstractClientWithPulse.
 * This class is a heartbeat with benefits. You can use the runtime information
 *  to write statistic data in your beat method implementation.
 *
 * You have to implement the knock method. The implementation depends on your client.
 *  If you need to call a url or what ever.
 * You have to implement the beat method.
 * You have to implement the handleHeartAttack method.
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-14
 */
abstract class AbstractClientWithPulse extends AbstractClient implements PulseAwareInterface
{
    /**
     * @var integer
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-14
     */
    protected $pulse;

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
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-09-22
     */
    public function hasPulse()
    {
        return (!is_null($this->pulse));
    }
}