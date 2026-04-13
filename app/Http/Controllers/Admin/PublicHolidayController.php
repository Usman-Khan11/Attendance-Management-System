<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublicHoilday;
use Yajra\DataTables\Facades\DataTables;

class PublicHolidayController extends Controller
{
    public function index(Request $request)
    {
        $data['page_title'] = "Public Holidays";

        if ($request->ajax()) {
            $query = PublicHoilday::orderBy('date', 'DESC');

            return DataTables::of($query)
                ->addColumn('date', function ($row) {
                    return showDate($row->date);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="' . route('admin.public_holiday.edit', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>';
                    $btn .= '<a href="' . route('admin.public_holiday.delete', $row->id) . '" class="btn btn-sm btn-danger" onclick="return checkDelete()">Delete</a>';

                    return "<div class='btn-group' role='group'>$btn</div>";
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.public_holiday.index', $data);
    }

    public function create()
    {
        $data['page_title'] = "Add New Public Holiday";
        return view('admin.public_holiday.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Public Holiday";
        $data['public_holiday'] = PublicHoilday::where("id", $id)->first();
        return view('admin.public_holiday.edit', $data);
    }

    public function delete($id)
    {
        PublicHoilday::where("id", $id)->delete();
        return back()->withSuccess('Public Hoilday deleted successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $public_holiday = new PublicHoilday();
        $public_holiday->name = $request->name;
        $public_holiday->date = $request->date;

        if ($public_holiday->save()) {
            return redirect()->route('admin.public_holiday')->withSuccess('Public Hoilday added successfully.');
        }

        return back()->withError('Something went wrong.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'date' => 'required|date',
        ]);

        $public_holiday = PublicHoilday::find($request->id);
        $public_holiday->name = $request->name;
        $public_holiday->date = $request->date;

        if ($public_holiday->save()) {
            return redirect()->route('admin.public_holiday')->withSuccess('Public Hoilday updated successfully.');
        }

        return back()->withError('Something went wrong.');
    }
}
