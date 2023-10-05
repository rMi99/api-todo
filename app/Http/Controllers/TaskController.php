<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        //cors policy
        header('Access-Control-Allow-Origin:*');
        // $tasks = Task::where('user_id', $id)->get();
        // $tasks = Task::where('id', 'LIKE', '%' . $id . '%')->get();
        $tasks = Task::all();
        return response()->json($tasks);
    }
    public function task($id)
    {
        // $id=1;
        //cors policy
        // header('Access-Control-Allow-Origin:*');
        $tasks = Task::where('user_id', $id)->get();
        // $tasks = Task::where('id', 'LIKE', '%' . $id . '%')->get();

        return response()->json($tasks);

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

            ]);
            return response()->json(['status', 'Task create  successfully'], 200);
        } catch (Exception $e) {

            return response()->json([
                "message" => "Unable to create task"
            ], 400);
        }
    }

    public function show($id)
    {
        //cors policy
        // header('Access-Control-Allow-Origin:*');
        $Task = Task::findOrFail($id);
        return response()->json($Task);
    }

    public function update(Request $request, $id)
    {
        //cors policy
        // header('Access-Control-Allow-Origin:*');
        $input = $request->all();

        $Task = Task::findOrFail($id);
        $Task->task = $input['task'];
        $Task->description = $input['description'];
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
