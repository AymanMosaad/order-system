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
    /**
     * عرض كل الأصناف (للمدير فقط)
     */
    public function index(Request $request)
    {
        // التحقق من أن المستخدم مدير
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك بعرض الأصناف');
        }

        try {
            $query = Product::with('stock')->where('is_active', true);

            // فلترة حسب البحث
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('item_code', 'like', '%' . $request->search . '%');
                });
            }

            // فلترة حسب النوع
            if ($request->type) {
                $query->where('type', $request->type);
            }

            $products = $query->latest()->paginate(20);

            // الأنواع المطلوبة يدوياً
            $requiredTypes = [
                'حوائط جلوريا',
                'حوائط ايكو',
                'أرضيات جلوريا',
                'أرضيات ايكو',
                'HDC',
                'UGC',
                'بورسل',
                'PORSLIM',
                'SUPER GLOSSY 61×122.5',
                'SUPER GLOSSY 61×61'
            ];

            // جلب الأنواع الموجودة في قاعدة البيانات
            $existingTypes = Product::where('is_active', true)
                ->distinct('type')
                ->pluck('type')
                ->filter()
                ->values()
                ->toArray();

            // دمج الأنواع المطلوبة مع الموجودة وإزالة المكرر
            $types = collect(array_merge($requiredTypes, $existingTypes))->unique()->values();

            return view('products.index', [
                'products' => $products,
                'types' => $types,
                'search' => $request->search,
                'selectedType' => $request->type
            ]);
        } catch (\Exception $e) {
            Log::error('Error in products index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في عرض الأصناف');
        }
    }

    /**
     * صفحة إنشاء صنف جديد (للمدير فقط)
     */
    public function create()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
        }

        return view('products.create');
    }

    /**
     * حفظ صنف جديد (للمدير فقط)
     */
    public function store(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
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
     * عرض تفاصيل صنف واحد (للمدير فقط)
     */
    public function show($id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
        }

        try {
            $product = Product::with('stock', 'orderItems.order')->findOrFail($id);
            return view('products.show', ['product' => $product]);
        } catch (\Exception $e) {
            Log::error('Error in product show: ' . $e->getMessage());
            return redirect()->route('products.index')
                ->with('error', 'المنتج غير موجود');
        }
    }

    /**
     * صفحة تعديل الصنف (للمدير فقط)
     */
    public function edit($id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
        }

        try {
            $product = Product::with('stock')->findOrFail($id);
            return view('products.edit', ['product' => $product]);
        } catch (\Exception $e) {
            Log::error('Error in product edit: ' . $e->getMessage());
            return redirect()->route('products.index')
                ->with('error', 'المنتج غير موجود');
        }
    }

    /**
     * حفظ التعديلات (للمدير فقط)
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
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
     * حذف صنف (للمدير فقط)
     */
    public function destroy($id)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
        }

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
     * AJAX: جلب الصنف كامل (متاح للمستخدمين المسجلين)
     */
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
     * استيراد الأصناف من Excel/CSV (للمدير فقط)
     */
    public function import(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
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

            return redirect()
                ->route('products.index')
                ->with('success', $message)
                ->with('import_errors', $import->errors);
        } catch (Throwable $e) {
            Log::error('Import error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في الاستيراد: ' . $e->getMessage()]);
        }
    }

    /**
     * تحميل قالب Excel مع البيانات الموجودة (للمدير فقط)
     */
    public function downloadTemplate()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
        }

        try {
            // جلب جميع المنتجات النشطة
            $products = Product::where('is_active', true)
                ->with('stock')
                ->get();

            // إنشاء ملف CSV مع البيانات الموجودة
            $headers = ['كود الصنف', 'اسم الصنف', 'الوحدة', 'الرصيد'];
            $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';

            $stream = fopen('php://temp', 'w+');
            fwrite($stream, "\xEF\xBB\xBF"); // BOM للعربية
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
                // إذا كان الجدول فاضي، أضف صفوف مثال
                fputcsv($stream, ['01041200076147', 'بلاط حوائط 60×60 لامع', 'سيراميك', '1000']);
                fputcsv($stream, ['01041200076148', 'بورسلان أرضيات 80×80', 'بورسلان', '500']);
                fputcsv($stream, ['01041200076149', 'رخام صناعي 60×120', 'رخام', '250']);
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

    /**
     * صفحة استيراد المنتجات (للمدير فقط)
     */
    public function importPage()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
        }

        return view('products.import');
    }

    /**
     * تقرير الأصناف والأرصدة (للمدير فقط)
     */
    public function report()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك');
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

    /**
     * تقرير الرصيد حسب النوع والمقاس
     */
    public function stockReport(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('orders.userDashboard')
                ->with('error', 'غير مصرح لك بالدخول');
        }

        try {
            $query = Product::with('stock')->where('is_active', true);

            // فلترة حسب النوع - تأكد من أنها تعمل
            if ($request->type && $request->type != '') {
                $query->where('type', $request->type);
            }

            // فلترة حسب المقاس
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

            // فلترة حسب البحث
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

            // الأنواع المطلوبة يدوياً للفلترة
            $requiredTypes = [
                'حوائط جلوريا',
                'حوائط ايكو',
                'أرضيات جلوريا',
                'أرضيات ايكو',
                'HDC',
                'UGC',
                'بورسل',
                'PORSLIM',
                'SUPER GLOSSY 61×122.5',
                'SUPER GLOSSY 61×61'
            ];

            // جلب الأنواع الموجودة في قاعدة البيانات
            $existingTypes = Product::where('is_active', true)
                ->distinct('type')
                ->pluck('type')
                ->filter()
                ->values()
                ->toArray();

            // دمج الأنواع المطلوبة مع الموجودة وإزالة المكرر
            $types = collect(array_merge($requiredTypes, $existingTypes))->unique()->values();

            // جلب المقاسات الموجودة في قاعدة البيانات
            $sizes = Product::where('is_active', true)
                ->whereNotNull('size')
                ->where('size', '!=', '')
                ->distinct('size')
                ->pluck('size')
                ->filter()
                ->sort()
                ->values();

            return view('products.stock_report', [
                'products' => $products,
                'types' => $types,
                'sizes' => $sizes,
                'filters' => $request->all()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in stockReport: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ في تحميل التقرير: ' . $e->getMessage());
        }
    }
}
