<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ClassBooking;
use App\Models\ApplicantInfo;
use App\Models\ClassSchedule;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ClassBookingRequest;

class ClassBookingController extends Controller
{
    use JsonResponseTrait;

    public function list(Request $request){
        if ($request->ajax()) {
            $schedule = ClassBooking::with('classSchedule', 'student', 'classSchedule.teacher_list', 'classSchedule.course_list');

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
                ->addColumn('date', function ($schedule) {
                    return $schedule->classSchedule->date ? \Carbon\Carbon::parse($schedule->classSchedule->date)->format('d F Y') : null;
                })
                ->addColumn('start_time', function ($schedule) {
                    return $schedule->classSchedule->start_time ? \Carbon\Carbon::parse($schedule->classSchedule->start_time)->format('h:i A') : null;
                })
                ->addColumn('end_time', function ($schedule) {
                    return $schedule->classSchedule->end_time ? \Carbon\Carbon::parse($schedule->classSchedule->end_time)->format('h:i A') : null;
                })
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <button onclick="deleteCommonMethod(\'' . route('admin.class-booking.delete', $data->id) . '\', \'classBookingDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['status', 'student_name', 'teacher_name', 'subject', 'date', 'start_time', 'end_time',  'action'])
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
        $date = $request->input('date');
        $courseId = $request->input('course_id');

        if (!$date || !$courseId) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $data['teachers'] = User::with('Class_schedule')
                            ->where('role', USER_ROLE_TEACHER)
                            ->when($date, function ($query) use ($date) {
                                $query->whereHas('Class_schedule', function ($q) use ($date) {
                                    $q->where('date', $date);
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
        $date = $request->input('date');
        $teacherId = $request->input('teacher_id');

        if (!$date || !$teacherId) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $data['classes_list'] = ClassSchedule::query()
                    ->leftJoin('class_bookings', 'class_schedules.id', '=', 'class_bookings.class_schedule_id')
                    ->select(
                        'class_schedules.teacher_id',
                        'class_schedules.course_id',
                        'class_schedules.id',
                        'class_schedules.date',
                        'class_schedules.start_time',
                        'class_schedules.end_time',
                        DB::raw("CASE 
                            WHEN class_schedules.booking_status = 2 THEN 'Not Available'
                            WHEN class_schedules.booking_status = 1 AND class_bookings.class_schedule_id IS NULL THEN 'Available'
                            ELSE 'Not Available'
                        END AS availability_status")
                    )
                    ->when($teacherId, function ($query) use ($teacherId) {
                        $query->where('class_schedules.teacher_id', $teacherId);
                    })
                    ->when($date, function ($query) use ($date) {
                        $query->where('class_schedules.date', $date);
                    })->get();

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
            $schedule->status = isset($request->status) ? $request->status : STATUS_ACTIVE;
            $schedule->save();

            ClassSchedule::where('id', $request->class_schedule_id)->update(['booking_status' => CLASS_BOOKED]);

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
            $class = ClassBooking::find($id);

            if ($class) {
                $classScheduleId = $class->class_schedule_id;
                $class->delete();
                ClassSchedule::where('id', $classScheduleId)->update(['booking_status' => CLASS_AVAILABLE]);
            }

            DB::commit();
            return $this->successResponse([], $msg);

        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

}
