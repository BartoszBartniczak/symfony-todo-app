<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 *
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     *
     * @SWG\Property(type="string", format="uuid", description="User unique ID")
     *
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @SWG\Property(type="string", description="Task title")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="2", max="255")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     *
     * @SWG\Property(type="string", description="Task description")
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1")
     */
    private $description;

    /**
     * Post constructor.
     * @param $id
     * @param $title
     * @param $description
     */
    public function __construct($id, $title, $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function changeTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function changeDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
