<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
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

    public function saveRecord(Request $request)
    {
        DB::beginTransaction();
        try{
            $employees = Employee::where('email', '=', $request->email)->first();
            if($employees === null) {
                $employee = new Employee();
                $employee->name = $request->name;
                $employee->email = $request->email;
                $employee->birth_date = $request->birthDate;
                $employee->gender = $request->gender;
                $employee->employee_id = $request->employee_id;
                $employee->company = $request->company;
                $employee->save();

                for($i = 0; $i< count($request->id_count); $i++) {
                    $module_permissions = [
                        'employee_id' => $request->employee_id,
                        'module_permission' => $request->permission[$i],
                        'id_count' => $request->id_count[$i],
                        'read' => $request->read[$i],
                        'create' => $request->create[$i],
                        'write' => $request->write[$i],
                        'delete' => $request->delete[$i],
                        'import' => $request->import[$i],
                        'export' => $request->export[$i],
                    ];

                    DB::table('module_permissions')->insert($module_permissions);
                }
                DB::commit();
                return redirect()->route('all/employee/card')->with('success', 'Add new employee successfully');
            }else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Add new employee exits');
            }
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Add new employee fail');
        }
    }

    public function listAllEmployee()
    {
        $users = DB::table('users')->join('employees', 'users.rec_id', '=', 'employees.employee_id')->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')->get();
        $userList = DB::table('users')->get();
        $permissionList = DB::table('permission_lists')->get();
        return view('form.employeelist', compact('users', 'userList', 'permissionList'));

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
