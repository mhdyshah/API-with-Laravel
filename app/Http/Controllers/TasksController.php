<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TaskResource::collection(
            Task::where('user_id', Auth::user()->id)->get()
        );
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        if (Auth::user()->id !== $task->user_id) {
            return $this->error('', 'You can not do it, sorry...', 403);
        }

        return new TaskResource($task);
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request
     *  @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        $request->validated($request->all());

        $task = Task::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return new TaskResource($task);
    }


    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        if (Auth::user()->id !== $task->user_id) {
            return $this->error('', 'You can not do it, sorry...', 403);
        }

        $task->update($request->all());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {

        if (Auth::user()->id !== $task->user_id) {
            return $this->error('', 'You can not do it, sorry...', 403);
        }

        $task->delete();

        return response(null, 204);
    }
}
