<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Status
 *
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Status
{
    const TO_DO = 'TO_DO';
    const IN_PROGRESS = 'IN_PROGRESS';
    const DONE = 'DONE';

    /**
     * Status of the job
     *
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id", type="string", length=256)
     *
     * @SWG\Property(type="string")
     *
     * @Groups({"all"})
     *
     */
    private $id;

    /**
     * Status constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

}
