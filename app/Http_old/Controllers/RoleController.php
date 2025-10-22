<?php
    
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DataTables;

    
class RoleController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    
public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Role::orderBy('id', 'DESC')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = route('roles.edit', $row->id);
                $deleteUrl = route('roles.destroy', $row->id);

                $btn  = '<a href="'.$editUrl.'" class="btn btn-sm btn-primary me-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
                $btn .= '<form action="'.$deleteUrl.'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure to delete this role?\')" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                         </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('admin.roles.index');
}

    
    public function create()
    {
        $permissions = Permission::all();

        $permissionGroups = [];
        foreach ($permissions as $permission) {
            $groupName = $permission->group_name;
            $permissionGroups[$groupName][] = $permission;
        }

        return view('admin.roles.create', compact('permissionGroups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
        ]);

        $permissionsID = array_map(
            function ($value) {
                return (int) $value;
            },
            $request->input('permissions', [])
        );

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionsID);

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }


public function show($id)
{
    $role = Role::findOrFail($id);
    $permissions = $role->permissions;

    $groupedPermissions = [];

    foreach ($permissions as $permission) {
        $groupName = $permission->group_name ?? 'Other';
        $groupedPermissions[$groupName][] = $permission;
    }

    return view('admin.roles.show', compact('role', 'groupedPermissions'));
}


    
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        $permissionGroups = [];
        foreach ($permissions as $permission) {
            $groupName = $permission->group_name;
            $permissionGroups[$groupName][] = $permission;
        }

        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $id)
            ->pluck('permission_id')
            ->toArray();  // Array of permission IDs for the role

        return view('admin.roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    

    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required|array',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();

        $permissionsID = array_map('intval', $request->input('permissions'));
        $role->syncPermissions($permissionsID);

        return redirect()->route('roles.index')
                         ->with('success', 'Role updated successfully');
    }


    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}