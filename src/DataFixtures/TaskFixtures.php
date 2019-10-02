<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bartosz.
 */

namespace App\DataFixtures;

use App\Entity\Status\Status;
use App\Entity\Task;
use App\Service\UuidGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TaskFixtures extends Fixture
{
    /**
     * @var UuidGenerator
     */
    private $uuidGenerator;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct(UuidGenerator $uuidGenerator)
    {
        $this->faker = Factory::create();
        $this->uuidGenerator = $uuidGenerator;
    }

    public function load(ObjectManager $manager)
    {
        $statusTodo = $manager->merge(new Status(Status::TO_DO));
        $todo = new Task($this->uuidGenerator->generate(), $this->faker->realText(50), $this->faker->realText(300), $statusTodo);
        $manager->persist($todo);

        $statusInProgress = $manager->merge(new Status(Status::IN_PROGRESS));
        $inProgress = new Task($this->uuidGenerator->generate(), $this->faker->realText(50), $this->faker->realText(300), $statusInProgress);
        $manager->persist($inProgress);

        $statusDone = $manager->merge(new Status(Status::DONE));
        $done = new Task($this->uuidGenerator->generate(), $this->faker->realText(50), $this->faker->realText(300), $statusDone);
        $manager->merge($done->getStatus());
        $manager->persist($done);

        $manager->flush();
    }
}
