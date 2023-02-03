<?php


namespace CrosierSource\CrosierLibCoreBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer as APIPlatformDateTimeNormalizer;

/**
 * Class DateTimeNormalizer
 * @package CrosierSource\CrosierLibBaseBundle\Normalizer
 */
class DateTimeNormalizer extends APIPlatformDateTimeNormalizer
{
    
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): \DateTimeInterface
    {
        // substitui o comportamento padrão para poder receber valores null (problema com ApiPlatform)
        if (null === $data) {
            return null;
        }

        return parent::denormalize($data, $type, $format, $context);
    }
}
