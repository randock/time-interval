<?php

declare(strict_types=1);

namespace Randock\TimeInterval\ReadableTimeInterval;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Randock\TimeInterval\ReadableTimeInterval\Exception\LocaleNotSupportedException;

class ReadableTimeIntervalGenerator
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * ReadableTimeIntervalGenerator constructor.
     *
     * @param string $locale
     *
     * @throws LocaleNotSupportedException
     */
    public function __construct(string $locale = 'en_EN')
    {
        $this->translator = new Translator(null);
        $this->translator->addLoader('yaml', new YamlFileLoader());
        $this->setLocale($locale);
    }

    /**
     * @param int $secondsToTransform
     *
     * @return string
     */
    public function getReadableTimeText(int $secondsToTransform)
    {
        $seconds = $secondsToTransform % 60;
        $remainder = \intdiv($secondsToTransform, 60);
        $minutes = $remainder % 60;
        $remainder = \intdiv($remainder, 60);
        $hours = $remainder % 24;
        $days = \intdiv($remainder, 24);

        $textComponents = [];

        if (0 !== $days) {
            $textComponents[] = $this->translator->transChoice('timeInterval.days', $days, ['%count%' => $days]);
        }

        if (0 !== $hours) {
            $textComponents[] = $this->translator->transChoice('timeInterval.hours', $hours, ['%count%' => $hours]);
        }

        if (0 !== $minutes) {
            $textComponents[] = $this->translator->transChoice('timeInterval.minutes', $minutes, ['%count%' => $minutes]);
        }

        if (0 !== $seconds) {
            $textComponents[] = $this->translator->transChoice('timeInterval.seconds', $seconds, ['%count%' => $seconds]);
        }

        $output = '';

        $i = 0;
        if (1 === \count($textComponents)) {
            return $textComponents[0];
        }

        $lastComponent = \array_pop($textComponents);
        $output = \implode(', ', $textComponents);
        $output = \sprintf(
            '%s %s %s',
            $output,
            $this->translator->trans('timeInterval.and'),
            $lastComponent
        );

        return $output;
    }

    /**
     * @param string $locale
     *
     * @throws LocaleNotSupportedException
     *
     * @return ReadableTimeIntervalGenerator
     */
    public function setLocale(string $locale): self
    {
        $translationFile = \sprintf('/app/translations/messages.%s.yaml', $locale);
        $resourceAdded = $this->addYamlResource($translationFile, $locale);
        if (!$resourceAdded) {
            $translationFile = \sprintf('/app/translations/messages.%s.yaml', \Locale::parseLocale($locale)['language']);
            $resourceAdded = $this->addYamlResource($translationFile, $locale);
            if (!$resourceAdded) {
                throw new LocaleNotSupportedException($locale);
            }
        }
        $this->translator->setLocale($locale);

        return $this;
    }

    /**
     * @param string $filePath
     * @param string $locale
     *
     * @return bool
     */
    private function addYamlResource(string $filePath, string $locale)
    {
        if (\file_exists($filePath)) {
            $this->translator->addResource('yaml', $filePath, $locale);

            return true;
        }

        return false;
    }
}
