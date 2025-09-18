<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DataTables;

class PermissionController extends Controller
{

    function __construct() {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('group_name', function ($row) {
                    // Agar aapke Permission model me "group_name" column hai
                    return $row->group_name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.permissions.edit', $row->id);
                    $deleteUrl = route('admin.permissions.destroy', $row->id);

                    $btn  = '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure to delete this Permission?\')" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                         </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.permissions.index');
    }



    public function create()
    {
        $permissions = Permission::all();
        return view('admin.permissions.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'guard_name' => 'required|string',
            'group_name' => 'required|string|max:100',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'group_name' => $request->group_name,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully');
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
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|unique:permissions,name,' . $id,
            'guard_name' => 'required|string',
            'group_name' => 'nullable|string|max:100',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name'       => $request->name,
            'guard_name' => $request->guard_name,
            'group_name' => $request->group_name,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Role deleted successfully');
    }
}
