<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك بعرض الأصناف');
        }

        try {
            $query = Product::with('stock')->where('is_active', true);

            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('item_code', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->type) {
                $query->where('type', $request->type);
            }

            $products = $query->latest()->paginate(20);

            $requiredTypes = [
                'حوائط جلوريا', 'حوائط ايكو', 'أرضيات جلوريا', 'أرضيات ايكو',
                'HDC', 'UGC', 'بورسل', 'PORSLIM',
                'SUPER GLOSSY 61×122.5', 'SUPER GLOSSY 61×61'
            ];

            $existingTypes = Product::where('is_active', true)
                ->distinct('type')->pluck('type')->filter()->values()->toArray();

            $types = collect(array_merge($requiredTypes, $existingTypes))->unique()->values();

            return view('products.index', [
                'products'     => $products,
                'types'        => $types,
                'search'       => $request->search,
                'selectedType' => $request->type
            ]);
        } catch (\Exception $e) {
            Log::error('Error in products index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في عرض الأصناف');
        }
    }

    public function create()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }
        return view('products.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $validated = $request->validate([
            'item_code'     => 'required|string|unique:products,item_code',
            'type'          => 'required|string',
            'name'          => 'required|string|max:255',
            'color'         => 'nullable|string|max:255',
            'size'          => 'nullable|string|max:255',
            'current_stock' => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:0',
            'price'         => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::create([
                'item_code' => $validated['item_code'],
                'type'      => $validated['type'],
                'name'      => $validated['name'],
                'color'     => $validated['color'] ?? null,
                'size'      => $validated['size'] ?? null,
                'price'     => $validated['price'] ?? 0,
                'is_active' => true,
            ]);

            ProductStock::create([
                'product_id'    => $product->id,
                'current_stock' => $validated['current_stock'],
                'min_stock'     => $validated['min_stock'],
            ]);

            DB::commit();

            return redirect()->route('products.index')->with('success', 'تم إنشاء الصنف بنجاح');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Create product error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        try {
            $product = Product::with('stock', 'orderItems.order')->findOrFail($id);
            return view('products.show', ['product' => $product]);
        } catch (\Exception $e) {
            Log::error('Error in product show: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'المنتج غير موجود');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        try {
            $product = Product::with('stock')->findOrFail($id);
            return view('products.edit', ['product' => $product]);
        } catch (\Exception $e) {
            Log::error('Error in product edit: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'المنتج غير موجود');
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'item_code' => 'required|string|unique:products,item_code,' . $product->id,
            'type'      => 'required|string',
            'name'      => 'required|string|max:255',
            'color'     => 'nullable|string|max:255',
            'size'      => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'min_stock' => 'required|integer|min:0',
            'price'     => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'item_code' => $validated['item_code'],
                'type'      => $validated['type'],
                'name'      => $validated['name'],
                'color'     => $validated['color'] ?? null,
                'size'      => $validated['size'] ?? null,
                'price'     => $validated['price'] ?? $product->price,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            if ($product->stock) {
                $product->stock->update(['min_stock' => $validated['min_stock']]);
            }

            DB::commit();

            return redirect()->route('products.show', $product->id)->with('success', 'تم تحديث الصنف بنجاح');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Update product error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $product = Product::findOrFail($id);

        if ($product->orderItems()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف صنف موجود في طلبيات']);
        }

        try {
            DB::beginTransaction();
            if ($product->stock) $product->stock->delete();
            $product->delete();
            DB::commit();

            return redirect()->route('products.index')->with('success', 'تم حذف الصنف بنجاح');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Delete product error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getByName($name)
    {
        $product = Product::with('stock')
            ->where('name', 'LIKE', "%{$name}%")
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'الصنف غير موجود'], 404);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'name'      => $product->name,
                'item_code' => $product->item_code,
                'stock1'    => $product->getCurrentStock(),
                'price'     => $product->price ?? 0,
            ]
        ]);
    }

    /**
     * ===== التعديل هنا: بيرجع لصفحة الاستيراد مع عرض الأخطاء =====
     */
    public function import(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:51200',
        ]);

        try {
            $import = new ProductsImport();
            Excel::import($import, $request->file('file'));

            $summary = [
                'processed' => $import->successCount + $import->errorCount,
                'success'   => $import->successCount,
                'failed'    => $import->errorCount,
            ];

            $message = "تم استيراد {$summary['success']} صنف بنجاح";
            if ($summary['failed'] > 0) {
                $message .= "، {$summary['failed']} صنف فشل استيرادهم";
            }

            // ===== رجوع لصفحة الاستيراد نفسها عشان نشوف الأخطاء =====
            return redirect()
                ->route('products.importPage')
                ->with('success', $message)
                ->with('import_errors', $import->errors)
                ->with('import_summary', $summary);

        } catch (Throwable $e) {
            Log::error('Import error: ' . $e->getMessage());
            return redirect()
                ->route('products.importPage')
                ->withErrors(['error' => 'خطأ في الاستيراد: ' . $e->getMessage()]);
        }
    }

    public function importPage()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }
        return view('products.import');
    }

    public function downloadTemplate()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        try {
            $products = Product::where('is_active', true)->with('stock')->get();

            $headers  = ['كود الصنف', 'اسم الصنف', 'الوحدة', 'الرصيد'];
            $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';

            $stream = fopen('php://temp', 'w+');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, $headers);

            if ($products->count() > 0) {
                foreach ($products as $product) {
                    fputcsv($stream, [
                        $product->item_code,
                        $product->name,
                        $product->type ?? 'سيراميك',
                        $product->stock->current_stock ?? 0,
                    ]);
                }
            } else {
                fputcsv($stream, ['01041200076147', 'بلاط حوائط 60×60 لامع', 'سيراميك', '1000']);
                fputcsv($stream, ['01041200076148', 'بورسلان أرضيات 80×80', 'بورسلان', '500']);
            }

            rewind($stream);
            $content = stream_get_contents($stream);
            fclose($stream);

            return response($content, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename={$filename}",
                'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            ]);
        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في تحميل القالب: ' . $e->getMessage());
        }
    }

    public function report()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $products = Product::with('stock', 'orderItems')
            ->where('is_active', true)
            ->get()
            ->map(function ($product) {
                return [
                    'id'            => $product->id,
                    'item_code'     => $product->item_code,
                    'name'          => $product->name,
                    'type'          => $product->type,
                    'current_stock' => $product->getCurrentStock(),
                    'min_stock'     => $product->stock?->min_stock,
                    'is_low'        => $product->isLowStock(),
                    'total_sold'    => $product->orderItems->sum('total'),
                    'price'         => $product->price ?? 0,
                ];
            });

        $lowStockProducts = $products->filter(fn ($p) => $p['is_low']);
        $topSoldProducts  = $products->sortByDesc('total_sold')->take(10);

        return view('products.report', [
            'products'         => $products,
            'lowStockProducts' => $lowStockProducts,
            'topSoldProducts'  => $topSoldProducts,
        ]);
    }

    public function stockReport(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك بالدخول');
        }

        try {
            $query = Product::with('stock')->where('is_active', true);

            if ($request->type && $request->type != '') {
                $query->where('type', $request->type);
            }

            if ($request->size && $request->size != '') {
                $size = $request->size;
                $query->where(function($q) use ($size) {
                    $q->where('size', 'like', '%' . $size . '%')
                      ->orWhere('size', 'like', '%' . str_replace('×', '*', $size) . '%')
                      ->orWhere('size', 'like', '%' . str_replace('*', '×', $size) . '%')
                      ->orWhere('size', 'like', '%' . str_replace('x', '×', $size) . '%')
                      ->orWhere('size', 'like', '%' . str_replace('×', 'x', $size) . '%');
                });
            }

            if ($request->search && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('item_code', 'like', '%' . $search . '%')
                      ->orWhere('type', 'like', '%' . $search . '%')
                      ->orWhere('size', 'like', '%' . $search . '%');
                });
            }

            $products = $query->get();

            $requiredTypes = [
                'حوائط جلوريا', 'حوائط ايكو', 'أرضيات جلوريا', 'أرضيات ايكو',
                'HDC', 'UGC', 'بورسل', 'PORSLIM',
                'SUPER GLOSSY 61×122.5', 'SUPER GLOSSY 61×61'
            ];

            $existingTypes = Product::where('is_active', true)
                ->distinct('type')->pluck('type')->filter()->values()->toArray();

            $types = collect(array_merge($requiredTypes, $existingTypes))->unique()->values();

            $sizes = Product::where('is_active', true)
                ->whereNotNull('size')->where('size', '!=', '')
                ->distinct('size')->pluck('size')->filter()->sort()->values();

            return view('products.stock_report', [
                'products' => $products,
                'types'    => $types,
                'sizes'    => $sizes,
                'filters'  => $request->all()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in stockReport: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في تحميل التقرير: ' . $e->getMessage());
        }
    }

    public function adjustStock(Request $request, $id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')->with('error', 'غير مصرح لك');
        }

        $product = Product::with('stock')->findOrFail($id);

        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity'        => 'required|numeric|min:0',
            'notes'           => 'nullable|string|max:500',
        ]);

        try {
            $quantity = (float) $validated['quantity'];

            switch ($validated['adjustment_type']) {
                case 'add':
                    $product->increaseStock($quantity);
                    $msg = "تم إضافة {$quantity} للرصيد";
                    break;
                case 'subtract':
                    if (!$product->decreaseStock($quantity)) {
                        return back()->withErrors(['error' => 'الرصيد غير كافي']);
                    }
                    $msg = "تم خصم {$quantity} من الرصيد";
                    break;
                case 'set':
                    $product->setStockOnRelation($quantity);
                    $msg = "تم تعيين الرصيد على {$quantity}";
                    break;
            }

            return redirect()->route('products.show', $product->id)->with('success', $msg);
        } catch (Throwable $e) {
            Log::error('adjustStock error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
