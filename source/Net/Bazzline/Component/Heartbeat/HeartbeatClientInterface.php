<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-11
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class ClientInterface
 * The knock method in each client takes care of the following situations:
 *  - How to handle timeout?
 *  - How to handle return value of null?
 *  - How to handle valid return value?
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-11
 */
interface HeartbeatClientInterface
{
    /**
     * This method returns the current timestamp as heartbeat.
     *
     * @return integer - timestamp of last beat
     * @throws RuntimeCriticalException|CriticalRuntimeException|RuntimeWarningException
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-11
     */
    public function knock();

    /**
     * This method updates the current heartbeat.
     *
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-15
     */
    public function beat();

    /**
     * Handles case if knock throws an error
     *
     * @param RuntimeCriticalException|CriticalRuntimeException|RuntimeWarningException $exception
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-07-16
     */
    public function handleException(RuntimeCriticalException $exception);
}
