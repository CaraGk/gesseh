<?php
  /**
   * Personal type to support LongBlob
   *
   * @author Leglopin
   */
  namespace Gesseh\CoreBundle\Doctrine\Types;

  use Doctrine\DBAL\Platforms\AbstractPlatform;
  use Doctrine\DBAL\DBALException;
  use Doctrine\DBAL\Types\Type;

  /**
   * Type that maps a PHP object to a longblob SQL type.
   */
  class LongBlobType extends Type
  {
    const LONGBLOB = 'longblob';

    public function getName() {
      return self::LONGBLOB;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
      return $platform->getDoctrineTypeMapping('LONGBLOB');
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
      return ($value === null) ? null : base64_encode($value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) {
      return ($value === null) ? null : base64_decode($value);
    }
  }
