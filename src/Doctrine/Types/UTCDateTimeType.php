<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class UTCDateTimeType extends DateTimeType
{
	/** @var \DateTimeZone|null */
	private static $utc;

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if ($value instanceof \DateTime or $value instanceof \DateTimeImmutable) {
			$value->setTimezone(self::getUtc());
		}

		return parent::convertToDatabaseValue($value, $platform);
	}

	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if (null === $value || $value instanceof \DateTime) {
			return $value;
		}

		$converted = \DateTime::createFromFormat(
			strlen($value) === 10 ? $platform->getDateFormatString() : $platform->getDateTimeFormatString(),
			$value,
			self::$utc ? self::$utc : self::$utc = new \DateTimeZone('UTC')
		);

		if (!$converted) {
			throw ConversionException::conversionFailedFormat(
				$value,
				$this->getName(),
				$platform->getDateTimeFormatString()
			);
		}

		return $converted;
	}

	public static function getUtc(): \DateTimeZone
	{
		return self::$utc ? self::$utc : self::$utc = new \DateTimeZone('UTC');
	}

	public function requiresSQLCommentHint(AbstractPlatform $platform): bool
	{
		return true;
	}
}
