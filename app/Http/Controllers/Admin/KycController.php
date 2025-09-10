<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;
use App\Models\Customer;
use DataTables;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KycController extends Controller
{
    function __construct()
    {
        // Permissions will be added here later
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kyc::orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function($row) {
                    return $row->customer ? $row->customer->fname . ' ' . $row->customer->lname : 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $statusText = match ((int)$row->status) {
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Rejected',
                    };
                    $statusClass = match ((int)$row->status) {
                        0 => 'btn-warning',
                        1 => 'btn-success',
                        2 => 'btn-danger',
                    };
                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })
               
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $editUrl = route('admin.kycs.edit', $row->id);
                    $deleteUrl = route('admin.kycs.destroy', $row->id);
                    $showUrl = route('admin.kycs.show', $row->id);

                    // Permissions to be added here later
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="fas fa-edit fw-bold"></i></a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline me-1">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure to delete this KYC entry?\')" title="Delete"><i class="fas fa-trash-alt fw-bold"></i></button></form>';
                    $btn .= '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye fw-bold"></i></a>';

                    return $btn;
                })
                ->rawColumns(['customer_name', 'status', 'action'])
                ->make(true);
        }

        return view('admin.kycs.index');
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
