<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Category;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
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
    public function store(ClientRequest $request)
    {
        // if (auth()->user() == null) {
        //     return response()->json('User not authenticated', 200);
        // }

        $client = DB::transaction(function () use ($request) {
            $category = Category::updateOrCreate([
                'name' => $request->category,
            ]);

            $client = Client::create([
                'company' => $request->company,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'category_id' => $category->id,
            ]);

            return $client;
        });
        $client = Client::with('category')->whereId($client->id)->first();
        return new ClientResource($client);
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
    public function update(ClientRequest $request, Client $client)
    {
        $category = Category::updateOrCreate([
            'name' => $request->category
        ]);

        $client->update([
            'company' => $request->company,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'category_id' => $category->id
        ]);

        $client = Client::with('category')->whereId($client->id)->first();
        return new ClientResource($client);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->update(['active' => 0]);
        return new ClientResource($client);
    }
}
