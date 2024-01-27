<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::all();
        if ($todos) {
            return response()->json($todos, 200);
        } else {
            $data = [
                'success' => true,
                'message' => 'There is no data.'
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                "success" => false,
                "message" => $validator->errors(),
            ];
            return response()->json($data, 400);
        }

        Todo::create($request->all());

        $data = [
            "success" => true,
            "message" => "Success",
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'List Not Found!'], 404);
        }

        return response()->json($todo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = Todo::find($id);
        // return $request->all();xxx
        if (!$todo) {
            return response()->json(['message' => 'Not Found!', 404]);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'task' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                "success" => false,
                "message" => $validator->errors(),
            ];
            return response()->json($data, 400);
        }

        $todo->update($request->all());

        $data = [
            "success" => true,
            "message" => "Update Success",
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found!'], 404);
        }

        $todo->delete();

        return response()->json([
            'message' => 'Delete success'
        ], 200);
    }
}
