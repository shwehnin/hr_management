<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PermissionList;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cardAllEmployee()
    {
        $users = DB::table('users')
                ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                ->get();
        $userList = DB::table('users')->get();
        $permission_lists = PermissionList::get();
        return view('form.allemployeecard', compact('users', 'userList', 'permission_lists'));
    }

    /* page departments */
    public function index()
    {
        $departments = Department::paginate('20');
        return view('form.departments', compact('departments'));
    }

    /* save record department */
    public function saveRecordDepartment(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try{
            $department = Department::where('department', '=', $request->department_name)->first();
            if($department === null) {
                $department = new Department();
                $department->department = $request->department_name;
                $department->save();
                DB::commit();
                return redirect()->route('form/departments/page')->with('success', 'Add new department successfully!');
            }else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Department name already exist!');
            }
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Add new department fail!');
        }
    }

    public function updateRecordDepartment(Request $request)
    {
        DB::beginTransaction();
        try{
            $department = [
                'id'=>$request->id,
                'department'=>$request->department_name,
            ];
            department::where('id',$request->id)->update($department);
            DB::commit();
            return redirect()->route('form/departments/page')->with('success', 'Update department successfully!');
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Update department fail!');
        }
    }

    public function deleteRecordDepartment(Request $request)
    {
        try {
            Department::destroy($request->id);
            return redirect()->back()->with('success', 'Department deleted');
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Delete department fail');
        }
    }

//    public function cardAllEmployee()
//    {
//        $employee = Employee::paginate('20');
//        return view('employee.employee', compact('employee'));
//    }
}
