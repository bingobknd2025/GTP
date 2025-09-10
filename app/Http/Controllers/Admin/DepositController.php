<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;
use App\Models\Customer;
use App\Models\Deposit;
use DataTables;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    function __construct()
    {
        // Permissions will be added here later
    }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = Deposit::orderBy('id', 'DESC')->get();
    //         return DataTables::of($data)
    //             ->addIndexColumn()

    //             // Deposit ID
    //             ->addColumn('id', fn($row) => $row->id)

    //             // Transaction ID
    //             ->addColumn('tnx_id', fn($row) => $row->transaction_id ?? 'N/A')

    //             // User
    //             ->addColumn('user', function ($row) {
    //                 return $row->user ?? 'N/A';
    //             })

    //             // Amount
    //             ->addColumn('amount', fn($row) => number_format($row->amount, 2))

    //             // Payment Method
    //             ->addColumn('payment_mode', fn($row) => $row->payment_mode ?? 'N/A')

    //             // Plan
    //             ->addColumn('plan', fn($row) => $row->plan->name ?? 'N/A')

    //             // Reference Number
    //             ->addColumn('reference_number', fn($row) => $row->reference_number ?? 'N/A')

    //             // Source
    //             ->addColumn('source', fn($row) => $row->source ?? 'N/A')

    //             // Created At
    //             ->addColumn('created_at', fn($row) => $row->created_at->format('Y-m-d H:i:s'))

    //             // Updated At
    //             ->addColumn('updated_at', fn($row) => $row->updated_at->format('Y-m-d H:i:s'))

    //             // Status with badge
    //             ->addColumn('status', function ($row) {
    //                 $statusText = match ((int)$row->status) {
    //                     0 => 'Pending',
    //                     1 => 'Approved',
    //                     2 => 'Rejected',
    //                     default => 'Unknown',
    //                 };
    //                 $statusClass = match ((int)$row->status) {
    //                     0 => 'bg-warning',
    //                     1 => 'bg-success',
    //                     2 => 'bg-danger',
    //                     default => 'bg-secondary',
    //                 };
    //                 return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
    //             })

    //             // Action Buttons
    //             ->addColumn('action', function ($row) {
    //                 $editUrl = route('admin.deposits.edit', $row->id);
    //                 $deleteUrl = route('admin.deposits.destroy', $row->id);
    //                 $showUrl = route('admin.deposits.show', $row->id);

    //                 $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye fw-bold"></i></a>';
    //                 $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="fas fa-edit fw-bold"></i></a>';
    //                 $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline">'
    //                     . csrf_field() . method_field('DELETE')
    //                     . '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')" title="Delete"><i class="fas fa-trash-alt fw-bold"></i></button></form>';

    //                 return $btn;
    //             })

    //             ->rawColumns(['status', 'action'])
    //             ->make(true);
    //     }

    //     return view('admin.deposits.index');
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Deposit::orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('id', fn($row) => $row->id)

                // Use txn_id from DB (not trnx_id)
                ->addColumn('txn_id', fn($row) => $row->txn_id ?? 'N/A')

                // Just return the user ID for now (later you can join with users table)
                ->addColumn('user', fn($row) => $row->user ?? 'N/A')

                ->addColumn('amount', fn($row) => number_format($row->amount, 2))

                ->addColumn('payment_mode', fn($row) => $row->payment_mode ?? 'N/A')

                // Just return plan id for now (later you can join with plans table)
                ->addColumn('plan', fn($row) => $row->plan ?? 'N/A')

                ->addColumn('reference_number', fn($row) => $row->reference_number ?? 'N/A')

                ->addColumn('source', fn($row) => $row->source ?? 'N/A')

                ->addColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d H:i:s'))

                ->addColumn('updated_at', fn($row) => $row->updated_at?->format('Y-m-d H:i:s'))

                ->addColumn('status', function ($row) {
                    $statusText = match ((int)$row->status) {
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Rejected',
                        default => 'Unknown',
                    };
                    $statusClass = match ((int)$row->status) {
                        0 => 'bg-warning',
                        1 => 'bg-success',
                        2 => 'bg-danger',
                        default => 'bg-secondary',
                    };
                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })

                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.deposits.edit', $row->id);
                    $deleteUrl = route('admin.deposits.destroy', $row->id);
                    $showUrl = route('admin.deposits.show', $row->id);

                    $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1"><i class="fas fa-eye fw-bold"></i></a>';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit fw-bold"></i></a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash-alt fw-bold"></i></button></form>';

                    return $btn;
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.deposits.index');
    }



    public function create(): View
    {
        $customers = Customer::all();
        return view('admin.kycs.create', compact('customers'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_code' => 'required',
            'phone_number' => 'required',
            'dob' => 'required|date',
            'social_media' => 'required|url',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'document_type' => 'required',
            'frontimg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'backimg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'integer|in:0,1,2',
            'kyc_type' => 'required|in:online,offline',
            'source' => 'required|in:APP,WEB',
        ]);

        $input = $request->all();

        if ($request->hasFile('frontimg')) {
            $file = $request->file('frontimg');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/kyc_front');
            $file->move($destinationPath, $fileName);
            $input['frontimg'] = 'kyc_front/' . $fileName;
        }
        if ($request->hasFile('backimg')) {
            $file = $request->file('backimg');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/kyc_back');
            $file->move($destinationPath, $fileName);
            $input['backimg'] = 'kyc_back/' . $fileName;
        }

        Kyc::create($input);

        return response()->json(['success' => true, 'message' => 'KYC entry created successfully!']);
    }

    public function show($id): View
    {
        $kyc = Kyc::with('customer')->findOrFail($id);
        return view('admin.kycs.show', compact('kyc'));
    }

    public function edit($id): View
    {
        $kyc = Kyc::findOrFail($id);
        $customers = Customer::all();
        return view('admin.kycs.edit', compact('kyc', 'customers'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_code' => 'required',
            'phone_number' => 'required',
            'dob' => 'required|date',
            'social_media' => 'required|url',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'document_type' => 'required',
            'frontimg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'backimg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'integer|in:0,1,2',
            'kyc_type' => 'required|in:online,offline',
            'source' => 'required|in:APP,WEB',
        ]);

        $kyc = Kyc::findOrFail($id);
        $input = $request->except(['_token', '_method']);

        if ($request->hasFile('frontimg')) {
            if ($kyc->frontimg) {
                Storage::disk('public')->delete($kyc->frontimg);
            }
            $file = $request->file('frontimg');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/kyc_front');
            $file->move($destinationPath, $fileName);
            $input['frontimg'] = 'kyc_front/' . $fileName;
        }
        if ($request->hasFile('backimg')) {
            if ($kyc->backimg) {
                Storage::disk('public')->delete($kyc->backimg);
            }
            $file = $request->file('backimg');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/kyc_back');
            $file->move($destinationPath, $fileName);
            $input['backimg'] = 'kyc_back/' . $fileName;
        }

        $kyc->update($input);

        return response()->json(['success' => true, 'message' => 'KYC entry updated successfully!']);
    }

    public function destroy($id): JsonResponse
    {
        $kyc = Kyc::findOrFail($id);
        if ($kyc->frontimg) {
            Storage::disk('public')->delete($kyc->frontimg);
        }
        if ($kyc->backimg) {
            Storage::disk('public')->delete($kyc->backimg);
        }
        $kyc->delete();

        return response()->json(['success' => true, 'message' => 'KYC entry deleted successfully!']);
    }
}
