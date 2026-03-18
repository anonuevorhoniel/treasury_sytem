<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PayableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $type = $request->type;
        $monthInput = $request->month;
        $data = Payable::searchAllFillable($search)
            ->when($type, fn($query) => $query->where('type', $type))
            ->when($monthInput, function ($query) use ($monthInput) {
                [$year, $month] = explode('-', $monthInput);
                $query->whereMonth('date', $month)
                    ->whereYear('date', $year);
            });
        $pagination = pagination($request, $data);
        $data = $data->skip($pagination["offset"])->take($pagination["limit"])->get();
        $pagination = pageInfo($pagination, $data->count());
        $data->map(function ($query) {
            $query->date = Carbon::parse($query->date)->format('F j, Y');
            return $query;
        });

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
    public function store(Request $request,)
    {
        $validation = $request->validate([
            'type' => "required",
            'dv_number' => "required",
            "check_number" => "required",
            "obr_number" => "required",
            "date" => "required",
            "particulars" => "required",
            "fund_type" => "required",
            "value" => "required",
            "deduction" => "required",
            "office_id" => "required"
        ]);

        try {
            Payable::create($validation);
            return response()->json('success');
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payable $payable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payable $payable)
    {
        return response()->json($payable);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payable $payable)
    {
        $validation = $request->validate([
            'type' => "required",
            'dv_number' => "required",
            "check_number" => "required",
            "obr_number" => "required",
            "date" => "required",
            "particulars" => "required",
            "fund_type" => "required",
            "value" => "required",
            "deduction" => "required",
            "office_id" => "required"
        ]);

        try {
            $payable->update($validation);
            return response()->json('success');
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payable $payable)
    {
        //
    }

    public function print(Request $request)
    {
        $validation =  $request->validate([
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $from_date = Carbon::parse($validation['from_date'])->startOfDay();
        $to_date = Carbon::parse($validation['to_date'])->endOfDay();
        $template = public_path('payable/Payable.xlsx');
        $dummyPath = public_path('payable/dummy.xlsx');
        $spreadsheet = IOFactory::load($template);
        $active = $spreadsheet->getActiveSheet();

        $row = 2;
        $data = Payable::whereBetween('date', [$from_date, $to_date])->get();

        foreach ($data as $payable) {
            $active->setCellValueExplicit("A$row", $payable->dv_number, DataType::TYPE_STRING);
            $active->setCellValueExplicit("B$row", $payable->obr_number, DataType::TYPE_STRING);
            $active->setCellValueExplicit("C$row", $payable->check_number, DataType::TYPE_STRING);
            $active->setCellValueExplicit("D$row", $payable->particulars, DataType::TYPE_STRING);
            $active->setCellValueExplicit("E$row", $payable->date, DataType::TYPE_STRING);
            $active->setCellValueExplicit("F$row", $payable->ps, DataType::TYPE_STRING);
            $active->setCellValueExplicit("G$row", $payable->ps_deduction, DataType::TYPE_STRING);
            if ($payable->ps && $payable->ps_deduction) {
                $active->setCellValueExplicit("H$row", $payable->ps - $payable->ps_deduction, DataType::TYPE_STRING);
            }
            $active->setCellValueExplicit("I$row", $payable->mooe, DataType::TYPE_STRING);
            $active->setCellValueExplicit("J$row", $payable->mooe_deduction, DataType::TYPE_STRING);
            $active->setCellValueExplicit("K$row", $payable->mooe - $payable->mooe_deduction, DataType::TYPE_STRING);
            $active->setCellValueExplicit("L$row", $payable->co, DataType::TYPE_STRING);
            $active->setCellValueExplicit("M$row", $payable->co_deduction, DataType::TYPE_STRING);
            $active->setCellValueExplicit("N$row", $payable->co - $payable->co_deduction, DataType::TYPE_STRING);

            $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($dummyPath);

        return response()->file($dummyPath, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Provincial_Report.xlsx"',
        ])->deleteFileAfterSend(true);
    }
}
