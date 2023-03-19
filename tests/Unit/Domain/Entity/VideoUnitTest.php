<?php

namespace Domain\Entity;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class VideoUnitTest extends TestCase
{
    public function test_attributes()
    {
        $uuid = (string)RamseyUuid::uuid4();
        $entity = new Video(
            title: "title",
            description: "description",
            yearLaunched: 2029,
            duration: 12,
            opened: false,
            rating: Rating::RATE12,
            id: new Uuid($uuid),
            published: false
        );

        $this->assertEquals($uuid,$entity->id());
        $this->assertEquals("title",$entity->title);
        $this->assertEquals("description",$entity->description);
        $this->assertEquals(2029,$entity->yearLaunched);
        $this->assertEquals(12,$entity->duration);
        $this->assertFalse($entity->opened);
        $this->assertEquals(Rating::RATE12,$entity->rating);
        $this->assertFalse($entity->published);
    }

}
