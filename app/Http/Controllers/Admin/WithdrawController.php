<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\Franchise;
use App\Models\Setting;
use App\Models\Withdrawal;
use DataTables;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WithdrawController extends Controller
{
    function __construct()
    {
        // Permissions will be added here later
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Withdrawal::with('customer')->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('id', fn($row) => $row->id)

                ->addColumn('txn_id', fn($row) => $row->txn_id ?? 'N/A')

                ->addColumn('customer', function ($row) {
                    return $row->customer?->name ?? 'N/A';
                })

                ->addColumn('amount', fn($row) => number_format($row->amount, 2))

                ->addColumn('charges', fn($row) => number_format($row->charges, 2))

                ->addColumn('to_deduct', fn($row) => number_format($row->to_deduct, 2))

                ->addColumn('payment_mode', fn($row) => $row->payment_mode ?? 'N/A')

                ->addColumn('paydetails', fn($row) => $row->paydetails ?? 'N/A')

                ->addColumn('comment', fn($row) => $row->comment ?? 'N/A')

                ->addColumn('reference_number', fn($row) => $row->reference_number ?? 'N/A')

                ->addColumn('source', fn($row) => $row->source ?? 'N/A')

                ->addColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d H:i:s'))

                ->addColumn('updated_at', fn($row) => $row->updated_at?->format('Y-m-d H:i:s'))

                ->addColumn('status', function ($row) {
                    $status = ucfirst($row->status ?? 'Unknown');

                    $colorClass = match ($status) {
                        'Pending' => 'bg-warning text-dark',
                        'Approved' => 'bg-success',
                        'Rejected' => 'bg-danger',
                        'Unknown' => 'bg-info text-dark',
                        default => 'bg-secondary'
                    };

                    // Dropdown with current status
                    return '
                    <div class="dropdown">
                        <span class="badge ' . $colorClass . ' dropdown-toggle" data-bs-toggle="dropdown" style="cursor:pointer;">
                            ' . $status . '
                        </span>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item change-status" href="#" data-id="' . $row->id . '" data-status="Pending">Pending</a></li>
                            <li><a class="dropdown-item change-status" href="#" data-id="' . $row->id . '" data-status="Approved">Approved</a></li>
                            <li><a class="dropdown-item change-status" href="#" data-id="' . $row->id . '" data-status="Rejected">Rejected</a></li>
                            <li><a class="dropdown-item change-status" href="#" data-id="' . $row->id . '" data-status="Unknown">Unknown</a></li>
                        </ul>
                    </div>';
                })

                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.withdraws.edit', $row->id);
                    $deleteUrl = route('admin.withdraws.destroy', $row->id);
                    $showUrl = route('admin.withdraws.show', $row->id);

                    $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1"><i class="fas fa-eye fw-bold"></i></a>';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit fw-bold"></i></a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline delete-withdraw-form">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt fw-bold"></i></button></form>';

                    return $btn;
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.withdraws.index');
    }

    public function create(): View
    {
        $customers = Customer::all();
        return view('admin.withdraws.create', compact('customers'));
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'user'             => 'required|exists:customers,id',
            'amount'           => 'required|numeric|min:10',
            'charges'          => 'nullable|numeric|min:0',
            'columns'          => 'nullable|string',
            'to_deduct'        => 'nullable|numeric|min:0',
            'payment_mode'     => 'required|string',
            'paydetails'       => 'nullable|string',
            'comment'          => 'nullable|string',
            'source'           => 'required|in:APP,WEB',
            'status'           => 'required|in:pending,approved,rejected,unknown', // enum values
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $input = $request->all();

        $lastWithdrawal = Withdrawal::latest('id')->first();
        $nextSequence = $lastWithdrawal ? $lastWithdrawal->id + 1 : 1;
        $datePart = now()->format('dMY');
        $txnNumber = str_pad($nextSequence, 6, '0', STR_PAD_LEFT);
        $input['txn_id'] = 'WDR' . $datePart . $txnNumber;

        $yearPart = now()->format('Y');
        $refNumber = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
        $input['reference_number'] = 'WITHDRAW' . $yearPart . $refNumber;

        $withdrawal = Withdrawal::create([
            'txn_id'           => $input['txn_id'],
            'user'             => $input['user'],
            'amount'           => $input['amount'],
            'charges'          => $input['charges'] ?? 0,
            'columns'          => $input['columns'] ?? null,
            'to_deduct'        => $input['to_deduct'] ?? 0,
            'payment_mode'     => $input['payment_mode'],
            'paydetails'       => $input['paydetails'] ?? null,
            'comment'          => $input['comment'] ?? null,
            'reference_number' => $input['reference_number'],
            'source'           => $input['source'],
            'status'           => $input['status'],
        ]);

        $mainSettings = Setting::first();
        $user = Customer::find($withdrawal->user);

        Mail::send('emails.withdraw_notification', [
            'withdraw' => $withdrawal,
            'settings' => $mainSettings,
            'for'      => 'admin',
            'user'     => $user,
        ], function ($message) use ($mainSettings) {
            $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                ->subject('New Withdrawal Request');
        });

        if ($user && $user->email) {
            Mail::send('emails.withdraw_notification', [
                'withdraw' => $withdrawal,
                'user'     => $user,
                'for'      => 'user'
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name ?? '')
                    ->subject('Your Withdrawal Request Has Been Submitted');
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal created successfully!',
            'data'    => $withdrawal
        ]);
    }

    public function show($id): View
    {
        $withdrawal = Withdrawal::with('customer')->findOrFail($id);
        return view('admin.withdraws.show', compact('withdrawal'));
    }

    public function edit($id): View
    {
        $withdraw = Withdrawal::findOrFail($id);
        $customers = Customer::all();
        return view('admin.withdraws.edit', compact('withdraw', 'customers'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'user'           => 'required|exists:customers,id',
            'amount'         => 'required|numeric|min:10',
            'charges'        => 'nullable|numeric|min:0',
            'columns'        => 'nullable|string',
            'to_deduct'      => 'nullable|numeric|min:0',
            'payment_mode'   => 'required|string',
            'paydetails'     => 'nullable|string',
            'comment'        => 'nullable|string',
            'source'         => 'required|in:APP,WEB',
            'status'         => 'required|in:pending,approved,rejected,unknown',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $withdrawal = Withdrawal::findOrFail($id);
        $input = $request->all();

        // Update withdrawal
        $withdrawal->update([
            'user'        => $input['user'],
            'amount'      => $input['amount'],
            'charges'     => $input['charges'] ?? $withdrawal->charges,
            'columns'     => $input['columns'] ?? $withdrawal->columns,
            'to_deduct'   => $input['to_deduct'] ?? $withdrawal->to_deduct,
            'payment_mode' => $input['payment_mode'],
            'paydetails'  => $input['paydetails'] ?? $withdrawal->paydetails,
            'comment'     => $input['comment'] ?? $withdrawal->comment,
            'source'      => $input['source'],
            'status'      => $input['status'],
        ]);

        $mainSettings = Setting::first();
        $user = Customer::find($withdrawal->user);

        // Mail to Admin
        Mail::send('emails.withdraw_notification', [
            'withdraw' => $withdrawal,
            'settings' => $mainSettings,
            'for'      => 'admin',
            'user'     => $user,
        ], function ($message) use ($mainSettings) {
            $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                ->subject('Withdrawal Updated');
        });

        // Mail to User
        if ($user && $user->email) {
            Mail::send('emails.withdraw_notification', [
                'withdraw' => $withdrawal,
                'user'     => $user,
                'for'      => 'user'
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name ?? '')
                    ->subject('Your Withdrawal Has Been Updated');
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal updated successfully and emails sent!',
        ]);
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Approved,Rejected,Unknown',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->status = $request->status;
        $withdrawal->save();

        $mainSettings = Setting::first();
        $user = Customer::find($withdrawal->customer_id);
        $franchise = $user?->ref_by ? Franchise::where('code', $user->ref_by)->first() : null;

        try {
            if ($mainSettings?->mail_from_email) {
                Mail::send('emails.withdraw_status', [
                    'withdrawal' => $withdrawal,
                    'settings' => $mainSettings,
                    'user' => $user,
                    'role' => 'admin',
                ], function ($message) use ($mainSettings) {
                    $message->to($mainSettings->mail_from_email, $mainSettings->mail_from_name)
                        ->subject('Withdrawal Status Updated - Admin Notification');
                });
            }

            if ($franchise?->email) {
                Mail::send('emails.withdraw_status', [
                    'withdrawal' => $withdrawal,
                    'settings' => $mainSettings,
                    'user' => $user,
                    'role' => 'franchise',
                    'franchise' => $franchise,
                ], function ($message) use ($franchise) {
                    $message->to($franchise->email, $franchise->name ?? 'Franchise Partner')
                        ->subject('Withdrawal Status Updated - Franchise Notification');
                });
            }

            if ($user?->email) {
                Mail::send('emails.withdraw_status', [
                    'withdrawal' => $withdrawal,
                    'user' => $user,
                    'role' => 'user',
                ], function ($message) use ($user) {
                    $message->to($user->email, $user->fname ?? 'User')
                        ->subject('Your Withdrawal Status Has Been Updated');
                });
            }
        } catch (\Exception $e) {
            response()->json([
                'success' => false,
                'message' => 'Status updated but failed to send email notifications: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Withdrawal status updated to '{$request->status}' successfully!",
        ]);
    }

    public function destroy($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->delete();

        return response()->json(['success' => true, 'message' => 'Withdrawal deleted successfully!']);
    }
}
