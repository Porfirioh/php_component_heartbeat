<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace Example\JSONBasedImplementation;

use Net\Bazzline\Component\Heartbeat\CriticalRuntimeException;
use Net\Bazzline\Component\Heartbeat\HeartbeatAbstract;
use Net\Bazzline\Component\Heartbeat\RuntimeException;
use Net\Bazzline\Component\Heartbeat\WarningRuntimeException;

/**
 * Class Heartbeat
 *
 * @package Example\JSONBasedImplementation
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17
 */
class Heartbeat extends HeartbeatAbstract
{
    /**
     * This method returns the current timestamp as heartbeat.
     *
     * @return integer - timestamp of last beat
     * @throws RuntimeException|CriticalRuntimeException|WarningRuntimeException
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function knock()
    {
        // TODO: Implement knock() method.
    }

    /**
     * This method updates the current heartbeat.
     *
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    public function beat()
    {
        // TODO: Implement beat() method.
    }

    /**
     * Handles case if knock throws an error
     *
     * @param RuntimeException|CriticalRuntimeException|WarningRuntimeException $exception
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-16
     */
    public function handleHeartAttack(RuntimeException $exception)
    {
        // TODO: Implement handleHeartAttack() method.
    }
}