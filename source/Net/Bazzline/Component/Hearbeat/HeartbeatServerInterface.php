<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class HeartbeatServerInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
interface HeartbeatServerInterface
{
    /**
     * Adds a client to the observer
     *
     * @param HeartbeatClientInterface $client
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function addClient(HeartbeatClientInterface $client);

    /**
     * Listen to each added client. Implement handling of error here.
     *
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function listen();
}