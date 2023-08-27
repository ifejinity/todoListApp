<?php

namespace App\Http\Controllers;

use App\todoList;
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
            'todoName' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500, 'errorMessages'=>$validator->errors()]);
        } else {
            $myList = todoList::create($request->all());
            return response()->json(['status'=>200, 'list'=>$myList]);
        }
    }
}
