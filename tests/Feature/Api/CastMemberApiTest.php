<?php

namespace Tests\Feature\Api;

use App\Models\CastMember;
use Illuminate\Http\Response;
use Tests\TestCase;

class CastMemberApiTest extends TestCase
{
    private $endpoint = '/api/cast_members';


    public function test_get_all_empty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function test_get_all()
    {
        CastMember::factory()->count(50)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            "meta" => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from'
            ]
        ]);
    }

    public function test_pagination_two()
    {
        CastMember::factory()->count(20)->create();

        $response = $this->getJson("$this->endpoint?page=2");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        $this->assertEquals(20, $response['meta']['total']);
        $this->assertEquals(2, $response['meta']['current_page']);
    }

    public function test_pagination_with_filter()
    {
        CastMember::factory()->count(10)->create();
        CastMember::factory()->count(10)->create([
            "name" => 'test'
        ]);

        $response = $this->getJson("$this->endpoint?filter=test");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(10, 'data');
    }

    public function test_show_not_found()
    {
        $response = $this->getJson("$this->endpoint/fake_id");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_show()
    {
        $cast_member = CastMember::factory()->create();
        $response = $this->getJson("$this->endpoint/$cast_member->id");
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            "data" => [
                'id',
                'name',
                'type',
                'created_at',
            ]
        ]);
    }

    public function test_store_validation()
    {
        $response = $this->postJson($this->endpoint, []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            "message",
            "errors" => [
                "name",
                "type"
            ],
        ]);
    }

    public function test_store()
    {
        $response = $this->postJson($this->endpoint, [
            "name" => "test",
            "type" => 1
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            "data" => [
                "id",
                "name",
                "type",
                "created_at"
            ]
        ]);

        $this->assertDatabaseHas('cast_members',[
            "name" => "test"
        ]);
    }

}
