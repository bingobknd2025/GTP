<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\Setting;
use DataTables;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    function __construct()
    {
        // Permissions will be added here later
    }

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
        return view('admin.deposits.create', compact('customers'));
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator($request->all(), [
            'user'             => 'required|exists:customers,id',
            'amount'           => 'required|numeric|min:1',
            'payment_mode'     => 'required|string',
            'plan'             => 'nullable|exists:plans,id',
            'source'           => 'required|in:APP,WEB',
            'status'           => 'required|in:Pending,Approved,Rejected,Unknown',
            'proof'            => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $input = $request->all();

        $lastDeposit = Deposit::latest('id')->first();
        $nextSequence = $lastDeposit ? $lastDeposit->id + 1 : 1;
        $datePart = now()->format('dMY');
        $txnNumber = str_pad($nextSequence, 6, '0', STR_PAD_LEFT);
        $input['txn_id'] = 'TXN' . $datePart . $txnNumber;
        $yearPart = now()->format('Y');
        $refNumber = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
        $input['reference_number'] = 'DEPOSIT' . $yearPart . $refNumber;

        if ($request->hasFile('proof')) {
            $file = $request->file('proof');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/deposit_proofs');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $fileName);
            $input['proof'] = 'deposit_proofs/' . $fileName;
        }

        // ✅ Save deposit
        $deposit = Deposit::create([
            'txn_id'           => $input['txn_id'],
            'user'             => $input['user'],
            'amount'           => $input['amount'],
            'payment_mode'     => $input['payment_mode'],
            'plan'             => $input['plan'] ?? null,
            'reference_number' => $input['reference_number'],
            'source'           => $input['source'],
            'status'           => $input['status'],
            'proof'            => $input['proof'] ?? null,
        ]);

        $mainSettings = Setting::first();
        $user = Customer::find($deposit->user);

        // Mail to Admin
        Mail::send('emails.deposit_notification', [
            'deposit'  => $deposit,
            'settings' => $mainSettings,
            'for'      => 'admin'
        ], function ($message) use ($mainSettings) {
            $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                ->subject('New Deposit Created');
        });

        // Mail to User
        if ($user && $user->email) {
            Mail::send('emails.deposit_notification', [
                'deposit'  => $deposit,
                'user'     => $user,
                'for'      => 'user'
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name ?? '')
                    ->subject('Your Deposit Has Been Submitted');
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Deposit created successfully!',
            'data'    => $deposit
        ]);
    }


    public function show($id): View
    {
        $deposits = Deposit::with('customer')->findOrFail($id);
        return view('admin.deposits.show', compact('deposits'));
    }

    public function edit($id): View
    {
        $deposit = Deposit::findOrFail($id);
        $customers = Customer::all();
        return view('admin.deposits.edit', compact('deposit', 'customers'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator($request->all(), [
            'user'           => 'required|exists:customers,id',
            'amount'         => 'required|numeric|min:1',
            'payment_mode'   => 'required|string',
            'plan'           => 'nullable|exists:plans,id',
            'source'         => 'required|in:APP,WEB',
            'status'         => 'required|in:Pending,Approved,Rejected,Unknown',
            'proof'          => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $deposit = Deposit::findOrFail($id);
        $input = $request->all();

        // Handle proof file upload
        if ($request->hasFile('proof')) {
            // Delete old file if exists
            if ($deposit->proof && file_exists(public_path('storage/' . $deposit->proof))) {
                unlink(public_path('storage/' . $deposit->proof));
            }

            $file = $request->file('proof');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/deposit_proofs');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $fileName);
            $input['proof'] = 'deposit_proofs/' . $fileName;
        }

        // Update deposit
        $deposit->update([
            'user'         => $input['user'],
            'amount'       => $input['amount'],
            'payment_mode' => $input['payment_mode'],
            'plan'         => $input['plan'] ?? null,
            'source'       => $input['source'],
            'status'       => $input['status'],
            'proof'        => $input['proof'] ?? $deposit->proof,
        ]);

        $mainSettings = Setting::first();
        $user = Customer::find($deposit->user);

        // Mail to Admin
        Mail::send('emails.deposit_notification', [
            'deposit'  => $deposit,
            'settings' => $mainSettings,
            'for'      => 'admin'
        ], function ($message) use ($mainSettings) {
            $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                ->subject('Deposit Updated');
        });

        // Mail to User
        if ($user && $user->email) {
            Mail::send('emails.deposit_notification', [
                'deposit'  => $deposit,
                'user'     => $user,
                'for'      => 'user'
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name ?? '')
                    ->subject('Your Deposit Has Been Updated');
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Deposit updated successfully and emails sent!',
            'data'    => $deposit
        ]);
    }


    public function destroy($id): JsonResponse
    {
        $deposit = Deposit::findOrFail($id);
        if ($deposit->proof) {
            Storage::disk('public')->delete($deposit->proof);
        }
        $deposit->delete();

        return response()->json(['success' => true, 'message' => 'Deposit deleted successfully!']);
    }
}
