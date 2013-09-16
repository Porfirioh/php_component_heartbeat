<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */

namespace Example\JSONBasedImplementation;

use Net\Bazzline\Component\Heartbeat\HeartbeatClientAwareInterface;
use Net\Bazzline\Component\Heartbeat\ClientInterface;

/**
 * Class Process
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */
class Process implements HeartbeatClientAwareInterface
{
    /**
     * @var Heartbeat
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-18
     */
    protected $heartbeat;

    /**
     * {@inheritdoc}
     */
    public function getHeartbeat()
    {
        return $this->heartbeat;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeartbeat(ClientInterface $heartbeat)
    {
        $this->heartbeat = $heartbeat;

        return $this;
    }

    /**
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-18
     */
    public function execute()
    {
        //that is the area where you should implement your logic by iterating
        // over a collection
        //--logic start
        $this->heartbeat->beat();
        //--logic end
    }
}