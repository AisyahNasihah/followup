<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActionRequest;
use App\Http\Resources\ActionResource;
use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActionRequest $request)
    {
        // if (auth()->user() == null) {
        //     return response()->json('User not authenticated', 200);
        // }

        $action = DB::transaction(function () use ($request) {
            $action = Action::create([
                'type' => $request->type,
                'medium' => $request->medium,
                'content' => $request->content
            ]);
            
            return $action;
        });

        $action = Action::whereId($action->id)->first();
        return new ActionResource($action);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActionRequest $request, Action $action)
    {
        $data = $request->all();
        $action->update($data);
        $action->save();

        return new ActionResource($action);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Action $action)
    {
        $action->update(['active' => 0]);
        return new ActionResource($action);
    }
}
