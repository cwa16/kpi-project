<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $department = $request->query('department');
        $status = $request->query('status');

        $deptList = DB::table('departments')->get();
        $statusList = DB::table('employees')->select('status')->distinct()->get();
        $query = DB::table('employees')->where('is_active', 1);
        if ($department && $status) {
            $users = $query->where('department_id', $department)->where('status', $status)->paginate(20);
        } elseif ($department) {
            $users = $query->where('department_id', $department)->paginate(20);
        } elseif ($status) {
            $users = $query->where('status', $status)->paginate(20);
        } else {
            $users = $query->paginate(20);
        }

        return view('users.list-user', ['title' => 'Employees', 'desc' => 'Master Employee', 'users' => $users, 'deptList' => $deptList, 'statusList' => $statusList]);
    }

    public function create()
    {
        $statusList = DB::table('employees')->select('status')->distinct()->get();
        $deptList = DB::table('departments')->get();

        return view('users.create-user', ['title' => 'Employees', 'desc' => 'Create Employee', 'statusList' => $statusList, 'deptList' => $deptList]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'nik' => 'required',
            // 'email' => 'required',
            'department_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please fill all the fields.');
        }

        $hashedPassword = Hash::make($request->password);
        // dd($hashedPassword);

        Employee::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'email' => $request->email,
            'password' => $hashedPassword,
            'department_id' => $request->department_id,
            'status' => $request->status,
            'occupation' => $request->occupation,
            'grade' => $request->grade,
            'input_type' => $request->input_type,
            'role' => $request->role,
            'is_active' => 1,
            'phone' => $request->phone,
        ]);

        return redirect()->route('user.index')->with('success', 'Employee created successfully.');
    }

    public function edit($id)
    {
        $user = DB::table('employees')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->where('employees.id', $id)->select('departments.*', 'employees.*', 'employees.id as employee_id', 'departments.name as department', 'departments.id as department_id')->first();
        $statusList = DB::table('employees')->select('status')->distinct()->get();
        $deptList = DB::table('departments')->get();
        // dd($user->is_active);

        return view('users.edit-user', ['title' => 'Employees', 'desc' => 'Edit Employee', 'user' => $user, 'statusList' => $statusList, 'deptList' => $deptList]);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'nik' => 'required',
            // 'email' => 'required|email|unique:employees,email,' . $id,
            'department_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please fill all the fields.');
        }

        $isActive = $request->is_active == 'aktif' ? true : false;

        $data = [
            'name' => $request->name,
            'nik' => $request->nik,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'status' => $request->status,
            'occupation' => $request->occupation,
            'grade' => $request->grade,
            'input_type' => $request->input_type,
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $isActive,
        ];



        // Check if password is provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $res = Employee::where('id', $id)->update($data);
        // dd($res);


        return redirect()->route('user.index')->with('success', 'Employee updated successfully.');
    }

    public function softDelete($id)
    {
        Employee::where('id', $id)->update(['is_active' => false]);

        return redirect()->route('user.index')->with('success', 'Employee deleted successfully.');
    }

    public function updatePassword(Request $request)
    {
        // dd($request->all()); 
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please fill all the fields.');
        }

        Employee::where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password),
                'last_password_reset_at' => now(),
            ]);

        return redirect()->to('/')->with('success', 'Password reset successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $users = DB::table('employees')->where('name', 'like', "%$search%")
            ->orWhere('nik', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->paginate(20);

        // Return only the table rows as a partial view
        return view('users.partials-user-table', compact('users'))->render();
    }
}
