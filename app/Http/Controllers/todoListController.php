<?php

namespace App\Http\Controllers;

use App\todoList;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class todoListController extends Controller
{
    public function getAllList() {
        return todoList::all();
    }

    public function index() {
        $myList = $this->getAllList();
        return view('todoListApp.index')
            ->with(["myList" => $myList]);
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(),[
            'todoName' => 'required|max:20|min:3'
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500, 'errorMessages'=>$validator->errors()]);
        } else {
            todoList::create($request->all());
            $myList = $this->getAllList();
            return response()->json(['status'=>200, 'list'=>$myList]);
        }
    }

    public function delete(Request $request) {
        try {
            $deleteThis = todoList::where('id', $request->input('id'));
            if($deleteThis->delete()) {
                $myList = $this->getAllList();
                return response()->json(['status'=>200, 'list'=>$myList]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['status'=>500]);
        }
    }

    public function edit($id) {
        $myList = todoList::where('id', $id)->get();
        dd($myList);
    }
}
