<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;
use App\Models\Customer;
use App\Models\Franchise;
use App\Models\Setting;
use App\Models\UserActivity;
use Carbon\Carbon;
use DataTables;
use Illuminate\Container\Attributes\Log;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    function __construct()
    {
        // 
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UserActivity::orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('user_id', function ($row) {
                    return $row->user_id ? $row->user_id : 'N/A';
                })

                ->addColumn('type', function ($row) {
                    return $row->type ? $row->type : 'N/A';
                })

                ->addColumn('details', function ($row) {
                    return $row->details ? $row->details : 'N/A';
                })

                ->addColumn('ip_address', function ($row) {
                    return $row->ip_address ?? 'N/A';
                })

                ->addColumn('device', function ($row) {
                    return $row->device ?? 'N/A';
                })
                ->addColumn('browser', function ($row) {
                    return $row->browser ?? 'N/A';
                })
                ->addColumn('os', function ($row) {
                    return $row->os ?? 'N/A';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : 'N/A';
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('Y-m-d H:i:s') : 'N/A';
                })

                ->addColumn('action', function ($row) {
                    $deleteUrl = route('admin.activity.destroy', $row->id);
                    $showUrl   = route('admin.activity.show', $row->id);

                    $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye"></i></a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline delete-kyc-form">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button></form>';

                    return $btn;
                })

                ->rawColumns([
                    'user_id',
                    'type',
                    'details',
                    'ip_address',
                    'device',
                    'browser',
                    'os',
                    'created_at',
                    'updated_at',
                    'action'
                ])
                ->make(true);
        }

        return view('admin.activities.admin-index');
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
        try {
            $validator = Validator::make($request->all(), [

                'customer_id'    => 'required|exists:customers,id',
                'first_name'     => 'required|string|max:255',
                'last_name'      => 'required|string|max:255',
                'email'          => 'required|email|max:255',
                'country_code'   => 'required|string|max:10',
                'phone_number' => 'required|string|max:15|exists:customers,mobile_no',
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
                'status'         => 'required|in:pending,approved,rejected',
                'identity_type'   => 'required|in:Aadhar,PAN,Passport,VoterID,DrivingLicense',
                'identity_number' => 'required|string|max:50',
                'identity_file'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
                'identity_status' => 'nullable|in:true,false',
                'kyc_type'       => 'required|in:online,offline',
                'source'         => 'required|in:APP,WEB',
                'zip_code'  => 'nullable|string|max:20',
                'franchise_status' => 'required|in:true,false',
                'admin_status'    => 'required|in:true,false',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation errors',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $input = $request->all();

            // Upload files
            $frontPath = $request->file('frontimg')->store('kyc_docs', 'public');
            $backPath = $request->hasFile('backimg') ? $request->file('backimg')->store('kyc_docs', 'public') : null;
            $addressProofPath = $request->hasFile('address_proof_file') ? $request->file('address_proof_file')->store('address_proofs', 'public') : null;
            $identityFilePath = $request->hasFile('identity_file') ? $request->file('identity_file')->store('identity_files', 'public') : null;

            // Create or update KYC
            $kyc = Kyc::updateOrCreate(
                ['customer_id' => $input['customer_id']],
                [
                    'first_name'          => $input['first_name'],
                    'last_name'           => $input['last_name'],
                    'email'               => $input['email'],
                    'country_code'        => $input['country_code'],
                    'social_media'       => $input['social_media'],
                    'dob'                 => $input['dob'],
                    'identity_type'       => $input['identity_type'],
                    'identity_number'     => $input['identity_number'],
                    'frontimg'            => $frontPath,
                    'backimg'             => $backPath,
                    'identity_file'       => $identityFilePath,
                    'identity_status'     => 'true',
                    'country'             => $input['country'],
                    'city'                => $input['city'],
                    'address'             => $input['address'],
                    'state'               => $input['state'],
                    'zip_code'            => $input['zip_code'] ?? null,
                    'resi_address_status' => 'true',
                    'address_status'      => 'approved',

                    'address_proof_type'  => $input['address_proof_type'] ?? null,
                    'address_proof_file'  => $addressProofPath,
                    'address_veri_status' => 'true',

                    'phone_number'        => $input['phone_number'],
                    'mobile_verified_at'  => Carbon::now(),
                    'mobile_status'       => 'true',

                    'status'              => 'Approved',
                    'final_status'        => $input['final_status'] ?? 'true',
                    'kyc_type'            => $input['kyc_type'] ?? 'online',
                    'source'              => $input['source'] ?? 'WEB',
                    'franchise_status'    => $input['franchise_status'],
                    'admin_status'        => $input['admin_status'],
                    'updated_by'          => auth()->id() ?? 0,
                    'created_by'          => auth()->id() ?? 0,
                ]
            );

            // Link KYC to Customer
            $customer = Customer::findOrFail($input['customer_id']);
            $customer->country = $input['country'];
            $customer->kyc_id = $kyc->id;
            $customer->mobile_no = $input['phone_number'];
            $customer->mobile_verfied = 1;
            $customer->account_verify = 1;
            $customer->kyc_status = 'Verified';
            $customer->status = 'Approved';
            $customer->save();

            // Fetch main settings
            $mainSettings = Setting::first();

            // Mail to Admin
            if ($mainSettings && $mainSettings->mail_from_email) {
                Mail::raw(
                    "New KYC Added and Approved.\n\nKYC ID: {$kyc->id}\nCustomer: {$customer->fname} {$customer->lname}\nEmail: {$kyc->email}",
                    function ($message) use ($mainSettings) {
                        $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                            ->subject('New KYC Approved');
                    }
                );
            }

            // Mail to Customer/User
            if ($customer && $customer->email) {
                Mail::raw(
                    "Dear {$customer->fname},\n\nYour KYC has been submitted and approved successfully.\nKYC Status: {$kyc->status}\n\nThank you.",
                    function ($message) use ($customer) {
                        $message->to($customer->email, $customer->fname ?? '')
                            ->subject('Your KYC Has Been Approved');
                    }
                );
            }

            // Mail to Franchise (if customer has a franchise assigned)
            if ($customer->franchise_id) {
                $franchise = Franchise::find($customer->franchise_id);
                if ($franchise && $franchise->email) {
                    Mail::raw(
                        "A new customer has been created under you.\n\nCustomer: {$customer->fname} {$customer->lname}\nEmail: {$customer->email}\nKYC ID: {$kyc->id}",
                        function ($message) use ($franchise) {
                            $message->to($franchise->email, $franchise->name ?? '')
                                ->subject('New Customer Created Under You');
                        }
                    );
                }
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'KYC submitted successfully (Admin Form).',
                'data'    => $kyc
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
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

    public function processedKyc(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:kycs,id',
            'status' => 'required|in:true,false',
        ]);

        try {
            $kyc = Kyc::findOrFail($request->id);

            // Convert string 'true'/'false' to boolean
            $newStatus = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);

            // Update final_status and admin_status logic
            $kyc->final_status = $newStatus;
            $kyc->admin_status = $newStatus; // if both must be same

            $kyc->save();

            return redirect()->route('admin.kycs.index')
                ->with('success', 'KYC final status updated successfully.');
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
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
