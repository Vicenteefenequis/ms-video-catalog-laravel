<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Datetime;

class GenreUnitTest extends TestCase
{

    public function testAttributes()
    {
        $uuid = (string)RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            name: 'New Genre',
            id: new Uuid($uuid),
            isActive: true,
            createdAt: new DateTime($date),
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertEquals($date, $genre->createdAt());
    }

    public function testAttributesCreate()
    {

        $genre = new Genre(
            name: 'New Genre',
        );

        $this->assertNotEmpty($genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testDeactivate()
    {
        $genre = new Genre(
            name: 'Test'
        );

        $this->assertTrue($genre->isActive);

        $genre->deactivate();

        $this->assertFalse($genre->isActive);
    }

    public function testActivate()
    {
        $genre = new Genre(
            name: 'Test',
            isActive: false
        );

        $this->assertFalse($genre->isActive);

        $genre->activate();

        $this->assertTrue($genre->isActive);
    }

    public function testUpdate()
    {
        $genre = new Genre(
            name: 'Test'
        );

        $this->assertEquals('Test', $genre->name);

        $genre->update(
            name: 'Test Updated'
        );

        $this->assertEquals('Test Updated', $genre->name);
    }

    public function testEntityExceptions()
    {
        $this->expectException(EntityValidationException::class);

        new Genre(
            name: 'T'
        );
    }

    public function testEntityUpdateException()
    {
        $this->expectException(EntityValidationException::class);

        $genre = new Genre(
            name: 'Teste'
        );

        $genre->update('T');
    }

    public function testAddCategoryToGenre()
    {
        $categoryId = (string)RamseyUuid::uuid4();

        $genre = new Genre(
            name: 'new genre'
        );

        $this->assertIsArray($genre->categoriesId);
        $this->assertCount(0, $genre->categoriesId);


        $genre->addCategory(
            categoryId: $categoryId
        );

        $genre->addCategory(
            categoryId: $categoryId
        );


        $this->assertCount(2, $genre->categoriesId);
    }


    public function testRemoteCategoryToGenre()
    {
        $categoryId = (string)RamseyUuid::uuid4();
        $categoryId2 = (string)RamseyUuid::uuid4();

        $genre = new Genre(
            name: 'new genre',
            categoriesId: [
                $categoryId,
                $categoryId2
            ]
        );


        $this->assertCount(2, $genre->categoriesId);

        $genre->removeCategory(
            categoryId: $categoryId
        );

        $this->assertCount(1, $genre->categoriesId);
        $this->assertContains($categoryId2,$genre->categoriesId);
        $this->assertNotContains($categoryId,$genre->categoriesId);
    }

}
