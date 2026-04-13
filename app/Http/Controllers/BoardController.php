<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $data['page_title'] = "Boards";

        if ($request->ajax()) {
            $query = Board::with('users')->orderBy('id', 'DESC');

            return DataTables::of($query)
                ->addColumn('members', function ($row) {
                    return $row->users->count() ?? 0;
                })
                ->addColumn('date', function ($row) {
                    return showDate($row->created_at);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="' . route('admin.board.view', $row->id) . '" class="btn btn-sm btn-info">View</a>';
                    $btn .= '<a href="' . route('admin.board.edit', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>';
                    $btn .= '<a href="' . route('admin.board.delete', $row->id) . '" class="btn btn-sm btn-danger" onclick="return checkDelete()">Delete</a>';

                    return "<div class='btn-group' role='group'>$btn</div>";
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.board.index', $data);
    }

    public function create()
    {
        $data['page_title'] = "Add New Board";
        $data['users'] = User::where('status', 1)->orderBy('name')->get();
        return view('admin.board.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Board";
        $data['board'] = Board::where("id", $id)->firstOrFail();
        $data['users'] = User::where('status', 1)->orderBy('name')->get();
        return view('admin.board.edit', $data);
    }

    public function view($id, Request $request)
    {
        if ($request->ajax()) {
            $type = $request->type ?? '';

            switch ($type) {
                case 'update_list_position':
                    $this->update_list_position($request);
                    break;
                default:
                    break;
            }
        }

        $data['page_title'] = "View Board";
        $data['board'] = Board::with(['users', 'lists'])->where("id", $id)->firstOrFail();
        return view('board.view', $data);
    }

    public function delete($id)
    {
        $board = Board::findOrFail($id);
        $board->users()->detach();
        $board->delete();
        return back()->withSuccess('Board deleted successfully.');
    }

    private function board_validation($request)
    {
        $request->validate([
            'name'    => 'required|string|max:150',
            'users'   => 'required|array|min:1',
            'users.*' => 'required|exists:users,id'
        ]);
    }

    public function store(Request $request)
    {
        $this->board_validation($request);

        $board = Board::create([
            'name' => $request->name,
            'created_by_id' => auth()->guard('admin')->id()
        ]);

        // attach users
        $board->users()->attach($request->users);

        return redirect()->route('admin.board')->withSuccess('Board added successfully.');
    }

    public function update(Request $request)
    {
        $this->board_validation($request);

        $board = Board::find($request->id);
        $board->update([
            'name' => $request->name
        ]);

        $board->users()->sync($request->users);

        return redirect()->route('admin.board')->withSuccess('Board updated successfully.');
    }

    public function add_list($id, Request $request)
    {
        $request->validate([
            'list_id' => 'nullable|exists:task_lists,id',
            'name'    => 'required|string|max:150'
        ]);

        $board = Board::findOrFail($id);
        $position = $board->lists->count() ?? 0;

        $list = TaskList::updateOrCreate(
            ['id' => $request->list_id],
            [
                'name'     => $request->name,
                'board_id' => $board->id,
                'position' => $position
            ]
        );

        return response()->json([
            'success' => 1,
            'message' => $request->id ? 'List updated successfully.' : 'List added successfully.',
            'data'    => $list
        ]);
    }

    public function update_list_position($request)
    {
        if ($request->data) {
            foreach ($request->data as $item) {
                TaskList::where('id', $item['id'])->update([
                    'position' => $item['position']
                ]);
            }
        }

        return response()->json([
            'success' => 1,
            'message' => 'List position updated successfully.'
        ]);
    }
}
