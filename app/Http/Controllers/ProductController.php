<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Throwable;

class ProductController extends Controller
{
    /**
     * عرض كل الأصناف
     */
    public function index()
    {
        $products = Product::with('stock')
            ->where('is_active', true)
            ->get();

        return view('products.index', ['products' => $products]);
    }

    /**
     * صفحة إنشاء صنف جديد
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * حفظ صنف جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code'     => 'required|string|unique:products,item_code',
            'type'          => 'required|string',
            'name'          => 'required|string|max:255',
            'color'         => 'nullable|string|max:255',
            'size'          => 'nullable|string|max:255',
            'current_stock' => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::create([
                'item_code' => $validated['item_code'],
                'type'      => $validated['type'],
                'name'      => $validated['name'],
                'color'     => $validated['color'] ?? null,
                'size'      => $validated['size'] ?? null,
                'is_active' => true,
            ]);

            ProductStock::create([
                'product_id'    => $product->id,
                'current_stock' => $validated['current_stock'],
                'min_stock'     => $validated['min_stock'],
            ]);

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'تم إنشاء الصنف بنجاح');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Create product error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل صنف واحد
     */
    public function show($id)
    {
        $product = Product::with('stock', 'orderItems.order')->findOrFail($id);

        return view('products.show', ['product' => $product]);
    }

    /**
     * صفحة تعديل الصنف
     */
    public function edit($id)
    {
        $product = Product::with('stock')->findOrFail($id);

        return view('products.edit', ['product' => $product]);
    }

    /**
     * حفظ التعديلات
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'item_code' => 'required|string|unique:products,item_code,' . $product->id,
            'type'      => 'required|string',
            'name'      => 'required|string|max:255',
            'color'     => 'nullable|string|max:255',
            'size'      => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'min_stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'item_code' => $validated['item_code'],
                'type'      => $validated['type'],
                'name'      => $validated['name'],
                'color'     => $validated['color'] ?? null,
                'size'      => $validated['size'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            if ($product->stock) {
                $product->stock->update([
                    'min_stock' => $validated['min_stock'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('products.show', $product->id)
                ->with('success', 'تم تحديث الصنف بنجاح');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Update product error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * حذف صنف
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->orderItems()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف صنف موجود في طلبيات']);
        }

        try {
            DB::beginTransaction();

            if ($product->stock) {
                $product->stock->delete();
            }

            $product->delete();

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'تم حذف الصنف بنجاح');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Delete product error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * تعديل الرصيد (تعديل يدوي)
     */
    public function adjustStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'current_stock' => 'required|integer|min:0',
        ]);

        try {
            if ($product->stock) {
                if (method_exists($product->stock, 'setStock')) {
                    $product->stock->setStock($validated['current_stock']);
                } else {
                    $product->stock->update(['current_stock' => $validated['current_stock']]);
                }
            }

            return redirect()
                ->route('products.show', $product->id)
                ->with('success', 'تم تحديث الرصيد بنجاح');
        } catch (Throwable $e) {
            Log::error('Adjust stock error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * صفحة استيراد الأصناف
     */
    public function importPage()
    {
        return view('products.import');
    }

    /**
     * استيراد الأصناف من Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:51200', // حتى 50MB
        ]);

        try {
            $import = new ProductsImport();
            Excel::import($import, $request->file('file'));

            $summary = [
                'processed' => $import->successCount + $import->errorCount,
                'success'   => $import->successCount,
                'failed'    => $import->errorCount,
            ];

            return redirect()
                ->route('products.index') // أو ->route('products.importPage')
                ->with('status', 'تم استيراد الملف ومعالجة البيانات.')
                ->with('import_summary', $summary)
                ->with('import_errors', $import->errors);
        } catch (Throwable $e) {
            Log::error('Import error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['file' => 'خطأ في الاستيراد: ' . $e->getMessage()]);
        }
    }

    /**
     * تحميل نموذج Excel (CSV مع BOM لدعم العربي)
     */
    public function downloadTemplate()
    {
        $filename = 'template_products_' . date('Y-m-d') . '.csv';

        // الأعمدة المتوقعة حسب الشيت
        $headers = ['كود الصنف', 'اسم الصنف', 'الوحدة', 'الرصيد'];

        // إنشاء CSV بالذاكرة + BOM لعرض العربي صحيح في Excel (ويندوز)
        $stream = fopen('php://temp', 'w+');
        fwrite($stream, "\xEF\xBB\xBF"); // UTF-8 BOM
        fputcsv($stream, $headers);
        rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);

        return response($content, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
        ]);
    }

    /**
     * تقرير الأصناف والأرصدة
     */
    public function report()
    {
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
}
