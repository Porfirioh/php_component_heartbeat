<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-10-01
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class ClientHistoryEventInterface
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-10-01
 */
interface ClientHistoryEntryInterface
{
    /**
     * @return string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getIdentifier();

    /**
     * @return string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getName();

    /**
     * @return string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getStatus();

    /**
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getTimestamp();

    /**
     * @param string $identifier
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function setIdentifier($identifier);

    /**
     * @param string $name
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function setName($name);

    /**
     * @param null|RuntimeException $exception
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function setStatusByException(RuntimeException $exception = null);

    /**
     * @param null|string $timestamp
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function setTimestamp($timestamp = null);
}