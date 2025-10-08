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

    public function indexCustomer(Request $request)
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
                    $deleteUrl = route('admin.customer_activity.destroy', $row->id);
                    $showUrl   = route('admin.customer_activity.show', $row->id);

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

        return view('admin.activities.customer-index');
    }

    public function indexFranchise(Request $request)
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
                    $deleteUrl = route('admin.franchise_activity.destroy', $row->id);
                    $showUrl   = route('admin.franchise_activity.show', $row->id);

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

        return view('admin.activities.franchise-index');
    }


    public function show($id): View
    {
        $activity = UserActivity::findOrFail($id);
        return view('admin.activities.admin-show', compact('activity'));
    }

    public function showCustomer($id): View
    {
        $activity = UserActivity::findOrFail($id);
        return view('admin.activities.customer-show', compact('activity'));
    }

    public function showFranchise($id): View
    {
        $activity = UserActivity::findOrFail($id);
        return view('admin.activities.franchise-show', compact('activity'));
    }

    public function destroy($id): JsonResponse
    {
        $activity = UserActivity::findOrFail($id);
        $activity->delete();

        return response()->json(['success' => true, 'message' => 'Activity entry deleted successfully!']);
    }

    public function destroyCustomer($id): JsonResponse
    {
        $activity = UserActivity::findOrFail($id);
        $activity->delete();

        return response()->json(['success' => true, 'message' => 'Customer activity entry deleted successfully!']);
    }

    public function destroyFranchise($id): JsonResponse
    {
        $activity = UserActivity::findOrFail($id);
        $activity->delete();

        return response()->json(['success' => true, 'message' => 'Franchise activity entry deleted successfully!']);
    }
}
