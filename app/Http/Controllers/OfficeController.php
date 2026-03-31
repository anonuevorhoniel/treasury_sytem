<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Error;
use Exception;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $data = Office::when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        });
        $page = $request->page;
        if ($page) {
            $pagination = pagination($request, $data);
            $data = $data->skip($pagination["offset"])->take($pagination["limit"])->get();
            $pagination = pageInfo($pagination, $data->count());
            return response()->json(compact('data', 'pagination'));
        } else {
            $data = $data->get();
            return response($data);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required'
        ]);

        try {
            Office::create($validation);
            return response()->json('success');
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Office $office)
    {
        return response()->json($office);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Office $office)
    {
        $validation = $request->validate([
            'name' => 'required'
        ]);

        try {
            $office->update($validation);
            return response()->json('success');
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Office $office)
    {
        //
    }
}
