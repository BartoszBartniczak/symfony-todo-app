<?php
/**
 * Created by PhpStorm.
 * User: bartosz
 */

namespace App\Tests\Entity\Status;

use App\Entity\Status\Exception\UnknownStatus;
use App\Entity\Status\Status;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{

    /**
     * @covers \App\Entity\Status\Status::__construct
     * @covers \App\Entity\Status\Status::getId
     * @covers \App\Entity\Status\Status::throwExceptionIfStatusUnknown
     */
    public function testConstructorAndGetters(){
        $status = new Status(Status::TO_DO);
        $this->assertSame(Status::TO_DO, $status->getId());
    }

    /**
     * @covers \App\Entity\Status\Status::__construct
     * @covers \App\Entity\Status\Status::throwExceptionIfStatusUnknown
     */
    public function testConstructorThrowsExceptionIfStatusIsUnknown(){
        $this->expectException(UnknownStatus::class);

        new Status('ZZZ');
    }

    /**
     * @covers \App\Entity\Status\Status::canBeChangedOn
     */
    public function testCanBeChangedOn(){
        $todo = new Status(Status::TO_DO);
        $this->assertTrue($todo->canBeChangedOn(new Status(Status::IN_PROGRESS)));
        $this->assertFalse($todo->canBeChangedOn(new Status(Status::DONE)));

        $inProgress = new Status(Status::IN_PROGRESS);
        $this->assertTrue($inProgress->canBeChangedOn(new Status(Status::DONE)));
        $this->assertFalse($inProgress->canBeChangedOn(new Status(Status::TO_DO)));

        $done = new Status(Status::DONE);
        $this->assertFalse($done->canBeChangedOn(new Status(Status::TO_DO)));
        $this->assertFalse($done->canBeChangedOn(new Status(Status::IN_PROGRESS)));
    }
}
