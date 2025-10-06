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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Customer::with('franchise')->orderBy('id', 'DESC');

            // Apply Franchise filter
            if ($request->franchise_id) {
                $query->where('franchise_id', $request->franchise_id);
            }

            // Apply Status filter
            if ($request->status) {
                $query->where('status', $request->status);
            }

            // Apply KYC Status filter
            if ($request->kyc_status) {
                $query->where('kyc_status', $request->kyc_status);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('franchise_name', fn($row) => $row->franchise ? $row->franchise->name : 'N/A')
                ->addColumn('customer_name', fn($row) => $row->fname . ' ' . $row->lname)
                ->editColumn('kyc_status', function ($row) {
                    $statusClass = match ($row->kyc_status) {
                        'Verified' => 'btn-success',
                        'Not Verified' => 'btn-warning',
                        'Rejected' => 'btn-danger',
                        default => 'btn-secondary',
                    };
                    return '<span class="btn btn-sm ' . $statusClass . '">' . $row->kyc_status . '</span>';
                })
                ->editColumn('status', function ($row) {
                    $statusClass = match ($row->status) {
                        'Approved' => 'btn-success',
                        'Pending' => 'btn-warning',
                        'Reject' => 'btn-danger',
                        default => 'btn-secondary',
                    };
                    return '<span class="btn btn-sm ' . $statusClass . '">' . $row->status . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('Customer Edit')) {
                        $btn .= '<a href="' . route('admin.customers.edit', $row->id) . '" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit"></i></a>';
                    }
                    if (auth()->user()->can('Customer Delete')) {
                        $btn .= '<form action="' . route('admin.customers.destroy', $row->id) . '" method="POST" style="display:inline;">'
                            . csrf_field() . method_field('DELETE') .
                            '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></form>';
                    }
                    if (auth()->user()->can('Customer View')) {
                        $btn .= '<a href="' . route('admin.customers.show', $row->id) . '" class="btn btn-sm btn-info me-1"><i class="fas fa-eye"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['franchise_name', 'customer_name', 'kyc_status', 'status', 'action'])
                ->make(true);
        }

        $franchises = Franchise::all();
        return view('admin.customers.index', compact('franchises'));
    }

    public function create(): View
    {
        $franchises = Franchise::where('status', 'Active')->get();
        return view('admin.customers.create', compact('franchises'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'franchise_id'   => 'nullable|exists:franchises,id',
            'fname'          => 'nullable|string|max:100',
            'lname'          => 'nullable|string|max:100',
            'email'          => 'nullable|email|unique:customers,email',
            'mobile_no'      => 'nullable|unique:customers,mobile_no',
            'password'       => 'nullable|min:6',
            'account_balance' => 'nullable|numeric',
            'account_name'   => 'nullable|string|max:255',
            'account_type'   => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_bank'   => 'nullable|string|max:255',
            'email_verfied'  => 'boolean',
            'mobile_verfied' => 'boolean',
        ]);

        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $franchise = Franchise::find($input['franchise_id'] ?? null);
        $input['account_verify'] = 1;
        $input['kyc_status']     = 'Not Verified';
        $input['status']         = 'Pending';
        $input['ref_by']        = $franchise ? $franchise->code : null;

        Customer::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully!'
        ]);
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
