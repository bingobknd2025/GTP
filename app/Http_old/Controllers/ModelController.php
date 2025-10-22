<?php
namespace App\Http\Controllers;

use App\Models\Models;
use App\Models\Brand;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;


class ModelController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $models = Models::where('is_deleted', 0)
            ->get();
            return datatables()->of($models)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '';

                    if (auth()->user()->can('Model Edit')) {
                        $btn .= '<button class="btn btn-sm btn-primary edit-btn me-1" 
                                    data-id="'.$row->id.'" 
                                    data-model_name="'.e($row->model_name).'" 
                                    data-brand_id="'.$row->brand_id.'" 
                                    data-product_category_id="'.$row->product_category_id.'" 
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button> ';
                    }

                    if (auth()->user()->can('Model Delete')) {
                        $deleteUrl = route('admin.model.destroy', $row->id);

                        $btn .= '<form action="'.$deleteUrl.'" method="POST" style="display:inline;">
                                    '.csrf_field().method_field('DELETE').'
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure to delete Model?\')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>';
                    }

                    return $btn;
                })


                ->rawColumns(['action'])
                ->make(true);
        }

        $brands = Brand::where('is_deleted',0)->get();
        $product_categorys = ProductCategory::where('is_deleted',0)->get();

        return view('admin.model.index',compact('brands','product_categorys'));
    }


    public function create() {
        return view('admin.model.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'brand_id' => 'required',
            'product_category_id' => 'required',
        ]);

        $brand = Brand::find($request->brand_id);
        $category = ProductCategory::find($request->product_category_id);

        $model = new Models();
        $model->brand_id = $validated['brand_id'];
        $model->brand_name = $brand->brand_name;
        $model->product_category_id = $validated['product_category_id'];
        $model->product_category = $category->category_name;
        $model->model_name = $validated['model_name'];
        $model->save();

        return response()->json(['message' => 'Model created successfully!']);
    }


    public function edit(Models $id) {
        return view('admin.model.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'brand_id' => 'required',
            'product_category_id' => 'required',
        ]);

        $brand = Brand::find($request->brand_id);
        $category = ProductCategory::find($request->product_category_id);

        $model = Models::findOrFail($id);
        $model->brand_id = $validated['brand_id'];
        $model->brand_name = $brand->brand_name;
        $model->product_category_id = $validated['product_category_id'];
        $model->product_category = $category->category_name;
        $model->model_name = $validated['model_name'];
        $model->save();

        return response()->json(['success' => true]);
    }


    public function destroy($id) {
        $data = Models::find($id);
        $data->is_deleted = 1;
        $data->save();
        return redirect()->route('admin.model.index')->with('success', 'Model deleted!');
    }
}
