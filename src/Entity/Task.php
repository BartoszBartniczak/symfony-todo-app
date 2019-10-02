<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Status\Status;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     *
     * @SWG\Property(type="string", format="uuid", description="User unique ID")
     *
     * @Assert\NotBlank()
     * @Assert\Uuid()
     *
     * @Groups({"all"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @SWG\Property(type="string", description="Task title")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="2", max="255")
     *
     * @Groups({"all"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @SWG\Property(type="string", description="Task description")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1")
     *
     * @Groups({"all"})
     */
    private $description;

    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Status\Status")
     * @ORM\JoinColumn(name="status_id",                       referencedColumnName="id")
     *
     * @SWG\Property(ref=@Model(type=Status::class), description="Status of the task")
     *
     * @Groups({"all"})
     */
    private $status;

    /**
     * Post constructor.
     *
     * @param string $id
     * @param string $title
     * @param string $description
     * @param Status $status
     */
    public function __construct(string $id, string $title, string $description, Status $status)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return void
     */
    public function changeTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return void
     */
    public function changeDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $newStatus
     *
     * @return void
     */
    public function changeStatus(Status $newStatus): void
    {
        if ($this->status->canBeChangedOn($newStatus)) {
            $this->status = $newStatus;
        }
    }
}
