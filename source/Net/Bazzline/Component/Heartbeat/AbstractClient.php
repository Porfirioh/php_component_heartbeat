<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class AbstractClient.
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
abstract class AbstractClient implements ClientInterface, RuntimeInformationInterface
{
    /**
     * @var integer
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-14
     */
    protected $startTime;

    /**
     * @author stev leibelt <artodeto@bazzline.net>
     * @since 2013-07-14
     */
    public function __construct()
    {
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
}