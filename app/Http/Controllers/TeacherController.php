<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\State;
use App\Models\Course;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\TeacherApplyInfo;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TeacherRequest;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    use JsonResponseTrait;

    public function all(Request $request){
        if ($request->ajax()) {
            $teacher = User::with('teacher_apply_info')->where('role', USER_ROLE_TEACHER);

            return datatables($teacher)
                ->addIndexColumn()
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <button onclick="editCommonModal(\'' . route('admin.teacher.edit', $data->id) . '\'' . ', \'#editModal\')" class="border-0 bg-transparent" data-bs-toggle="modal" title="Edit">
                                    <img src="' . asset('dashboard/assets/img/icon/edit.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteCommonMethod(\'' . route('admin.teacher.delete', $data->id) . '\', \'teacherDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();
        $data['activeTeacher'] = 'active';
        $data['pageTitle'] = __('All Teacher');

        return view('admin.teacher.index', $data);
    }

    public function store(TeacherRequest $request){
        try {
            DB::beginTransaction();
            $id = $request->get('id', 0);

            if ($id != 0) {
                $id = $id;
                $teacher = User::find($id);
                $msg = __(MSG_UPDATED_SUCCESSFULLY);
            } else {
                $teacher = new User();
                $msg = __(MSG_CREATED_SUCCESSFULLY);
            }

            $teacher->name = $request->name;
            $teacher->email = $request->email;
            $teacher->phone = $request->phone;
            $teacher->gender = $request->gender;
            $teacher->role = USER_ROLE_TEACHER;
            $teacher->country_id = $request->country_id;
            $teacher->state_id = $request->state_id;
            $teacher->dob = $request->dob;
            $teacher->password = $request->password;
            $teacher->save();

            // teacher multipole subject add record
            if ($request->course_id) {
                TeacherApplyInfo::where('teacher_id', $teacher->id)->delete();

                foreach ($request->course_id as $key => $value) {
                    $teacherInfo = new TeacherApplyInfo();
                    
                    $teacherInfo->teacher_id = $teacher->id;
                    $teacherInfo->course_id = $value;
                    $teacherInfo->save();
                }
            }

            DB::commit();

            return $this->successResponse([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

    public function edit($id)
    {
        $data['teacher'] = User::with('teacher_apply_info')->find($id);
        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();
        $data['state'] = State::where('status', STATUS_ACTIVE)->get();

        return view('admin.teacher.edit', $data);
    }

    public function getState(Request $request){
        $data['state'] = State::where('country_id', $request->id)->get();
        return view('admin.teacher.state-dropdown', $data)->render();
    }

    public function delete($id){
        try {
            $student = User::find($id);
            TeacherApplyInfo::where('teacher_id', $id)->delete();
            $student->delete();
            return $this->successResponse([], __(MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

}
