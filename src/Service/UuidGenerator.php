<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: bartosz.
 */

namespace App\Service;

use Ramsey\Uuid\Uuid;

/**
 * Class UuidGenerator.
 */
class UuidGenerator
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
