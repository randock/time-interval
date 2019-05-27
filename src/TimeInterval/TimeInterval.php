<?php

declare(strict_types=1);

namespace Randock\TimeInterval\TimeInterval;

class TimeInterval
{
    /**
     * @var int
     */
    private $milliseconds;

    /**
     * TimeInterval constructor.
     *
     * @param int $milliseconds
     */
    public function __construct(int $milliseconds)
    {
        $this->milliseconds = $milliseconds;
    }

    /**
     * @param int $milliseconds
     *
     * @return TimeInterval
     */
    public static function newFromMilliseconds(int $milliseconds)
    {
        return new self($milliseconds);
    }

    /**
     * @param int $seconds
     *
     * @return TimeInterval
     */
    public static function newFromSeconds(int $seconds)
    {
        $milliseconds = $seconds * 1000;

        return new self($milliseconds);
    }

    /**
     * @param int $minutes
     *
     * @return TimeInterval
     */
    public static function newFromMinutes(int $minutes)
    {
        $milliseconds = $minutes * 60 * 1000;

        return new self($milliseconds);
    }

    /**
     * @param int $hours
     *
     * @return TimeInterval
     */
    public static function newFromHours(int $hours)
    {
        $milliseconds = $hours * 60 * 60 * 1000;

        return new self($milliseconds);
    }

    /**
     * @param int $days
     *
     * @return TimeInterval
     */
    public static function newFromDays(int $days)
    {
        $milliseconds = $days * 24 * 60 * 60 * 1000;

        return new self($milliseconds);
    }

    public function getInMilliseconds()
    {
        return $this->milliseconds;
    }

    public function getInSeconds()
    {
        return (int) \ceil($this->milliseconds / 1000);
    }

    /**
     * @return int
     */
    public function getInDays(): int
    {
        $days = $this->getInSeconds() / 86400;
        if( $days < 1 ){
            return 0;
        }

        return (int) \ceil(
            $this->getInSeconds() / 86400
        );
    }

    /**
     * @throws \Exception
     *
     * @return \DateInterval
     */
    public function getDateInterval(): \DateInterval
    {
        return new \DateInterval(
            \sprintf(
                'PT%dS',
                $this->getInSeconds()
            )
        );
    }

    /**
     * @param int $milliseconds
     *
     * @return $this
     */
    public function setInMilliseconds(int $milliseconds): self
    {
        $this->milliseconds = $milliseconds;

        return $this;
    }

    /**
     * @param int $seconds
     *
     * @return TimeInterval
     */
    public function setInSeconds(int $seconds): self
    {
        $this->setInMilliseconds($seconds * 1000);

        return $this;
    }

    /**
     * Defaults to PHP_INT_MAX if the result of the increment results in something bigger than that.
     *
     * @param int $numTimes
     *
     * @return TimeInterval
     */
    public function incrementXTimes(int $numTimes): self
    {
        $milliseconds = $this->milliseconds * $numTimes;
        if ($milliseconds > PHP_INT_MAX) {
            $milliseconds = PHP_INT_MAX;
        }
        $this->setInMilliseconds($milliseconds);

        return $this;
    }

    public function isGreaterThan(self $timeInterval)
    {
        return $this->milliseconds > $timeInterval->milliseconds;
    }

    public function isLessThan(self $timeInterval)
    {
        return $this->milliseconds < $timeInterval->milliseconds;
    }

    public function equals(self $timeInterval)
    {
        return $this->milliseconds === $timeInterval->milliseconds;
    }
}
