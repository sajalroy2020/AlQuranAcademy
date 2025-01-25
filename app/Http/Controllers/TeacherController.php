<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\State;
use App\Models\Course;
use App\Models\Country;
use App\Models\ActiveCheck;
use App\Models\ClassBooking;
use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Models\TeacherDetails;
// use Illuminate\Support\Carbon;
use App\Models\TeacherApplyInfo;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TeacherRequest; 

class TeacherController extends Controller
{
    use JsonResponseTrait;

    public function all(Request $request){

        $today = Carbon::now()->format('l');
        $class_schedule = ClassSchedule::where('day', $today)->where('status', ACTIVE)->get();
        
        foreach ($class_schedule as $key => $value) {
            $check_active = ActiveCheck::where('teacher_id', $value->teacher_id)->first();
            
            if ($check_active) {
                if ($check_active->notify_count == 0) {
                    $check_active->notify_count = 1;
                } elseif ($check_active->notify_count == 1) {
                    $check_active->notify_count = 2;
                } elseif ($check_active->notify_count == 2) {
                    $check_active->notify_count = 3;
                } elseif ($check_active->notify_count == 3) {
                    $check_active->notify_count = 0;
                    $check_active->is_active = DEACTIVATE;
                }
                $check_active->save();
            }
        }

        if ($request->ajax()) {
            $teacher = User::with('teacher_apply_info', 'check_active')->where('role', USER_ROLE_TEACHER)->orderBy('id', 'desc');

            return datatables($teacher)
                ->addIndexColumn()
                ->addColumn('image', function ($getData) {
                    if ($getData->check_active->is_active == ACTIVE) {
                        return "<div class='profile-image'>
                                    <img src='" . asset('dashboard/assets/img/user.png') . "' alt='image' />
                                    <span class='active-status'></span>
                                </div>";
                    }else{
                        return "<div class='profile-image'>
                                    <img src='" . asset('dashboard/assets/img/user.png') . "' alt='image' />
                                    <span class='active-status bg-danger'></span>
                                </div>";
                    }
                    
                })
                ->addColumn('status', function ($state) {
                    return getStatusHtml($state->status);
                })
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <a href="' . route('admin.teacher.edit', $data->id) . '" class="border-0 bg-transparent" title="Edit">
                                     <img src="' . asset('dashboard/assets/img/icon/edit.svg') . '" alt="edit" />
                                </a>
                                <button onclick="deleteCommonMethod(\'' . route('admin.teacher.delete', $data->id) . '\', \'teacherDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();
        $data['activeTeacher'] = 'active';
        $data['pageTitle'] = __('All Teacher');

        return view('admin.teacher.index', $data);
    }

    public function add(){
        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();
        $data['activeTeacher'] = 'active';
        $data['pageTitle'] = __('Add New Teacher');

        return view('admin.teacher.add', $data);
    }

    public function store(TeacherRequest $request){
        try {
            DB::beginTransaction();
            $id = $request->get('id', 0);

            if ($id != 0) {
                $id = $id;
                $teacher = User::find($id);
                $teacher_details = TeacherDetails::where('user_id', $id)->first();
                $msg = __(MSG_UPDATED_SUCCESSFULLY);
            } else {
                $teacher = new User();
                $teacher_details = new TeacherDetails();
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

            // teacher detail add record
            $teacher_details->user_id = $teacher->id;
            $teacher_details->father_name = $request->father_name;
            $teacher_details->marital_status = $request->marital_status;
            $teacher_details->guardian_phone = $request->guardian_phone;
            $teacher_details->present_address = $request->present_address;
            $teacher_details->permanent_address = $request->permanent_address;
            $teacher_details->edu_qualification = $request->edu_qualification;
            $teacher_details->training_qualification = $request->training_qualification;
            $teacher_details->other_occupation = $request->other_occupation;
            $teacher_details->occupation_details = $request->occupation_details;
            $teacher_details->class_device = $request->class_device;
            $teacher_details->is_all_agree = $request->is_all_agree;

            if ($request->hasFile("certificate_file")) {
                $file = $request->file("certificate_file");
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path("teacher-img/"), $imageName);
                $teacher_details->certificate_file = asset('teacher-img/' . $imageName);
            }

            if ($request->hasFile("nid_file")) {
                $file = $request->file("nid_file");
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path("teacher-img/"), $imageName);
                $teacher_details->nid_file = asset('teacher-img/' . $imageName);
            }

            $teacher_details->save();

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

            if (!$id) {
                $activeCheck = new ActiveCheck();
                $activeCheck->teacher_id = $teacher->id;
                $activeCheck->notify_count = 0;
                $activeCheck->save();
            }

            DB::commit();

            return $this->successResponse([], $msg);
        } catch (Exception $e) {
            // return $e;
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

    public function edit($id)
    {
        $data['teacher'] = User::with('teacher_apply_info', 'teacher_info')->find($id);
        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();
        $data['activeTeacher'] = 'active';
        $data['pageTitle'] = __('Edit Teacher');
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
            ActiveCheck::where('teacher_id', $id)->delete();
            TeacherApplyInfo::where('teacher_id', $id)->delete();
            $student->delete();
            return $this->successResponse([], __(MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

    public function activeRoute(){
        $userId = Auth::id();
        $activeStatus = ActiveCheck::where('teacher_id', $userId)->first();

        if ($activeStatus) {
            return response()->json([
                'status' => 'success',
                'data' => $activeStatus,
            ]);
        }
    }

    public function todayClass()
    {
        $today = Carbon::now()->format('l'); 
        
        $data['class'] = ClassBooking::whereHas('classSchedule', function ($query) use ($today) {
                        $query->where('day', $today)
                            ->where('teacher_id', auth()->id());
                    })->with('classSchedule')
                    ->get();

        $data['pageTitle'] = __('Today All Class');
        $data['activeTodayClass'] = 'active';
        $data['today'] = $today;

        return view('teacher.class-history.today-class', $data);
    }

    public function allClass()
    {
        $data['class'] = ClassSchedule::where('teacher_id', auth()->id())->get();

        $data['pageTitle'] = __('All Class List');
        $data['activeAllClass'] = 'active';
        $data['allday'] = ['Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];

        return view('teacher.class-history.all-class', $data);
    }

}
