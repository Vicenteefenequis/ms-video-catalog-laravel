<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Enum\CastMemberType;
use Core\Domain\Entity\CastMember;
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

        $this->assertEquals($uuid,$castMember->id());
        $this->assertEquals('Name',$castMember->name);
        $this->assertEquals(CastMemberType::ACTOR,$castMember->type);
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
        $this->assertEquals('Name',$castMember->name);
        $this->assertEquals(CastMemberType::DIRECTOR,$castMember->type);
        $this->assertNotEmpty($castMember->createdAt());
    }



}
