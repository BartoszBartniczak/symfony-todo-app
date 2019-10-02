<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bartosz.
 */

namespace App\Tests\Service;

use App\Service\UuidGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class UuidGeneratorTest extends TestCase
{
    /**
     * @covers \App\Service\UuidGenerator::generate
     */
    public function testGenerate()
    {
        $generator = new UuidGenerator();

        $this->assertRegExp('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $generator->generate());
    }
}
