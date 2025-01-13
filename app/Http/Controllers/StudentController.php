<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    use ResponseTrait;

    public function allStudent(Request $request){

        if ($request->ajax()) {
            $student = Student::query();
            return datatables($student)
                ->addIndexColumn()
                ->addColumn('action', function ($data){
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                                <li class="d-flex gap-2">
                                    <button onclick="getEditModal(\'' . route('index', $data->id) . '\'' . ', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="'.__('Edit').'">
                                        <img src="' . asset('assets/images/icon/edit.svg') . '" alt="edit" />
                                    </button>
                                    <button onclick="deleteItem(\'' . route('student.delete', $data->id) . '\', \'studentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="'.__('Delete').'">
                                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                                    </button>
                                </li>
                            </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['activeStudent'] = 'active';
        $data['pageTitle'] = __('All Student');
        return view('admin.student.index', $data);
    }

    public function studentStore(StudentRequest $request){
        try {
            DB::beginTransaction();
            $student = new Student();
            $student->name = $request->name;
            $student->email = $request->email;
            $student->password = $request->password;
            $student->city = $request->city;
            $student->address = $request->address;
            $student->save();
            DB::commit();
            return $this->success([], getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function studentDelete($id){
        try {
            $student = Student::find($id);
            $student->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

}
