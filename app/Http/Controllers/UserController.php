<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Zone;
use App\Models\Brand;
use App\Models\UserPincode;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{

public function index(Request $request)
{
        // dd($users->toArray()); 
    if ($request->ajax()) {
    $users = User::with(['zone_data', 'roles'])->where('is_deleted', 0)->get();
       
        return datatables()->of($users)
            ->addIndexColumn()
            ->editColumn('created_at', fn($row) =>
                \Carbon\Carbon::parse($row->created_at)->format('d-M-y')
            )
            ->addColumn('zone', fn($row) => $row->zone_data->zone_name)

           ->addColumn('brand_names', function ($row) {
                $names = explode(',', $row->brand_names ?? '');
                $trimmed = array_map('trim', $names);
                return implode(', ', $trimmed);
            })


            ->addColumn('role', fn($row) =>
                $row->roles->pluck('name')->implode(', ') ?? '-'
            )
           ->addColumn('action', function ($row) {
                $btn = '';
                
                if (auth()->user()->can('User Edit')) {
                    $editUrl = route('admin.users.edit', $row->id);
                    $btn .= '<a href="'.$editUrl.'" class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>';
                }

                if (auth()->user()->can('User View')) {
                    $viewUrl = route('admin.users.show', $row->id);
                    $btn .= '<a href="'.$viewUrl.'" class="btn btn-sm btn-info me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>';
                }

                if (auth()->user()->can('User Delete')) {
                    $deleteUrl = route('admin.users.destroy', $row->id);
                    $btn .= '<form action="'.$deleteUrl.'" method="POST" style="display:inline;">
                                '.csrf_field().method_field('DELETE').'
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                        onclick="return confirm(\'Are you sure to delete User?\')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>';
                }

                return $btn;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    return view('admin.users.index');
}

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        $zoneData = Zone::where('is_deleted', 0)->get();
        $brandData = Brand::where('is_deleted', 0)->get();
        return view('admin.users.create',compact('roles','zoneData','brandData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name',
            'mobile_no' => 'required|digits:10',
            'zone_id' => 'nullable|integer|exists:zones,id',
            'brand_ids.*' => 'integer|exists:brands,id',
        ]);

        $user = new User();
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->company_name = $request->company_name;
        $user->user_id = $request->user_id;
        $user->mobile_no = $request->mobile_no;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->gst_no = $request->gst_no;
        $user->pan_no = $request->pan_no;
        $user->pin_code = $request->pin_code;
        $user->zone_id = $request->zone_id;

        $user->brand_ids = $request->brand_ids ? json_encode($request->brand_ids) : null;

        $user->save();

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }


 
   public function show($id)
{
    $user = User::leftJoin('zones', 'zones.id', '=', 'users.zone_id')
                ->select('users.*', 'zones.zone_name as zoneName')
                ->where('users.id', $id)
                ->first();

    // Decode brand_ids JSON and get brand names
    $brandIds = json_decode($user->brand_ids ?? '[]', true);
    $brands = \App\Models\Brand::whereIn('id', $brandIds)->pluck('brand_name');

    return view('admin.users.show', compact('user', 'brands'));
}


    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $zoneData = Zone::where('is_deleted', 0)->get();
        $brandData = Brand::where('is_deleted', 0)->get();

        return view('admin.users.edit',compact('user','roles','userRole','zoneData','brandData'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'user_id'      => 'nullable|string|max:255',
            'email'        => 'required|email|unique:users,email,'.$id,
            'password'     => 'nullable|string|min:8|confirmed', // assuming you add password_confirmation field on form
            'roles'        => 'required|array',
            'mobile_no'    => ['required','digits:10','regex:/^\d{10}$/'],
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:255',
            'state'        => 'nullable|string|max:255',
            'gst_no'       => 'nullable|string|max:255',
            'pan_no'       => 'nullable|string|max:255',
            'pin_code'     => 'nullable|string|max:10',
            'zone_id'      => 'nullable|exists:zones,id',
            'brand_ids'    => 'nullable|array',
            'brand_ids.*'  => 'integer|exists:brands,id',
        ]);

        $user->name = $validated['first_name'] . ' ' . $validated['last_name'];
        $user->company_name = $validated['company_name'] ?? $user->company_name;
        $user->user_id = $validated['user_id'] ?? $user->user_id;
        $user->email = $validated['email'];
        $user->mobile_no = $validated['mobile_no'];
        $user->address = $validated['address'] ?? $user->address;
        $user->city = $validated['city'] ?? $user->city;
        $user->state = $validated['state'] ?? $user->state;
        $user->gst_no = $validated['gst_no'] ?? $user->gst_no;
        $user->pan_no = $validated['pan_no'] ?? $user->pan_no;
        $user->pin_code = $validated['pin_code'] ?? $user->pin_code;
        $user->zone_id = $validated['zone_id'] ?? $user->zone_id;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if (isset($validated['brand_ids'])) {
            $user->brand_ids = json_encode($validated['brand_ids']);
        } else {
            $user->brand_ids = json_encode([]); // clear if none selected
        }

        $user->save();

        $user->syncRoles($validated['roles']);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User updated successfully']);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }


    public function destroy($id)
    {
        $data = User::find($id);
        $data->is_deleted = 1;
        $data->save();
        return redirect()->route('admin.users.index')
                        ->with('success','User deleted successfully');

    }

    public function updatePassword($id){
        $userid = decrypt($id);
        return view('admin.users.updatePassword',compact('userid'));
    }

    public function passwordStore(Request $request)
    {
        $userdata = User::find($request->userid);
        if ($request->password !== $request->confirmPassword) {
        return redirect()->back()->with('error', 'Password do not match');
        }

        $user = User::find($request->userid);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('users.index')
                        ->with('success','Password Updated successfully');

    }

    public function import_excel_view()
    {
        return view('admin.users.import_excel');
    }

    public function import_excel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new UsersImport, $request->file('excel_file'));

        return redirect()->route('admin.users.index')->with('success', 'Users imported successfully.');
    }

    public function pincode_index($id)
{
    $userData = User::findOrFail($id);
    return view('admin.users.pincode_index', compact('userData'));
}

public function pincode_store(Request $request)
{
    $request->validate([
        'pincode' => 'required|numeric|digits:6',
        'user_id' => 'required|exists:users,id'
    ]);

    UserPincode::create([
        'user_id' => $request->user_id,
        'pincode' => $request->pincode
    ]);

    return response()->json(['message' => 'Pincode added successfully.']);
}

public function pincode_update(Request $request, $id)
{
    $request->validate([
        'pincode' => 'required|numeric|digits:6'
    ]);

    $pincode = UserPincode::findOrFail($id);
    $pincode->update(['pincode' => $request->pincode]);

    return response()->json(['message' => 'Pincode updated successfully.']);
}

public function pincode_list($id)
{
    $data = UserPincode::where('user_id', $id)->latest()->get();

    return datatables()->of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $deleteUrl = route('users.pincode_delete', $row->id);
            $csrf = csrf_field();
            $method = method_field('DELETE');

            return '
                <!-- <button class="btn btn-sm btn-info edit-btn" data-id="' . $row->id . '" data-name="' . $row->pincode . '">Edit</button> -->
                <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                    ' . $csrf . '
                    ' . $method . '
                    <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                        onclick="return confirm(\'Are you sure to delete this pincode?\')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>';
        })
        ->rawColumns(['action'])
        ->make(true);
}


public function pincode_delete($id)
{
    $pincode = UserPincode::findOrFail($id);
    $pincode->delete();

    return redirect()->back()->with('success', 'Pincode deleted successfully.');
}



}