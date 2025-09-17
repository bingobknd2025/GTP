<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;
use App\Models\Customer;
use App\Models\Setting;
use DataTables;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KycController extends Controller
{
    function __construct()
    {
        // Permissions will be added here later
    }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = Kyc::orderBy('id', 'DESC')->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('customer_name', function ($row) {
    //                 return $row->customer ? $row->customer->fname . ' ' . $row->customer->lname : 'N/A';
    //             })
    //             ->addColumn('status', function ($row) {
    //                 $statusText = match ((int)$row->status) {
    //                     0 => 'Pending',
    //                     1 => 'Approved',
    //                     2 => 'Rejected',
    //                 };
    //                 $statusClass = match ((int)$row->status) {
    //                     0 => 'btn-warning',
    //                     1 => 'btn-success',
    //                     2 => 'btn-danger',
    //                 };
    //                 return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
    //             })

    //             ->addColumn('action', function ($row) {
    //                 $btn = '';
    //                 $editUrl = route('admin.kycs.edit', $row->id);
    //                 $deleteUrl = route('admin.kycs.destroy', $row->id);
    //                 $showUrl = route('admin.kycs.show', $row->id);

    //                 // Permissions to be added here later
    //                 $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="fas fa-edit fw-bold"></i></a>';
    //                 $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline me-1">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure to delete this KYC entry?\')" title="Delete"><i class="fas fa-trash-alt fw-bold"></i></button></form>';
    //                 $btn .= '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye fw-bold"></i></a>';

    //                 return $btn;
    //             })
    //             ->rawColumns(['customer_name', 'status', 'action'])
    //             ->make(true);
    //     }

    //     return view('admin.kycs.index');
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kyc::orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($row) {
                    return $row->customer ? $row->customer->fname . ' ' . $row->customer->lname : 'N/A';
                })
                ->editColumn('status', function ($row) {
                    return $row->status; // ab string hoga: pending, approved, rejected
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $editUrl = route('admin.kycs.edit', $row->id);
                    $deleteUrl = route('admin.kycs.destroy', $row->id);
                    $showUrl = route('admin.kycs.show', $row->id);

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
        $customers = Customer::where('kyc_id', 0)
            ->orWhereNull('kyc_id')
            ->get();
        return view('admin.kycs.create', compact('customers'));
    }


    public function store(Request $request): JsonResponse
    {
        // dd($request->all());
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'country_code'   => 'required|string|max:10',
            'phone_number'   => 'required|string|max:20',
            'dob'            => 'required|date',
            'social_media'   => 'required|url|max:255',
            'address'        => 'required|string',
            'city'           => 'required|string|max:255',
            'state'          => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'address_proof_type' => 'required|in:Utility Bill,Rent Agreement,Bank Statement,Passport,Driving License,Voter ID',
            'address_proof_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'document_type'  => 'required|string|max:255',
            'frontimg'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'backimg'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'         => 'nullable|in:pending,approved,rejected',
            'identity_type'  => 'nullable|in:Aadhar,PAN,Passport,VoterID,DrivingLicense',
            'identity_number' => 'nullable|string|max:50',
            'identity_file'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'identity_status' => 'nullable|in:pending,approved,rejected',
            'kyc_type'       => 'required|in:online,offline',
            'source'         => 'required|in:APP,WEB',
        ]);

        $input = $request->all();


        // Upload front image
        if ($request->hasFile('frontimg')) {
            $file = $request->file('frontimg');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/kyc_front');
            $file->move($destinationPath, $fileName);
            $input['frontimg'] = 'kyc_front/' . $fileName;
        }

        // Upload back image
        if ($request->hasFile('backimg')) {
            $file = $request->file('backimg');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/kyc_back');
            $file->move($destinationPath, $fileName);
            $input['backimg'] = 'kyc_back/' . $fileName;
        }

        // Upload address proof (optional)
        if ($request->hasFile('address_proof_file')) {
            $file = $request->file('address_proof_file');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/address_proof');
            $file->move($destinationPath, $fileName);
            $input['address_proof_file'] = 'address_proof/' . $fileName;
        }

        // Upload identity file (optional)
        if ($request->hasFile('identity_file')) {
            $file = $request->file('identity_file');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/identity');
            $file->move($destinationPath, $fileName);
            $input['identity_file'] = 'identity/' . $fileName;
        }

        // Default values if not passed
        $input['status'] = $input['status'] ?? 'pending';
        $input['identity_status'] = $input['identity_status'] ?? 'pending';
        $input['address_status'] = $input['address_status'] ?? 'pending';
        $input['created_by'] = auth()->id() ?? 0;
        $input['updated_by'] = auth()->id() ?? 0;

        $kyc = Kyc::create($input);
        $customer = Customer::findOrFail($input['customer_id']);
        $customer->kyc_id = $kyc->id;
        $customer->save();

        $mainSettings = Setting::first();

        // Mail to Admin
        Mail::raw(
            "New KYC Added and Approved.\n\nKYC ID: {$kyc->id}\nCustomer: {$customer->fname} {$customer->lname}\nEmail: {$kyc->email}",
            function ($message) use ($mainSettings) {
                $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                    ->subject('New KYC Approved');
            }
        );

        // Mail to User
        if ($customer && $customer->email) {
            Mail::raw(
                "Dear {$customer->fname},\n\nYour KYC has been submitted and approved successfully.\nKYC Status: {$kyc->status}\n\nThank you.",
                function ($message) use ($customer) {
                    $message->to($customer->email, $customer->fname ?? '')
                        ->subject('Your KYC Has Been Approved');
                }
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'KYC entry created successfully!',
            'data'    => $kyc
        ], 201);
    }

    public function show($id): View
    {
        $kyc = Kyc::with('customer')->findOrFail($id);
        return view('admin.kycs.show', compact('kyc'));
    }

    public function edit($id): View
    {
        $kyc = Kyc::findOrFail($id);
        $customers = Customer::where('kyc_id', 0)
            ->orWhereNull('kyc_id')
            ->get();
        return view('admin.kycs.edit', compact('kyc', 'customers'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'country_code'   => 'required|string|max:10',
            'phone_number'   => 'required|string|max:20',
            'dob'            => 'required|date',
            'social_media'   => 'required|url|max:255',
            'address'        => 'required|string',
            'city'           => 'required|string|max:255',
            'state'          => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'address_proof_type' => 'required|in:Utility Bill,Rent Agreement,Bank Statement,Passport,Driving License,Voter ID',
            'address_proof_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'document_type'  => 'required|string|max:255',
            'frontimg'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'backimg'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'         => 'nullable|in:pending,approved,rejected',
            'identity_type'  => 'nullable|in:Aadhar,PAN,Passport,VoterID,DrivingLicense',
            'identity_number' => 'nullable|string|max:50',
            'identity_file'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'identity_status' => 'nullable|in:pending,approved,rejected',
            'kyc_type'       => 'required|in:online,offline',
            'source'         => 'required|in:APP,WEB',
        ]);

        $kyc = Kyc::findOrFail($id);
        $input = $request->except(['_token', '_method']);

        // Upload front image
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

        // Upload back image
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

        // Upload address proof
        if ($request->hasFile('address_proof_file')) {
            if ($kyc->address_proof_file) {
                Storage::disk('public')->delete($kyc->address_proof_file);
            }
            $file = $request->file('address_proof_file');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/address_proof');
            $file->move($destinationPath, $fileName);
            $input['address_proof_file'] = 'address_proof/' . $fileName;
        }

        // Upload identity file
        if ($request->hasFile('identity_file')) {
            if ($kyc->identity_file) {
                Storage::disk('public')->delete($kyc->identity_file);
            }
            $file = $request->file('identity_file');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/identity');
            $file->move($destinationPath, $fileName);
            $input['identity_file'] = 'identity/' . $fileName;
        }

        // Default values if not passed
        $input['status'] = $input['status'] ?? 'pending';
        $input['identity_status'] = $input['identity_status'] ?? 'pending';
        $input['address_status'] = $input['address_status'] ?? 'pending';
        $input['updated_by'] = auth()->id() ?? 0;

        $kyc->update($input);
        $customer = Customer::findOrFail($input['customer_id']);
        $customer->kyc_id = $kyc->id;
        $customer->save();

        $mainSettings = Setting::first();

        // Mail to Admin
        Mail::raw(
            "KYC Added and Updated.\n\nKYC ID: {$kyc->id}\nCustomer: {$customer->fname} {$customer->lname}\nRegistered Email: {$kyc->email}",
            function ($message) use ($mainSettings) {
                $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                    ->subject('KYC Update');
            }
        );

        // Mail to User
        if ($customer && $customer->email) {
            Mail::raw(
                "Dear {$customer->fname},\n\nYour KYC has been updated successfully.\nKYC Status: {$kyc->status}\n\nThank you.",
                function ($message) use ($customer) {
                    $message->to($customer->email, $customer->fname ?? '')
                        ->subject('Your KYC Has Been Approved');
                }
            );
        }


        return response()->json([
            'success' => true,
            'message' => 'KYC updated successfully!',
            'data'    => $kyc
        ]);
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
        if ($kyc->address_proof_file) {
            Storage::disk('public')->delete($kyc->address_proof_file);
        }
        if ($kyc->identity_file) {
            Storage::disk('public')->delete($kyc->identity_file);
        }

        $kyc->delete();

        return response()->json(['success' => true, 'message' => 'KYC entry deleted successfully!']);
    }
}
