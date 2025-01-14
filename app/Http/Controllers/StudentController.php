<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\State;
use App\Models\Course;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    use JsonResponseTrait;

    public function allStudent(Request $request){
        if ($request->ajax()) {
            $student = User::where('role', USER_ROLE_STUDENT);

            return datatables($student)
                ->addIndexColumn()
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <button onclick="editCommonModal(\'' . route('student.edit', $data->id) . '\'' . ', \'#editModal\')" class="border-0 bg-transparent" data-bs-toggle="modal" title="Edit">
                                    <img src="' . asset('dashboard/assets/img/icon/edit.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteCommonMethod(\'' . route('student.delete', $data->id) . '\', \'studentDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();

        $data['activeStudent'] = 'active';
        $data['pageTitle'] = __('All Student');

        return view('admin.student.index', $data);
    }

    public function studentStore(UserRequest $request){
        try {
            DB::beginTransaction();
            $student = new User();
            $student->name = $request->name;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->gender = $request->gender;
            // $student->course_id = $request->course_id;
            $student->country_id = $request->country_id;
            $student->state_id = $request->state_id;
            $student->dob = $request->dob;
            $student->password = $request->password;
            $student->save();
            DB::commit();

            return $this->successResponse([], __(MSG_CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

    public function edit($id)
    {
        $data['student'] = User::find($id);
        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();
        $data['state'] = State::where('status', STATUS_ACTIVE)->get();

        return view('admin.student.edit', $data);
    }

    public function getState(Request $request){
        $data['state'] = State::where('country_id', $request->id)->get();
        return view('admin.student.state-dropdown', $data)->render();
    }

    public function delete($id){
        try {
            $student = User::find($id);
            $student->delete();
            return $this->successResponse([], __(MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

}
