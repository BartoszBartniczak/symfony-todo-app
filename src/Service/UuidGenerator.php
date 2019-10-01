<?php
/**
 * Created by PhpStorm.
 * User: bartosz
 */

namespace App\Service;


use Ramsey\Uuid\Uuid;

class UuidGenerator
{

    /**
     * @return string
     */
    public function generate():string {
        return Uuid::uuid4()->toString();
    }

}
