<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $lists = TodoList::all();
        return response($lists);
    }

    public function show(TodoList $list)
    {
        return response($list);
        // $list = TodoList::findOrFail($todolist);
        // return response($list);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        return TodoList::create($request->all());
        // return response($list, Response::HTTP_CREATED);
    }

    public function destroy(TodoList $list)
    {
        $list->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(TodoList $list, Request $request)
    {
        $request->validate(['name' => 'required']);
        $list->update($request->all());
        return response($list);
    }
}
