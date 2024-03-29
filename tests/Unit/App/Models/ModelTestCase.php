<?php


namespace Tests\Unit\App\Models;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function traits(): array;
    abstract protected function fillables(): array;
    abstract protected function casts(): array;


    public function testIfUseTraits(): void
    {

        $traitsNeed = $this->traits();

        $traits = array_keys(class_uses($this->model()));

        $this->assertEquals($traitsNeed, $traits);
    }


    public function testIncrementingIsFalse()
    {
        $model = $this->model();

        $this->assertFalse($model->incrementing);
    }

    public function testHasCasts()
    {
        $castsNeed = $this->casts();

        $casts = $this->model()->getCasts();


        $this->assertEquals($castsNeed, $casts);
    }

    public function testFillables()
    {
        $expected = $this->fillables();

        $fillable = $this->model()->getFillable();

        $this->assertEquals($expected, $fillable);
    }

}
