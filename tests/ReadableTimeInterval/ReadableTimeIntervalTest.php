<?php

declare(strict_types=1);

namespace Tests\Randock\TimeInterval\ReadableTimeInterval;

use PHPUnit\Framework\TestCase;
use Randock\TimeInterval\TimeInterval\TimeInterval;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Randock\TimeInterval\ReadableTimeInterval\ReadableTimeIntervalGenerator;
use Randock\TimeInterval\ReadableTimeInterval\Exception\LocaleNotSupportedException;

class ReadableTimeIntervalTest extends TestCase
{
    /**
     * @var Translator
     */
    private $translator;

    public function setUp()
    {
        $this->translator = new Translator(null);
        $this->translator->addLoader('yaml', new YamlFileLoader());
        $this->translator->addResource('yaml', '/app/translations/messages.en.yaml', 'en_EN');
        $this->translator->addResource('yaml', '/app/translations/messages.nl.yaml', 'nl_NL');
        $this->translator->addResource('yaml', '/app/translations/messages.fr.yaml', 'fr_FR');
        $this->translator->addResource('yaml', '/app/translations/messages.de.yaml', 'de_DE');
        $this->translator->addResource('yaml', '/app/translations/messages.de.yaml', 'es_ES');
    }

    public function testGetReadableTimeText()
    {
        $readableTimeIntervalGenerator = new ReadableTimeIntervalGenerator('fr_BE');
        $generatedTranslation = $readableTimeIntervalGenerator->getReadableTimeText(200000);

        $this->translator->setLocale('fr_FR');
        $expectedOutput = \sprintf(
            '%s, %s, %s %s %s',
            $this->translator->trans('timeInterval.days', ['%count%' => 2]),
            $this->translator->trans('timeInterval.hours', ['%count%' => 7]),
            $this->translator->trans('timeInterval.minutes', ['%count%' => 33]),
            $this->translator->trans('timeInterval.and'),
            $this->translator->trans('timeInterval.seconds', ['%count%' => 20])
            );
        $this->assertSame($expectedOutput, $generatedTranslation);
    }

    public function testGetReadableTimeTextLocaleNotSupported()
    {
        $this->expectException(LocaleNotSupportedException::class);
        new ReadableTimeIntervalGenerator('hr-HR');
    }

    public function testGetReadableSingularEnglish()
    {
        $readableTimeIntervalGenerator = new ReadableTimeIntervalGenerator('en_EN');
        $generatedTranslation = $readableTimeIntervalGenerator->getReadableTimeText(60);

        $this->translator->setLocale('en_EN');
        $this->assertSame('1 minute', $generatedTranslation);
    }

    public function testPluralEnglish()
    {
        $readableTimeIntervalGenerator = new ReadableTimeIntervalGenerator('en_EN');
        $generatedTranslation = $readableTimeIntervalGenerator->getReadableTimeText(120);

        $this->translator->setLocale('en_EN');
        $this->assertSame('2 minutes', $generatedTranslation);
    }

    public function testFromTimeInterval()
    {
        $timeInterval = TimeInterval::newFromSeconds(5);
        $readableTimeIntervalGenerator = new ReadableTimeIntervalGenerator('en_EN');
        $generatedTranslation = $readableTimeIntervalGenerator->getReadableTimeIntervalText($timeInterval);

        $this->translator->setLocale('en_EN');
        $this->assertSame('5 seconds', $generatedTranslation);
    }

    public function testFromZeroSeconds()
    {
        $timeInterval = TimeInterval::newFromSeconds(0);
        $readableTimeIntervalGenerator = new ReadableTimeIntervalGenerator('en_EN');
        $generatedTranslation = $readableTimeIntervalGenerator->getReadableTimeIntervalText($timeInterval);

        $this->translator->setLocale('en_EN');
        $this->assertSame('0 seconds', $generatedTranslation);
    }
}
