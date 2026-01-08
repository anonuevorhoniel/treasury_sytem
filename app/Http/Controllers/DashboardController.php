<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [];
        $year = $request->year;
        foreach (months()  as $month => $value) {
            // $data[$month] = $
            $payableCount = Payable::whereMonth('date', $value)
                ->when($year, fn($query) => $query->whereYear('date', $year))
                ->when(!$year, fn($query) => $query->whereYear('date', date("Y")))
                ->count();
            $data[$month] = $payableCount;
        }
        $overall = Payable::count();
        $accountPayableCount = Payable::where('type', "Accounts Payable")->count();
        $nonAccountPayableCount = Payable::where('type', "Non-Accounts Payable")->count();
        return response()->json(compact('data', 'overall', 'accountPayableCount', 'nonAccountPayableCount'));
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
        //
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
