<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Franchise;
use DataTables;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    function __construct()
    {
        // Permissions will be added here later
    }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = Customer::orderBy('id', 'DESC')->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('franchise_name', function ($row) {
    //                 return $row->franchise ? $row->franchise->name : 'N/A';
    //             })
    //             ->addColumn('status', function ($row) {
    //                 $statusText = $row->status ? 'Active' : 'Inactive';
    //                 $statusClass = $row->status ? 'btn-success' : 'btn-danger';
    //                 return '<span class="btn btn-sm ' . $statusClass . '">' . $statusText . '</span>';
    //             })
    //             ->addColumn('action', function ($row) {
    //                 $btn = '';
    //                 $editUrl = route('admin.customers.edit', $row->id);
    //                 $deleteUrl = route('admin.customers.destroy', $row->id);
    //                 $showUrl = route('admin.customers.show', $row->id);

    //                 // Permissions to be added here later
    //                 $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="fas fa-edit"></i></a>';
    //                 $btn .= '<form action="' . $deleteUrl . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure to delete this customer?\')" title="Delete"><i class="fas fa-trash-alt"></i></button></form>';
    //                 $btn .= '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye"></i></a>';

    //                 return $btn;
    //             })
    //             ->rawColumns(['franchise_name', 'status', 'action'])
    //             ->make(true);
    //     }

    //     return view('admin.customers.index');
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::with('franchise')->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // Franchise Name
                ->addColumn('franchise_name', function ($row) {
                    return $row->franchise ? $row->franchise->name : 'N/A';
                })

                // Full Name
                ->addColumn('customer_name', function ($row) {
                    return $row->fname . ' ' . $row->lname;
                })

                ->editColumn('kyc_status', function ($row) {
                    $status = $row->kyc_status;

                    $statusClass = match ($status) {
                        'Verified'     => 'btn-success',
                        'Not Verified' => 'btn-warning',
                        'Rejected'     => 'btn-danger',
                        default        => 'btn-secondary',
                    };

                    return '<span class="btn btn-sm ' . $statusClass . '">' . $status . '</span>';
                })

                ->editColumn('status', function ($row) {
                    $status = $row->status;

                    $statusClass = match ($status) {
                        'Approved' => 'btn-success',
                        'Pending'  => 'btn-warning',
                        'Reject'   => 'btn-danger',
                        default    => 'btn-secondary',
                    };

                    return '<span class="btn btn-sm ' . $statusClass . '">' . $status . '</span>';
                })

                // Action Buttons
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (auth()->user()->can('Customer Edit')) {
                        $btn .= '<a href="' . route('admin.customers.edit', $row->id) . '" 
                        class="btn btn-sm btn-primary me-1" title="Edit">
                        <i class="fas fa-edit"></i></a>';
                    }

                    if (auth()->user()->can('Customer Delete')) {
                        $btn .= '<form action="' . route('admin.customers.destroy', $row->id) . '" 
                        method="POST" style="display:inline;" class="delete-customer-form">'
                            . csrf_field() . method_field('DELETE') .
                            '<button type="submit" class="btn btn-sm btn-danger" 
                        title="Delete"><i class="fas fa-trash-alt"></i></button></form>';
                    }

                    if (auth()->user()->can('Customer View')) {
                        $btn .= '<a href="' . route('admin.customers.show', $row->id) . '" 
                        class="btn btn-sm btn-info me-1" title="View">
                        <i class="fas fa-eye"></i></a>';
                    }

                    return $btn;
                })

                ->rawColumns(['franchise_name', 'customer_name', 'kyc_status', 'status', 'action'])
                ->make(true);
        }

        return view('admin.customers.index');
    }

    public function create(): View
    {
        $franchises = Franchise::all();
        return view('admin.customers.create', compact('franchises'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'franchise_id' => 'nullable|exists:franchises,id',
            'fname' => 'nullable',
            'lname' => 'nullable',
            'email' => 'nullable|email|unique:customers,email',
            'mobile_no' => 'nullable|unique:customers,mobile_no',
            'password' => 'nullable|min:6',
            'account_balance' => 'nullable|numeric',
            'account_name' => 'nullable',
            'account_type' => 'nullable',
            'account_number' => 'nullable',
            'account_bank' => 'nullable',
            'status' => 'boolean',
            'email_verfied' => 'boolean',
            'mobile_verfied' => 'boolean',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        Customer::create($input);

        return response()->json(['success' => true, 'message' => 'Customer created successfully!']);
    }

    public function show($id): View
    {
        $customer = Customer::with('franchise')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit($id): View
    {
        $customer = Customer::findOrFail($id);
        $franchises = Franchise::all();
        return view('admin.customers.edit', compact('customer', 'franchises'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'franchise_id' => 'nullable|exists:franchises,id',
            'fname' => 'nullable',
            'lname' => 'nullable',
            'email' => 'nullable|email|unique:customers,email,' . $id,
            'mobile_no' => 'nullable|unique:customers,mobile_no,' . $id,
            'password' => 'nullable|min:6',
            'account_balance' => 'nullable|numeric',
            'account_name' => 'nullable',
            'account_type' => 'nullable',
            'account_number' => 'nullable',
            'account_bank' => 'nullable',
            'status' => 'boolean',
            'email_verfied' => 'boolean',
            'mobile_verfied' => 'boolean',
        ]);

        $customer = Customer::findOrFail($id);
        $input = $request->all();

        if ($request->filled('password')) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $customer->update($input);

        return response()->json(['success' => true, 'message' => 'Customer updated successfully!']);
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
