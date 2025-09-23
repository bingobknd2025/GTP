<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Franchise;
use DataTables;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FranchiseController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Franchise List|Franchise Add|Franchise Edit|Franchise Delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:Franchise Add', ['only' => ['create', 'store']]);
        $this->middleware('permission:Franchise Edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Franchise Delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Franchise::orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.franchises.edit', $row->id);
                    $deleteUrl = route('admin.franchises.destroy', $row->id);
                    $showUrl = route('admin.franchises.show', $row->id);

                    $btn = '';

                    if (auth()->user()->can('Franchise Edit')) {
                        $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>';
                    }

                    if (auth()->user()->can('Franchise Delete')) {
                        $btn .= '<form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure to delete this franchise?\')" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>';
                    }

                    if (auth()->user()->can('Franchise View')) {
                        $btn .= '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.franchises.index');
    }

    public function create(): View
    {
        return view('admin.franchises.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'pincode' => 'required',
            'contact_no' => 'required',
            'email' => 'required|email|unique:franchises,email',
            'password' => 'required|min:6',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'store_lat' => 'required|numeric',
            'store_long' => 'required|numeric',
            'status' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        // Generate unique code
        do {
            $code = strtoupper(Str::random(10)); // Generate a random string of 10 characters and convert to uppercase
        } while (Franchise::where('code', $code)->exists());
        $input['code'] = $code;

        $input['password'] = Hash::make($input['password']);
        $input['created_by'] = auth()->id();

        if ($image = $request->file('image')) {
            $destinationPath = 'images/franchises/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "/$destinationPath" . $profileImage;
        }

        Franchise::create($input);

        return redirect()->route('admin.franchises.index')
            ->with('success', 'Franchise created successfully.');
    }

    public function show($id): View
    {
        $franchise = Franchise::findOrFail($id);
        return view('admin.franchises.show', compact('franchise'));
    }

    public function edit($id): View
    {
        $franchise = Franchise::findOrFail($id);
        return view('admin.franchises.edit', compact('franchise'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'pincode' => 'required',
            'contact_no' => 'required',
            'email' => 'required|email|unique:franchises,email,' . $id,
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'store_lat' => 'required|numeric',
            'store_long' => 'required|numeric',
            'status' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $franchise = Franchise::findOrFail($id);
        $input = $request->all();

        if ($request->filled('password')) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $input['updated_by'] = auth()->id();

        if ($image = $request->file('image')) {
            $destinationPath = 'images/franchises/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "/$destinationPath" . $profileImage;
        } else {
            unset($input['image']);
        }

        $franchise->update($input);

        return redirect()->route('admin.franchises.index')
            ->with('success', 'Franchise updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        Franchise::findOrFail($id)->delete();

        return redirect()->route('admin.franchises.index')
            ->with('success', 'Franchise deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'requiredd|boolean',
        ]);

        $franchise = Franchise::findOrFail($id);
        $franchise->status = $request->input('status');
        $franchise->save();

        return response()->json(['message' => 'Franchise status updated successfully.']);
    }
}
