<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */

namespace Example\JSONBasedImplementation;

use Net\Bazzline\Component\Heartbeat\HeartbeatAwareInterface;
use Net\Bazzline\Component\Heartbeat\HeartbeatInterface;

/**
 * Class Process
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */
class Process implements HeartbeatAwareInterface
{
    /**
     * @var Heartbeat
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-18
     */
    protected $heartbeat;

    /**
     * {@inheritDoc}
     */
    public function getHeartbeat()
    {
        return $this->heartbeat;
    }

    /**
     * {@inheritDoc}
     */
    public function setHeartbeat(HeartbeatInterface $heartbeat)
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