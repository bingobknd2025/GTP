<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Withdrawal;
use DataTables;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    function __construct()
    {
        // Permissions will be added here later
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::with('customer') // relation for name
                ->orderBy('id', 'DESC')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('id', fn($row) => $row->id)
                ->addColumn('reference_no', fn($row) => $row->reference_no ?? 'N/A')
                ->addColumn('reference_id', fn($row) => $row->reference_id ?? 'N/A')
                ->addColumn('customer_id', fn($row) => $row->customer_id ?? 'N/A')
                ->addColumn('amount', fn($row) => number_format($row->amount, 2))
                ->addColumn('type', fn($row) => $row->type ?? 'N/A')
                ->addColumn('naration', fn($row) => $row->naration ?? 'N/A')
                ->addColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d H:i:s'))
                ->addColumn('updated_at', fn($row) => $row->updated_at?->format('Y-m-d H:i:s'))

                ->addColumn('action', function ($row) {
                    $editUrl   = route('admin.transactions.edit', $row->id);
                    $deleteUrl = route('admin.transactions.destroy', $row->id);
                    $showUrl   = route('admin.transactions.show', $row->id);

                    $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline delete-transaction-form">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></form>';

                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.transactions.index');
    }



    public function create(): View
    {
        $customers = Customer::all();
        return view('admin.transactions.create', compact('customers'));
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'customer_id'   => 'required|exists:customers,id',
            'amount'        => 'required|numeric|min:10',
            'type'          => 'required|in:Deposite,Withdrawal,Bonous,Referral',
            'naration'      => 'nullable|string',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
            ], 400);
        }

        // Generate reference_no
        $last = Transaction::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        $year = now()->format('Y');
        $reference_no = "TXN{$year}" . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        $transaction = Transaction::create([
            'reference_no' => $reference_no,
            'reference_id' => $nextId,
            'customer_id'  => $request->customer_id,
            'amount'       => $request->amount,
            'type'         => $request->type,
            'naration'     => $request->naration,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
            'data'    => $transaction
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


    public function destroy($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->delete();

        return response()->json(['success' => true, 'message' => 'Withdrawal deleted successfully!']);
    }
}
