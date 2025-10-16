<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;
use App\Models\Customer;
use App\Models\Franchise;
use App\Models\Setting;
use Carbon\Carbon;
use DataTables;
use Illuminate\Container\Attributes\Log;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KycController extends Controller
{
    function __construct()
    {
        // 
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kyc::with('customer')->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // Customer full name
                ->addColumn('customer_name', function ($row) {
                    return $row->customer ? $row->customer->fname . ' ' . $row->customer->lname : 'N/A';
                })

                // Customer Email
                ->addColumn('email', function ($row) {
                    return $row->customer ? $row->customer->email : 'N/A';
                })

                // Customer Phone
                ->addColumn('phone_number', function ($row) {
                    return $row->customer ? $row->customer->mobile_no : 'N/A';
                })

                // Identity Type
                ->addColumn('identity_type', function ($row) {
                    return $row->identity_type ?? 'N/A';
                })

                // Identity Number
                ->addColumn('identity_number', function ($row) {
                    return $row->identity_number ?? 'N/A';
                })

                // Identity Status
                ->addColumn('identity_status', function ($row) {
                    $statusText  = $row->identity_status === 'true' ? 'Verified' : 'Not Verified';
                    $statusClass = $row->identity_status === 'true' ? 'bg-success' : 'bg-danger';
                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })

                // Mobile Status
                ->addColumn('mobile_status', function ($row) {
                    $statusText  = $row->mobile_status === 'true' ? 'Verified' : 'Not Verified';
                    $statusClass = $row->mobile_status === 'true' ? 'bg-success' : 'bg-danger';
                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })

                // Address Proof Status
                ->addColumn('address_veri_status', function ($row) {
                    $statusText  = $row->address_veri_status === 'true' ? 'Submitted' : 'Pending';
                    $statusClass = $row->address_veri_status === 'true' ? 'bg-success' : 'bg-warning';
                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })

                // Residential Address Status
                ->addColumn('resi_address_status', function ($row) {
                    $statusText  = $row->resi_address_status === 'true' ? 'Submitted' : 'Pending';
                    $statusClass = $row->resi_address_status === 'true' ? 'bg-success' : 'bg-warning';
                    return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
                })

                // Final Status
                ->editColumn('final_status', function ($row) {
                    return $row->final_status === 'true' ? 'true' : 'false';
                })

                // KYC Type
                ->addColumn('kyc_type', function ($row) {
                    $typeText  = $row->kyc_type === 'online' ? 'Online' : 'Offline';
                    $typeClass = $row->kyc_type === 'online' ? 'bg-primary' : 'bg-secondary';
                    return '<span class="badge ' . $typeClass . '">' . $typeText . '</span>';
                })

                // Action buttons
                ->addColumn('action', function ($row) {
                    $editUrl   = route('admin.kycs.edit', $row->id);
                    $deleteUrl = route('admin.kycs.destroy', $row->id);
                    $showUrl   = route('admin.kycs.show', $row->id);

                    $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline delete-kyc-form">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button></form>';

                    return $btn;
                })

                ->rawColumns([
                    'identity_status',
                    'mobile_status',
                    'address_veri_status',
                    'resi_address_status',
                    'final_status',
                    'kyc_type',
                    'action'
                ])
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
            DB::beginTransaction();

            $kyc = Kyc::findOrFail($request->id);
            $finalStatus = $request->status === 'true' ? 'true' : 'false';

            $kyc->final_status = $finalStatus;
            $kyc->admin_status = $finalStatus;
            $kyc->franchise_status = $finalStatus;
            $kyc->save();

            $customer = Customer::find($kyc->customer_id);
            if ($customer) {
                if ($finalStatus === 'true') {
                    $customer->kyc_status = 'Verified';
                    $customer->status = 'Approved';
                    $customerMessage = "Dear {$customer->fname},\n\nYour KYC has been approved successfully.\n\nThank you for completing your verification!";
                } else {
                    $customer->kyc_status = 'Not Verified';
                    $customer->status = 'Pending';
                    $customerMessage = "Dear {$customer->fname},\n\nYour KYC is currently pending. Please complete the required documents or contact support.\n\nThank you.";
                }
                $customer->save();

                // Send email safely
                if (!empty($customer->email)) {
                    try {
                        Mail::raw($customerMessage, function ($message) use ($customer, $finalStatus) {
                            $subject = $finalStatus === 'true'
                                ? 'KYC Approved Successfully'
                                : 'KYC Pending';
                            $message->to($customer->email, $customer->fname ?? '')
                                ->subject($subject);
                        });
                    } catch (\Exception $mailException) {
                        \Log::error("Customer mail failed: " . $mailException->getMessage());
                    }
                }
            }

            // Notify franchise safely
            if ($customer && $customer->franchise_id) {
                $franchise = Franchise::find($customer->franchise_id);
                if ($franchise && !empty($franchise->email)) {
                    $franchiseMessage = "KYC final status updated for one of your customers.\n\n"
                        . "Customer: {$customer->fname} {$customer->lname}\n"
                        . "Email: {$customer->email}\n"
                        . "KYC ID: {$kyc->id}\n"
                        . "Status: " . ($finalStatus === 'true' ? 'Approved' : 'Pending');
                    try {
                        Mail::raw($franchiseMessage, function ($message) use ($franchise) {
                            $message->to($franchise->email, $franchise->name ?? '')
                                ->subject('KYC Status Updated for Customer');
                        });
                    } catch (\Exception $mailException) {
                        \Log::error("Franchise mail failed: " . $mailException->getMessage());
                    }
                }
            }

            DB::commit();

            // if ($finalStatus === 'true' && $customer) {
            //     try {
            //         $token = JWTAuth::fromUser($customer);
            //         JWTAuth::invalidate($token);

            //     } catch (\Exception $logoutException) {
            //         \Log::warning("Failed to invalidate JWT for customer ID {$customer->id}: " . $logoutException->getMessage());
            //     }
            // }
            return response()->json([
                'success' => true,
                'message' => 'KYC final status updated successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating final_status: ' . $e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Server error. Please try again. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $kyc = Kyc::findOrFail($id);
        $customer = Customer::where('kyc_id', $kyc->id)->first();
        if ($customer) {
            $customer->kyc_id = 0;
            $customer->kyc_status = 'Not Verified';
            $customer->save();
        }
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
