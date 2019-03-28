<?php

declare(strict_types=1);

namespace Randock\TimeInterval\ReadableTimeInterval;

use Randock\TimeInterval\ReadableTimeInterval\Exception\LocaleNotSupportedException;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;

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

        $textComponents=[];

        if (0 !== $seconds) {
            $textComponents[] = $this->translator->trans('timeInterval.seconds', ['%count%' => $seconds]);
        }

        if (0 !== $minutes) {
            $textComponents[] = $this->translator->trans('timeInterval.minutes', ['%count%' => $minutes]);
        }

        if (0 !== $hours) {
            $textComponents[] = $this->translator->trans('timeInterval.hours', ['%count%' => $hours]);
        }

        if (0 !== $days) {
            $textComponents[] = $this->translator->trans('timeInterval.days', ['%count%' => $days]);
        }
        $output = '';

        $i= 0;
        $len = count($textComponents);
        foreach ($textComponents as $textComponent) {
            if ($i === 0) {
                if ($len > 1) {
                    $output =
                        sprintf(
                            ' %s %s',
                            $this->translator->trans('timeInterval.and'),
                            $textComponent
                        );
                } else {
                    $output = sprintf('%s', $textComponent);
                }
            } elseif ($i === $len - 1) {
                $output = sprintf('%s%s', $textComponent, $output);
            } else {
                $output = sprintf(', %s%s', $textComponent, $output);
            }
            $i++;
        }
        return $output;
    }

    /**
     * @param string $locale
     *
     * @return ReadableTimeIntervalGenerator
     * @throws LocaleNotSupportedException
     */
    public function setLocale(string $locale): self
    {
        $translationFile = sprintf('/app/translations/messages.%s.yaml', $locale);
        $resourceAdded = $this->addYamlResource($translationFile, $locale);
        if (!$resourceAdded) {
            $translationFile = sprintf('/app/translations/messages.%s.yaml', substr($locale, 0, 2));
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
        if (file_exists($filePath)) {
            $this->translator->addResource('yaml', $filePath, $locale);
            return true;
        }
        return false;
    }
}
