<?php

namespace Domain\Entity;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use DateTime;

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
            published: false,
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );

        $this->assertEquals($uuid,$entity->id());
        $this->assertEquals("title",$entity->title);
        $this->assertEquals("description",$entity->description);
        $this->assertEquals(2029,$entity->yearLaunched);
        $this->assertEquals(12,$entity->duration);
        $this->assertFalse($entity->opened);
        $this->assertEquals(Rating::RATE12,$entity->rating);
        $this->assertFalse($entity->published);
        $this->assertNotNull($entity->createdAt());
    }

    public function test_add_category()
    {
        $categoryId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: "title",
            description: "description",
            yearLaunched: 2029,
            duration: 12,
            opened: false,
            rating: Rating::RATE12,
            published: false
        );

        $this->assertCount(0,$entity->categoriesId);

        $entity->addCategoryId($categoryId);

        $this->assertCount(1,$entity->categoriesId);

        $entity->addCategoryId($categoryId);

        $this->assertCount(2,$entity->categoriesId);
    }

    public function test_remove_category()
    {
        $categoryId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: "title",
            description: "description",
            yearLaunched: 2029,
            duration: 12,
            opened: false,
            rating: Rating::RATE12,
            published: false
        );


        $entity->addCategoryId($categoryId);

        $entity->addCategoryId("any_category_id");

        $this->assertCount(2,$entity->categoriesId);

        $entity->removeCategoryId($categoryId);

        $this->assertCount(1,$entity->categoriesId);
    }

    public function test_add_genre()
    {
        $genreId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: "title",
            description: "description",
            yearLaunched: 2029,
            duration: 12,
            opened: false,
            rating: Rating::RATE12,
            published: false
        );

        $this->assertCount(0,$entity->genresId);

        $entity->addGenreId($genreId);

        $this->assertCount(1,$entity->genresId);

        $entity->addGenreId($genreId);

        $this->assertCount(2,$entity->genresId);
    }


    public function test_remove_genre()
    {
        $genreId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: "title",
            description: "description",
            yearLaunched: 2029,
            duration: 12,
            opened: false,
            rating: Rating::RATE12,
            published: false
        );


        $entity->addGenreId($genreId);
        $entity->addGenreId("any_id");

        $this->assertCount(2,$entity->genresId);

        $entity->removeGenreId($genreId);

        $this->assertCount(1,$entity->genresId);
    }


    public function test_add_cast_member()
    {
        $castMemberId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: "title",
            description: "description",
            yearLaunched: 2029,
            duration: 12,
            opened: false,
            rating: Rating::RATE12,
            published: false
        );

        $this->assertCount(0,$entity->castMembersId);

        $entity->addCastMemberId($castMemberId);

        $this->assertCount(1,$entity->castMembersId);

        $entity->addCastMemberId($castMemberId);

        $this->assertCount(2,$entity->castMembersId);
    }


    public function test_remove_cast_members()
    {
        $castMemberId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: "title",
            description: "description",
            yearLaunched: 2029,
            duration: 12,
            opened: false,
            rating: Rating::RATE12,
            published: false
        );


        $entity->addCastMemberId($castMemberId);
        $entity->addCastMemberId("any_id");

        $this->assertCount(2,$entity->castMembersId);

        $entity->removeCastMemberId($castMemberId);

        $this->assertCount(1,$entity->castMembersId);
    }

}
