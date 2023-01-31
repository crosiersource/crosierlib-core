<?php

declare(strict_types=1);

namespace CrosierSource\CrosierLibCoreBundle\ApiPlatform;

use ApiPlatform\Core\Operation\PathSegmentNameGeneratorInterface;
use ApiPlatform\Core\Util\Inflector;

final class SingularPathSegmentNameGenerator implements PathSegmentNameGeneratorInterface
{
    public function getSegmentName(string $name, bool $collection = true): string
    {
        return Inflector::tableize($name);
    }
}
