<?php

namespace App\Traits;

trait HasCrudTest
{
    public function test_seeders_works()
    {
        $model = new $this->model();
        $this->assertTrue($this->count > 0);
        $this->assertDatabaseCount($model->getTable(), $this->count);
    }

    public function test_index()
    {
        $response = $this->actingAs($this->user, 'api')->json('GET', $this->api);
        $response->assertOk();
        $response->assertJsonCount($this->count);
    }

    public function test_show()
    {
        $id = $this->model::first()->id;
        $response = $this->actingAs($this->user, 'api')->get($this->api . $id);
        $response->assertOk();
        $response->assertJson(['id' => $id]);
    }

    public function test_cannot_show_invalid_id()
    {
        $response = $this->actingAs($this->user, 'api')->get($this->api.'0');
        $response->assertNotFound();
    }

    public function test_store_valid_data()
    {
        $data = $this->model::factory()->make()->toArray();

        $response = $this->actingAs($this->user, 'api')->post($this->api, $data);

        $object = new $this->model ;

        $response->assertCreated();
        $response->assertJsonStructure(array_keys($data));
        $this->assertDatabaseCount($object->getTable(), $this->count + 1);
    }

    public function test_cannot_store_invalid_data()
    {
        $this->assertTrue(isset($this->invalidData)) ;

        $response = $this->actingAs($this->user, 'api')->post($this->api, $this->invalidData);
        $response->assertStatus(400);
    }

    public function test_update_valid_data()
    {
        $data = $this->model::factory()->make();
        $id = $this->model::first()->id;

        $response = $this->actingAs($this->user, 'api')->put($this->api . $id, $data->toArray());

        $response->assertOk();
        $response->assertJson(['id' => $id]);
    }

    public function test_cannot_update_invalid_data()
    {
        $this->assertTrue(isset($this->invalidData)) ;

        $id = $this->model::first()->id;

        $response = $this->actingAs($this->user, 'api')->put($this->api . $id, $this->invalidData);
        $response->assertStatus(400);
    }

    public function test_delete()
    {
        $model = $this->model::find(2);
        $response = $this->actingAs($this->user, 'api')->delete($this->api . $model->id);

        $response->assertOk();
        $response->assertjson(['id' => $model->id]);

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
        ]);
        $this->assertSoftDeleted($model);
    }
}
