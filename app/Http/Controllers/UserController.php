<?php

namespace App\Http\Controllers;

use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = User::orderByRaw('id = ? DESC', [Auth::user()->id])
            ->orderBy('id', 'DESC');
        $pagination = pagination($request, $data);
        $data = $data->skip($pagination['offset'])->take($pagination['limit'])->get();
        $pagination = pageInfo($pagination, $data->count());
        return response()->json(compact('data', 'pagination'));
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
            'name' => 'required|unique:tbl_users,name',
            'email' => 'required|unique:tbl_users,email',
            'password' => 'required|confirmed',
        ]);
        try {
            User::create([
                'name' => $validation['name'],
                'email' => $validation['email'],
                'password' => Hash::make($validation['password']),
            ]);
            return response('success');
        } catch (\Exception $e) {
            throw new Error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
