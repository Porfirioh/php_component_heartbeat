<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatClientHistory
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-09-29
 */
class RuntimeHeartbeatClientHistory implements HeartbeatClientHistoryInterface
{
    /**
     * @var array
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    protected $entries;

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function __construct()
    {
        $this->entries = array();
    }

    /**
     * @param HeartbeatClientInterface $heartbeatClient
     * @param null|RuntimeException $exception
     * @return $this
     * @throws InvalidArgumentException
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function addEntry(HeartbeatClientInterface $heartbeatClient, $exception = null)
    {
        if (!is_null($exception)
            && !($exception instanceof RuntimeException)) {
            throw new InvalidArgumentException(
                'Invalid exception given'
            );
        }

        $entry = array(
            'identifier' => spl_object_hash($heartbeatClient),
            'name' => get_class($heartbeatClient),
            'status' => ((is_null($exception))
                ? 'ok'
                : (($exception instanceof CriticalRuntimeException) ? 'critical' : 'warning')),
            'timestamp' => time()
        );
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * @return array
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-09-29
     */
    public function getEntries()
    {
        return $this->entries;
    }
}