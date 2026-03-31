<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - جلوريا للسيراميك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
            background-color: #f8f9fa;
            padding: 20px;
            padding-top: 80px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .role-super_admin { background: #dc3545; color: white; }
        .role-sales_manager { background: #fd7e14; color: white; }
        .role-sales_rep { background: #28a745; color: white; }
        .role-factory { background: #17a2b8; color: white; }
        .admin-badge { background: #007bff; color: white; }

        @media (max-width: 768px) {
            body { padding-top: 70px; }
            table { font-size: 12px; }
            th, td { padding: 6px; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> إدارة المستخدمين</h1>
        <p>إضافة وتعديل وحذف المستخدمين وتحديد صلاحياتهم</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle"></i> إضافة مستخدم جديد
    </a>

    <div class="table-responsive">
         <table class="table table-bordered">
            <thead>
                  <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الدور</th>
                    <th>مدير</th>
                    <th>تاريخ التسجيل</th>
                    <th>الإجراءات</th>
                  </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                  <tr>
                      <td>{{ $user->id }}</td>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>
                        <span class="role-badge role-{{ $user->role }}">
                            @if($user->role == 'super_admin') مدير عام
                            @elseif($user->role == 'sales_manager') مدير مبيعات
                            @elseif($user->role == 'sales_rep') مندوب
                            @else مصنع @endif
                        </span>
                      </td>
                      <td>
                        @if($user->is_admin)
                            <span class="role-badge admin-badge">نعم</span>
                        @else
                            <span class="role-badge" style="background:#6c757d;">لا</span>
                        @endif
                      </td>
                      <td>{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</td>
                      <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        @if(auth()->id() != $user->id)
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                        @endif
                      </td>
                  </tr>
                @endforeach
            </tbody>
          </table>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>

</body>
</html>
