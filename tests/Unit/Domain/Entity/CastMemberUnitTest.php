<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Enum\CastMemberType;
use Core\Domain\Entity\CastMember;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class CastMemberUnitTest extends TestCase
{
    public function test_attributes()
    {

        $uuid = (string)RamseyUuid::uuid4();

        $castMember = new CastMember(
            name: 'Name',
            type: CastMemberType::ACTOR,
            id: new Uuid($uuid),
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );

        $this->assertEquals($uuid, $castMember->id());
        $this->assertEquals('Name', $castMember->name);
        $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }


    public function test_attributes_new_entity()
    {
        $castMember = new CastMember(
            name: 'Name',
            type: CastMemberType::DIRECTOR,
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );

        $this->assertNotEmpty($castMember->id());
        $this->assertEquals('Name', $castMember->name);
        $this->assertEquals(CastMemberType::DIRECTOR, $castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }

    public function test_validation()
    {
        $this->expectException(EntityValidationException::class);

        new CastMember(
            name: 'ab',
            type: CastMemberType::DIRECTOR
        );
    }

    public function test_exception_update()
    {
        $this->expectException(EntityValidationException::class);

        $castMember = new CastMember(
            name: 'New Name',
            type: CastMemberType::DIRECTOR
        );

        $castMember->update(
            name: 'N'
        );

    }

    public function test_update()
    {
        $castMember = new CastMember(
            name: 'New Name',
            type: CastMemberType::DIRECTOR
        );

        $castMember->update(
            name: 'Name Updated'
        );

        $this->assertEquals('Name Updated',$castMember->name);
    }



}
