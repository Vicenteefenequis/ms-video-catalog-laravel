<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre;
use Core\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Datetime;

class GenreUnitTest extends TestCase
{

    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            id: new Uuid($uuid),
            name: 'New Genre',
            isActive: true,
            createdAt: new DateTime($date),
        );

        $this->assertEquals($uuid,$genre->id());
        $this->assertEquals('New Genre',$genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertEquals($date,$genre->createdAt());

    }
}
