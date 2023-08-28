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
            'todoName' => 'required|max:30|min:3'
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500, 'errorMessages'=>$validator->errors()]);
        } else {
            $myList = todoList::create($request->all());
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

    public function getEdit(Request $request) {
        try {
            $myList = todoList::where('id', $request->input('id'))->get();
            return response()->json(['status'=>200, 'list'=>$myList]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status'=>500]);
        }
    }

    public function save(Request $request) {
        try{
            $validator = Validator::make($request->all(),[
                'todoName' => 'required|max:30|min:3'
            ]);
            if($validator->fails()) {
                return response()->json(['status'=>500, 'erroMessages'=>$validator->errors()]);
            } else {
                $id = $request->input('id');
                $todoName = $request->input('todoName');
                $toSave = todoList::findOrFail($id);
                $toSave->update(['todoName' => $todoName]);
                $myList = $this->getAllList();
                return response()->json(['status'=>200, 'list'=>$myList]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['status'=>500]);
        }  
    }
}
