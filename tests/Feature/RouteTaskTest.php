<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Task;

class RouteTaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * GET /
     *
     * @return void
     */
    public function testGetIndex()
    {
        $task = factory(Task::class)->create(['name' => 'new_task']);

        $response = $this->get('/');
        $response->assertStatus(200);
        $this->assertRegExp('/'.$task->name.'/', $response->getContent());
    }

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

    /**
     * DELETE /task
     * test the success of task creation.
     *
     * @return void
     */
    public function testSuccessOfDeleteTask()
    {
        $task = factory(Task::class)->create();

        $response = $this->delete('/task/'.$task->id);
        $response->assertStatus(302);

        $this->assertEmpty(Task::find($task->id));
    }

    /**
     * DELETE /task
     * test disable deleting other user's task
     *
     * @return void
     */
    public function testDisableOfDeletingOtherUserTask()
    {
        $task = factory(Task::class)->create();

        $request_id = $task->id + 1;
        $response = $this->delete('/task/'.$request_id);
        $response->assertStatus(404);
    }
}
