<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Task;
use App\User;

class RouteTaskTest extends TestCase
{
    use RefreshDatabase;

    private ?User $user = null;

    public function setUp(): void
    {
      parent::setUp();
      $this->user = factory(User::class)->create();
    }

    /**
     * GET /
     *
     * @return void
     */
    public function testGetIndex()
    {
        $task = $this->user->tasks()->save(factory(Task::class)->make(['name' => 'new_task']));

        $response = $this->actingAs($this->user)->get('/');
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

        $response = $this->actingAs($this->user)->post('/task', ['name' => 'new task']);
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
        $response = $this->actingAs($this->user)->post('/task', ['name' => str_repeat('a', 256)]);
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
        $task = $this->user->tasks()->save(factory(Task::class)->make());

        $response = $this->actingAs($this->user)->delete('/task/'.$task->id);
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
        $task = $this->user->tasks()->save(factory(Task::class)->make());

        $request_id = $task->id + 1;
        $response = $this->actingAs($this->user)->delete('/task/'.$request_id);
        $response->assertStatus(404);
    }
}
