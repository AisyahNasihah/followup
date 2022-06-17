<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {

        $advance_qry = $request->search;
        $requestData = ['company', 'name', 'phone_number', 'email'];
        $client = Client::with('category')
            ->where(function ($q) use ($requestData, $advance_qry) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$advance_qry}%");
            })->get();

        return ClientResource::collection($client);
    }
}
