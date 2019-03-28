<?php

declare(strict_types=1);

namespace Randock\TimeInterval\ReadableTimeInterval\Exception;

class LocaleNotSupportedException extends \Exception
{
    /**
     * LocaleNotSupportedException constructor.
     *
     * @param string $locale
     */
    public function __construct(string $locale)
    {
        parent::__construct(sprintf('Locale not supported: %s', $locale));
    }
}
