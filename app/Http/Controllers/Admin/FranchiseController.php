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
<<<<<<< HEAD
        $request->validate([
=======
        $data = $request->validate([
>>>>>>> master
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
<<<<<<< HEAD
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

=======
            'status' => 'required|in:Pending,Approved,Reject',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Hash password
        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = auth()->id();

        // Always verified on creation
        $data['is_verified'] = 'true';

        // Generate sequential franchise code
        $lastFranchise = Franchise::orderBy('id', 'desc')->first();
        if ($lastFranchise && $lastFranchise->code) {
            $lastNumber = (int) filter_var($lastFranchise->code, FILTER_SANITIZE_NUMBER_INT);
            $newNumber  = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $data['code'] = 'FRANCODE' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Referral link = only code
        $data['ref_link'] = $data['code'];

        // Image upload
>>>>>>> master
        if ($image = $request->file('image')) {
            $destinationPath = 'images/franchises/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
<<<<<<< HEAD
            $input['image'] = "/$destinationPath" . $profileImage;
        }

        Franchise::create($input);

        return redirect()->route('admin.franchises.index')
                         ->with('success', 'Franchise created successfully.');
=======
            $data['image'] = "/$destinationPath" . $profileImage;
        }

        Franchise::create($data);

        return redirect()->route('admin.franchises.index')
            ->with('success', 'Franchise created successfully.');
>>>>>>> master
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

<<<<<<< HEAD
=======
    // public function update(Request $request, $id): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'address' => 'required',
    //         'pincode' => 'required',
    //         'contact_no' => 'required',
    //         'email' => 'required|email|unique:franchises,email,' . $id,
    //         'contact_person_name' => 'required',
    //         'contact_person_number' => 'required',
    //         'store_lat' => 'required|numeric',
    //         'store_long' => 'required|numeric',
    //         'status' => 'boolean',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     $franchise = Franchise::findOrFail($id);
    //     $input = $request->all();

    //     if ($request->filled('password')) {
    //         $input['password'] = Hash::make($input['password']);
    //     } else {
    //         unset($input['password']);
    //     }

    //     $input['updated_by'] = auth()->id();

    //     if ($image = $request->file('image')) {
    //         $destinationPath = 'images/franchises/';
    //         $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
    //         $image->move($destinationPath, $profileImage);
    //         $input['image'] = "/$destinationPath" . $profileImage;
    //     } else {
    //         unset($input['image']);
    //     }

    //     $franchise->update($input);

    //     return redirect()->route('admin.franchises.index')
    //         ->with('success', 'Franchise updated successfully.');
    // }

>>>>>>> master
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
<<<<<<< HEAD
            'status' => 'boolean',
=======
            'status' => 'required|in:Pending,Approved,Reject',
>>>>>>> master
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $franchise = Franchise::findOrFail($id);
        $input = $request->all();

<<<<<<< HEAD
=======
        // Handle password update
>>>>>>> master
        if ($request->filled('password')) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $input['updated_by'] = auth()->id();

<<<<<<< HEAD
=======
        // Update is_verified based on status
        $input['is_verified'] = ($input['status'] === 'Approved') ? 'true' : 'false';

        // Image upload
>>>>>>> master
        if ($image = $request->file('image')) {
            $destinationPath = 'images/franchises/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "/$destinationPath" . $profileImage;
        } else {
            unset($input['image']);
        }

<<<<<<< HEAD
        $franchise->update($input);

        return redirect()->route('admin.franchises.index')
                         ->with('success', 'Franchise updated successfully.');
    }

=======
        // Keep ref_link unchanged
        unset($input['ref_link']);
        unset($input['code']); // Do not update the code

        $franchise->update($input);

        return redirect()->route('admin.franchises.index')
            ->with('success', 'Franchise updated successfully.');
    }


>>>>>>> master
    public function destroy($id): RedirectResponse
    {
        Franchise::findOrFail($id)->delete();

        return redirect()->route('admin.franchises.index')
<<<<<<< HEAD
                         ->with('success', 'Franchise deleted successfully.');
=======
            ->with('success', 'Franchise deleted successfully.');
>>>>>>> master
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
<<<<<<< HEAD
            'status' => 'requiredd|boolean',
=======
            'status' => 'required|in:Pending,Approved,Reject',
>>>>>>> master
        ]);

        $franchise = Franchise::findOrFail($id);
        $franchise->status = $request->input('status');
<<<<<<< HEAD
=======

        // Update is_verified based on status using ENUM strings
        $franchise->is_verified = ($franchise->status === 'Approved') ? 'true' : 'false';

>>>>>>> master
        $franchise->save();

        return response()->json(['message' => 'Franchise status updated successfully.']);
    }
}
