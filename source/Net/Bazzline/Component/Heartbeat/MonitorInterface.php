<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class MonitorInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-14
 */
interface MonitorInterface
{
    /**
     * Adds a client to the observer
     *
     * @param ClientInterface $client
     * @throws InvalidArgumentException - if you want to attach a attached
     *  heartbeat
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function attach(ClientInterface $client);

    /**
     * Removes an attached client to the observer
     *
     * @param ClientInterface $client
     * @return $this
     * @throws InvalidArgumentException - if you want to detach a non attached
     *  heartbeat
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function detach(ClientInterface $client);

    /**
     * Returns all attached clients
     *
     * @return array|ClientInterface[]
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function getAll();

    /**
     * Removes all attached clients
     *
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-17
     */
    public function detachAll();

    /**
     * Listen to each added client. Implement handling of error here.
     *
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-14
     */
    public function listen();
}