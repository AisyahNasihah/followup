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
    public function index(Request $request)
    {
        if (auth()->user() == null) {
            return response()->json('User not authenticated', 200);
        }

        $action = Action::with('type', 'course')->where('active', 1)->where(function ($query) use ($request) {
            if (isset($request->when)) {

                $query->where('when', '=', $request->when);
            }
        })->get();

        return ActionResource::collection($action);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActionRequest $request)
    {
        if (auth()->user() == null) {
            return response()->json('User not authenticated', 200);
        }

        $action = DB::transaction(function () use ($request) {
            $action = Action::create([
                'comment' => $request->comment,
                'when' => $request->when,
                'time' => $request->time,
                'type_id' => $request->type,
                'course_id' => $request->course,
                'client_id' => $request->client,
            ]);

            return $action;
        });

        $action = Action::with('type', 'course')->whereId($action->id)->first();
        return new ActionResource($action);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($client_id)
    {
        if (auth()->user() == null) {
            return response()->json('User not authenticated', 200);
        }

        $action = Action::with('type', 'course')->where('client_id', $client_id)->where('active', 1)->paginate(perPage: request('per_page'), page: request('page'));
        return ActionResource::collection($action);
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
        if (auth()->user() == null) {
            return response()->json('User not authenticated', 200);
        }

        $action->update([
            'comment' => $request->comment,
            'when' => $request->when,
            'time' => $request->time,
            'type_id' => $request->type,
            'course_id' => $request->course,
            'client_id' => $request->client,
        ]);

        $action = Action::with('type', 'course')->whereId($action->id)->first();
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
