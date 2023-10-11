<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    public function task($id)
    {
        $tasks = Task::where('user_id', $id)->get();

        $formattedTasks = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'created_at' => Carbon::parse($task->update_at)->format('Y-m-d'),
                'task' => $task->task,
                'description' => $task->description,
                'link' => $task->link,
                'is_completed' => $task->is_completed,




            ];
        });

        return response()->json($formattedTasks);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors(),
            ], 422);
        }

        try {
            Task::create([
                'task' => $request->task,
                'description' => $request->description,
                'user_id' => $request->user_id,
                'link' => $request->link,
                'is_completed' => $request->user_id,

            ]);
            return response()->json(['status', 'Task create  successfully'], 200);
        } catch (Exception $e) {

            return response()->json([
                "message" => "Unable to create task"
            ], 400);
        }
    }
    public function show($searchId, $userId)
    {
        $searchedTasks = Task::where('task','id', 'like', '%' . $searchId . '%')
            ->where('user_id', $userId)
            ->get();

        if ($searchedTasks->isEmpty()) {
            return response()->json(['error' => 'No matching tasks found'], 404);
        }

        return response()->json($searchedTasks);
    }




    public function update(Request $request, $id)
    {
        $input = $request->all();

        $Task = Task::findOrFail($id);
        $Task->task = $input['task'];
        $Task->description = $input['description'];
        $Task->link = $input['link'];
        $Task->is_completed = $input['is_completed'];

        // $Task = $Task->update($request->all());
        $Task->save();
        return response()->json($Task, 200);
    }

    public function destroy($id)
    {
        //cors policy
        // header('Access-Control-Allow-Origin:*');
        $Task = Task::findOrFail($id);
        $Task->delete();
        return response()->json($Task, 200);
    }
}
