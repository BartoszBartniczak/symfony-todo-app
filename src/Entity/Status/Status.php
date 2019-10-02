<?php

declare(strict_types=1);

namespace App\Entity\Status;

use App\Entity\Status\Exception\UnknownStatus;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Status.
 *
 * @ORM\Entity()
 */
class Status
{
    public const TO_DO = 'TO_DO';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const DONE = 'DONE';

    private const STATUSES = [
        self::TO_DO,
        self::IN_PROGRESS,
        self::DONE,
    ];

    /**
     * Status of the job.
     *
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id",               type="string", length=256)
     *
     * @SWG\Property(type="string")
     *
     * @Groups({"all"})
     */
    private $id;

    /**
     * Status constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->throwExceptionIfStatusUnknown($id);

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param self $status
     *
     * @return bool
     */
    public function canBeChangedOn(Status $status): bool
    {
        $changeArray = [
            self::TO_DO => self::IN_PROGRESS,
            self::IN_PROGRESS => self::DONE,
        ];

        if (!isset($changeArray[$this->getId()])) {
            return false;
        }

        return $changeArray[$this->getId()] === $status->getId();
    }

    /**
     * @param string $newStatus
     *
     * @return void
     */
    private function throwExceptionIfStatusUnknown(string $newStatus): void
    {
        if (!in_array($newStatus, self::STATUSES, true)) {
            throw new UnknownStatus();
        }
    }
}
