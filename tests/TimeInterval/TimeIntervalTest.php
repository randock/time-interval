<?php

declare(strict_types=1);

namespace Tests\Randock\TimeInterval\ReadableTimeInterval;

use PHPUnit\Framework\TestCase;
use Randock\TimeInterval\TimeInterval\TimeInterval;

class TimeIntervalTest extends TestCase
{
    public function testNewFromMilliseconds()
    {
        $milliseconds = 300000;
        $interval = TimeInterval::newFromMilliseconds($milliseconds);
        $this->assertSame($interval->getInMilliseconds(), $milliseconds);
        $this->assertSame($interval->getInSeconds(), $milliseconds / 1000);
        $this->assertSame($interval->getInDays(), 0);
    }

    public function testNewFromSeconds()
    {
        $seconds = 300;
        $interval = TimeInterval::newFromSeconds($seconds);
        $this->assertSame($interval->getInSeconds(), $seconds);
        $this->assertSame($interval->getInMilliseconds(), $seconds * 1000);
        $this->assertSame($interval->getInDays(), 0);
    }

    public function testNewFromMinutes()
    {
        $minutes = 5;
        $interval = TimeInterval::newFromMinutes($minutes);
        $this->assertSame($interval->getInMilliseconds(), 300000);
        $this->assertSame($interval->getInSeconds(), 300);
        $this->assertSame($interval->getInDays(), 0);
    }

    public function testNewFromHours()
    {
        $hours = 1;
        $interval = TimeInterval::newFromHours($hours);
        $this->assertSame($interval->getInMilliseconds(), 3600000);
        $this->assertSame($interval->getInSeconds(), 3600);
        $this->assertSame($interval->getInDays(), 0);
    }

    public function testNewFromDays()
    {
        $days = 1;
        $interval = TimeInterval::newFromDays($days);
        $this->assertSame($interval->getInMilliseconds(), 86400000);
        $this->assertSame($interval->getInSeconds(), 86400);
        $this->assertSame($interval->getInDays(), $days);
    }
}
