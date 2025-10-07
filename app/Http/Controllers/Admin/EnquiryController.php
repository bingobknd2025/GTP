<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Models\Kyc;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EnquiryController extends Controller
{
    function __construct()
    {
        // 
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Enquiry::orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // Customer full name
                ->addColumn('name', function ($row) {
                    return $row->name ? $row->name : 'N/A';
                })

                // Customer Email
                ->addColumn('email', function ($row) {
                    return $row->email ? $row->email : 'N/A';
                })

                // Customer Phone
                ->addColumn('phone', function ($row) {
                    return $row->phone ? $row->phone : 'N/A';
                })

                // Identity Type
                ->addColumn('subject', function ($row) {
                    return $row->subject ? $row->subject : 'N/A';
                })

                // Identity Number
                ->addColumn('message', function ($row) {
                    return $row->message ? $row->message : 'N/A';
                })
                // Action buttons
                ->addColumn('action', function ($row) {
                    $deleteUrl = route('admin.kycs.destroy', $row->id);
                    $showUrl   = route('admin.kycs.show', $row->id);

                    $btn  = '<a href="' . $showUrl . '" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye"></i></a>';
                    $btn .= '<form action="' . $deleteUrl . '" method="POST" class="d-inline delete-kyc-form">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button></form>';

                    return $btn;
                })

                ->rawColumns([
                    'name',
                    'email',
                    'phone',
                    'subject',
                    'message',
                    'action'
                ])
                ->make(true);
        }

        return view('admin.settings.enquiry-settings');
    }


    public function destroy($id): JsonResponse
    {
        $enquiry = Enquiry::findOrFail($id);

        $enquiry->delete();
        return response()->json([
            'success' => true,
            'message' => 'Enquiry entry deleted successfully.'
        ]);
    }
}
