<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Task;

class RouteTaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * POST /task
     * test the success of task creation.
     * 事前にタスクの数を数えておき、作成後に 1 つ数が増えていること。
     *
     * @return void
     */
    public function testSuccessOfPostTask()
    {
        $before_tasks_count = Task::all()->count();

        $response = $this->post('/task', ['name' => 'new task']);
        $response->assertStatus(302);

        $this->assertEquals($before_tasks_count + 1, Task::all()->count());
    }

    /**
     * POST /task
     * test the failure of task creation.
     * 文字数をオーバーしている場合にリダイレクトされること
     *
     * @return void
     */
    public function testFailureOfPostTask()
    {
        $response = $this->post('/task', ['name' => str_repeat('a', 256)]);
        $response->assertStatus(302);
    }
}
