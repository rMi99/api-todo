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
        $Task = Task::all();
        return response()->json($Task);
    }

    public function store(Request $request)
    {
        //cors policy
        // header('Access-Control-Allow-Origin:*');
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
