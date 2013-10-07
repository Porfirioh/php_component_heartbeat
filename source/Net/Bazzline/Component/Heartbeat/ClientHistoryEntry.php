<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-10-01
 */

namespace Net\Bazzline\Component\Heartbeat;

/**
 * Class ClientHistoryEntry
 *
 * @package Net\Bazzline\Component\Heartbeat
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-10-01
 */
class ClientHistoryEntry implements ClientHistoryEntryInterface
{
    /**
     * @var string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    private $identifier;

    /**
     * @var string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    private $name;

    /**
     * @var string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    private $status;

    /**
     * @var int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    private $timestamp;

    /**
     * @return string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $identifier
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param null|RuntimeException $exception
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function setStatusByException(RuntimeException $exception = null)
    {
        if (is_null($exception)) {
            $this->status = 'ok';
        } else {
            $this->status = ($exception instanceof CriticalRuntimeException)
                ? 'critical'
                : 'warning';
        }

        return $this;
    }

    /**
     * @param null|string $timestamp
     * @return $this
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-10-01
     */
    public function setTimestamp($timestamp = null)
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}