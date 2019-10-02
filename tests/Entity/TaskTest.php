<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bartosz.
 */

namespace App\Tests\Entity;

use App\Entity\Status\Status;
use App\Entity\Task;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class TaskTest extends TestCase
{
    /**
     * @covers \App\Entity\Task::__construct
     * @covers \App\Entity\Task::changeDescription
     * @covers \App\Entity\Task::changeStatus
     * @covers \App\Entity\Task::changeTitle
     * @covers \App\Entity\Task::getDescription
     * @covers \App\Entity\Task::getId
     * @covers \App\Entity\Task::getStatus
     * @covers \App\Entity\Task::getTitle
     */
    public function testConstructorGettersAndSetters()
    {
        $status = new Status(Status::TO_DO);
        $task = new Task('fd229975-bc53-4646-836f-c700e1bcf147', 'Title', 'description', $status);

        $this->assertSame('fd229975-bc53-4646-836f-c700e1bcf147', $task->getId());
        $this->assertSame('Title', $task->getTitle());
        $this->assertSame('description', $task->getDescription());
        $this->assertSame($status, $task->getStatus());

        $task->changeTitle('New title');
        $this->assertSame('New title', $task->getTitle());

        $task->changeDescription('New description');
        $this->assertSame('New description', $task->getDescription());

        $newStatus = new Status(Status::IN_PROGRESS);
        $task->changeStatus($newStatus);
        $this->assertSame($newStatus, $task->getStatus());
    }
}
