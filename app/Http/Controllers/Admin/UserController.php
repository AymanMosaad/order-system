<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index()
    {
        // التحقق من أن المستخدم الحالي هو مدير عام
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'غير مصرح لك بالدخول - هذه الصفحة مخصصة للمدير العام فقط');
        }

        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * صفحة إضافة مستخدم جديد
     */
    public function create()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'غير مصرح لك بالدخول');
        }

        return view('admin.users.create');
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'غير مصرح لك بالدخول');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:super_admin,sales_manager,sales_rep,factory',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_admin' => $request->role == 'super_admin' || $request->role == 'sales_manager' ? 1 : 0,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * صفحة تعديل مستخدم
     */
    public function edit($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'غير مصرح لك بالدخول');
        }

        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'غير مصرح لك بالدخول');
        }

        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:super_admin,sales_manager,sales_rep,factory',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_admin' => $request->role == 'super_admin' || $request->role == 'sales_manager' ? 1 : 0,
        ]);

        if ($request->password) {
            $request->validate(['password' => 'string|min:6|confirmed']);
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    /**
     * حذف مستخدم
     */
    public function destroy($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'غير مصرح لك بالدخول');
        }

        $user = User::findOrFail($id);

        if ($user->id == auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
}
