<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformUser\Templating\Twig;

use DateTimeImmutable;
use EzSystems\EzPlatformUser\UserSetting\Setting\DateTimeFormatSerializer;
use EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateTimeExtension extends AbstractExtension
{
    /** @var \EzSystems\EzPlatformUser\UserSetting\Setting\DateTimeFormatSerializer */
    private $dateTimeFormatSerializer;

    /** @var \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface */
    private $shortDateTimeFormatter;

    /** @var \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface */
    private $shortDateFormatter;

    /** @var \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface */
    private $shortTimeFormatter;

    /** @var \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface */
    private $fullDateTimeFormatter;

    /** @var \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface */
    private $fullDateFormatter;

    /** @var \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface */
    private $fullTimeFormatter;

    /**
     * @param \EzSystems\EzPlatformUser\UserSetting\Setting\DateTimeFormatSerializer $dateTimeFormatSerializer
     * @param \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface $shortDateTimeFormatter
     * @param \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface $shortDateFormatter
     * @param \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface $shortTimeFormatter
     * @param \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface $fullDateTimeFormatter
     * @param \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface $fullDateFormatter
     * @param \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface $fullTimeFormatter
     */
    public function __construct(
        DateTimeFormatSerializer $dateTimeFormatSerializer,
        FormatterInterface $shortDateTimeFormatter,
        FormatterInterface $shortDateFormatter,
        FormatterInterface $shortTimeFormatter,
        FormatterInterface $fullDateTimeFormatter,
        FormatterInterface $fullDateFormatter,
        FormatterInterface $fullTimeFormatter
    ) {
        $this->dateTimeFormatSerializer = $dateTimeFormatSerializer;
        $this->shortDateTimeFormatter = $shortDateTimeFormatter;
        $this->shortDateFormatter = $shortDateFormatter;
        $this->shortTimeFormatter = $shortTimeFormatter;
        $this->fullDateTimeFormatter = $fullDateTimeFormatter;
        $this->fullDateFormatter = $fullDateFormatter;
        $this->fullTimeFormatter = $fullTimeFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('ez_short_datetime', [$this, 'formatShortDateTime']),
            new TwigFilter('ez_short_date', [$this, 'formatShortDate']),
            new TwigFilter('ez_short_time', [$this, 'formatShortTime']),
            new TwigFilter('ez_full_datetime', [$this, 'formatFullDateTime']),
            new TwigFilter('ez_full_date', [$this, 'formatFullDate']),
            new TwigFilter('ez_full_time', [$this, 'formatFullTime']),
        ];
    }

    public function formatShortDateTime($date, $timezone = null)
    {
        return $this->format($this->shortDateTimeFormatter, $date, $timezone);
    }

    public function formatShortDate($date, $timezone = null)
    {
        return $this->format($this->shortDateFormatter, $date, $timezone);
    }

    public function formatShortTime($date, $timezone = null)
    {
        return $this->format($this->shortTimeFormatter, $date, $timezone);
    }

    public function formatFullDateTime($date, $timezone = null)
    {
        return $this->format($this->fullDateTimeFormatter, $date, $timezone);
    }

    public function formatFullDate($date, $timezone = null)
    {
        return $this->format($this->fullDateFormatter, $date, $timezone);
    }

    public function formatFullTime($date, $timezone = null)
    {
        return $this->format($this->fullTimeFormatter, $date, $timezone);
    }

    /**
     * @param \EzSystems\EzPlatformUser\UserSetting\DateTimeFormat\FormatterInterface $formatter
     * @param mixed|null $date
     * @param string|null $timezone
     *
     * @return string
     *
     * @throws \Exception
     */
    public function format(FormatterInterface $formatter, $date = null, string $timezone = null): string
    {
        if ($date === null) {
            $date = new DateTimeImmutable();
        }

        if (is_int($date)) {
            $date = new DateTimeImmutable('@' . $date);
        }

        if (!$date instanceof \DateTimeInterface) {
            throw new \RuntimeException('The date argument passed to format function must be int or DateTimeInterface');
        }

        return $formatter->format($date, $timezone);
    }
}
