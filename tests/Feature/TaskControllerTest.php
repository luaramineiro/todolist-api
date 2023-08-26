<?php

namespace Tests\Feature;

use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use Mockery;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_should_return_the_version_info(): void
    {
        $response = $this->getJson('/api');
        $response->assertStatus(200);
        $response->assertJson([
            'appName' => 'tolistapi', 
            'version' => '1.0.0'
        ]);
    }

    /**
     * Happy path for /tasks (GET)
     */
    public function test_should_return_the_list_of_all_tasks(): void
    {
        $quantity = mt_rand(1, 10);
        $tasks = Task::factory($quantity)->create();
        $response = $this->getJson('/api/tasks');
        $responseData = $response['data'];

        $response->assertStatus(200);
        $this->assertCount($quantity, $responseData);
        $this->assertEquals($response['total'], $quantity);

        // TODO: validate all task objects returned
    }

    /**
     * Happy path for /tasks (POST)
     */
    public function test_should_create_a_new_task() : void 
    {
        $task = Task::factory()->makeOne()->getAttributes();
        if (array_key_exists('attachment', $task)) {
            $task['attachment'] = json_decode($task['attachment']);
        }
        $response = $this->postJson('/api/tasks', $task);
        $this->validate_task_object($task, $response['data']);
        $response->assertStatus(201);
    }

    /**
     * Happy path for /tasks/{id} (GET)
     */
    public function test_should_return_the_details_of_a_specific_task(): void
    {
        $task = Task::factory()->create()->getAttributes();
        $response = $this->getJson('/api/tasks/' . strval($task['id']));
        $this->validate_task_object($task, $response['data']);
        $response->assertStatus(200);
    }

    /**
     * Happy path for /tasks/{id} (PUT)
     */
    public function test_should_update_a_specific_task() : void 
    {
        $task = Task::factory()->create()->getAttributes();
        $faker = \Faker\Factory::create();
        $dataUpdated = [
            'description'   => $faker->paragraph(3, true),
            'is_completed'  => (bool)random_int(0, 1),
        ];

        $response = $this->putJson('/api/tasks/' . strval($task['id']), $dataUpdated);
        $task['description']  = $dataUpdated['description'];
        $task['is_completed'] = $dataUpdated['is_completed'];
        $this->validate_task_object($task, $response['data']);
        $response->assertStatus(200);
    }

    /**
     * Happy path for /tasks/{id} (DELETE)
     */
    public function test_should_remove_a_specific_task() : void 
    {
        $task = Task::factory()->create()->getAttributes();
        $response = $this->deleteJson('/api/tasks/' . strval($task['id']));
        $this->validate_task_object($task, $response['data']);
        $response->assertStatus(200);
    }

    /**
     * Sad path for /tasks/{id} (GET)
     */
    public function test_should_not_return_a_specific_task_if_uuid_is_not_correct() : void 
    {
        $faker = \Faker\Factory::create();
        $response = $this->getJson('/api/tasks/' . $faker->uuid);
        $response->assertStatus(404);
    }

    /**
     * Sad path for /tasks (POST)
     */
    public function test_should_not_create_a_new_task_if_the_title_is_not_correct() : void 
    {
        $faker = \Faker\Factory::create();
        $data = [
            'description'   => $faker->paragraph(3, true), 
            'user_id'       => $faker->uuid
        ];

        $response = $this->postJson('/api/tasks', $data);
        $response->assertStatus(400);

    }

    /**
     * Sad path for /tasks (POST)
     */
    public function test_should_not_create_a_new_task_if_the_description_is_not_correct() : void 
    {
        $faker = \Faker\Factory::create();
        $data = [
            'title'   => $faker->unique()->sentence(), 
            'user_id' => $faker->uuid
        ];

        $response = $this->postJson('/api/tasks', $data);
        $response->assertStatus(400);
    }

    /**
     * Sad path for /tasks (POST)
     */
    public function test_should_not_create_a_new_task_if_the_user_id_is_not_correct() : void 
    {
        $faker = \Faker\Factory::create();
        $data = [
            'title'   => $faker->unique()->sentence(), 
            'description'   => $faker->paragraph(3, true)
        ];

        $response = $this->postJson('/api/tasks', $data);
        $response->assertStatus(400);
    }    
    
    /**
    * Happy path for /tasks/{id} (PUT)
    */
   public function test_should_not_update_a_specific_task_if_uuid_is_not_correct() : void 
   {
       $faker = \Faker\Factory::create();
       $dataUpdated = [
           'description'   => $faker->paragraph(3, true),
           'is_completed'  => (bool)random_int(0, 1),
       ];

       $response = $this->putJson('/api/tasks/' . $faker->uuid, $dataUpdated);
       $task['description']  = $dataUpdated['description'];
       $task['is_completed'] = $dataUpdated['is_completed'];
       $response->assertStatus(404);
   }

    /**
     * Sad path for /tasks/{id} (DELETE)
     */
    public function test_should_not_delete_a_specific_task_if_uuid_is_not_correct() : void 
    {
        $faker = \Faker\Factory::create();
        $response = $this->deleteJson('/api/tasks/' . $faker->uuid);
        $response->assertStatus(404);
    }

    public function validate_task_object($task, $response) : void 
    {
        while (current($response)) {
            $attribute = key($response);

            if (array_key_exists($attribute, $task)) {
                if($attribute == 'attachment') {
                    $response['attachment'] = json_decode($response['attachment']);
                }   
                $this->assertContains($task[$attribute], $response);
            }
            // TODO: validate the types
            next($response);
        }
    }
}