<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassSlot;
use Illuminate\Http\Request;
use App\Models\ClassBooking;
use App\Models\ApplicantInfo;
use App\Models\ClassSchedule;
use Illuminate\Support\Carbon;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ClassBookingRequest;

class ClassBookingController extends Controller
{
    use JsonResponseTrait;

    public function list(Request $request){
        if ($request->ajax()) {
            $schedule = ClassBooking::with('classSchedule', 'student', 'classSchedule.teacher_list', 'classSchedule.course_list', 'slot')->orderBy('id', 'desc');
            return datatables($schedule)
                ->addIndexColumn()
                ->addColumn('status', function ($schedule) {
                    return getStatusHtml($schedule->status);
                })
                ->addColumn('student_name', function ($schedule) {
                    return $schedule->student->name;
                })
                ->addColumn('teacher_name', function ($schedule) {
                    return $schedule->classSchedule->teacher_list->name;
                })
                ->addColumn('subject', function ($schedule) {
                    return $schedule->classSchedule->course_list->subject_name;
                })
                ->addColumn('day', function ($schedule) {
                    return $schedule->classSchedule->day;
                })
                ->addColumn('time', function ($state) {
                    return ($state->slot->start_time ? \Carbon\Carbon::parse($state->slot->start_time)->format('h:i A') : null) . ' - ' . ($state->slot->end_time ? \Carbon\Carbon::parse($state->slot->end_time)->format('h:i A') : null);
                })
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <button onclick="deleteCommonMethod(\'' . route('admin.class-booking.delete', $data->id) . '\', \'classBookingDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['status', 'student_name', 'teacher_name', 'subject', 'day', 'time', 'action'])
                ->make(true);
        }

        $data['activeClassBookingMenu'] = 'active';
        $data['activeClassBooking'] = 'active';
        $data['pageTitle'] = __('Student Class List');

        return view('admin.class-booking.index', $data);
    }

    public function add(){

        $data['teachers'] = User::where(['role' => USER_ROLE_TEACHER, 'status' => USER_STATUS_ACTIVE])->get();
        $data['students'] = User::where(['role' => USER_ROLE_STUDENT, 'status' => USER_STATUS_ACTIVE])->get();

        $data['activeClassBookingMenu'] = 'active';
        $data['activeClassBooking'] = 'active';
        $data['pageTitle'] = __('Add New Class Booking');

        return view('admin.class-booking.add', $data);
    }

    public function getFilterCourse(Request $request){
        $data['course'] = ApplicantInfo::where('student_id', $request->id)->with('course_list')->get();
        return view('admin.class-booking.course-dropdown', $data)->render();
    }

    public function getTeacherFilter(Request $request){
        $day = $request->input('day');
        $courseId = $request->input('course_id');

        if (!$day || !$courseId) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $data['teachers'] = User::with('Class_schedule')
                            ->where('role', USER_ROLE_TEACHER)
                            ->when($day, function ($query) use ($day) {
                                $query->whereHas('Class_schedule', function ($q) use ($day) {
                                    $q->where('day', $day);
                                });
                            })
                            ->when($courseId, function ($query) use ($courseId) {
                                $query->whereHas('Class_schedule', function ($q) use ($courseId) {
                                    $q->where('course_id', $courseId);
                                });
                            })
                            ->get();    

        return view('admin.class-booking.teacher-dropdown', $data)->render();
    }

    public function getTeacherClassList(Request $request){
        $day = $request->input('day');
        $teacherId = $request->input('teacher_id');

        if (!$day || !$teacherId) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $data['classes_list'] = ClassSlot::query()
            ->join('class_schedules', 'class_slots.class_schedule_id', '=', 'class_schedules.id')
            ->leftJoin('class_bookings', 'class_slots.id', '=', 'class_bookings.class_slot_id')
            ->select(
                'class_slots.id',
                'class_slots.start_time',
                'class_slots.end_time',
                'class_schedules.teacher_id',
                'class_schedules.day',
                'class_schedules.id as class_schedule_id',
                DB::raw('IF(class_bookings.id IS NOT NULL, 1, 0) as is_booked')
            )
            ->when($teacherId, function ($query) use ($teacherId) {
                $query->where('class_schedules.teacher_id', $teacherId);
            })
            ->when($day, function ($query) use ($day) {
                $query->where('class_schedules.day', $day);
            })
            ->get();
                    
        return view('admin.class-booking.class-slot', $data)->render();
    }

    public function store(ClassBookingRequest $request)
    {
        DB::beginTransaction();
        try {
            $schedule = new ClassBooking();
            $msg = __('Class Booking Successfully');

            $schedule->student_id = $request->student_id;
            $schedule->class_schedule_id = $request->class_schedule_id;
            $schedule->class_slot_id = $request->class_slot_id;
            $schedule->status = isset($request->status) ? $request->status : STATUS_ACTIVE;
            $schedule->save();

            DB::commit();
            return $this->successResponse([], $msg);

        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
             ClassBooking::find($id)->delete();
            DB::commit();
            return $this->successResponse([], $msg);

        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

}
