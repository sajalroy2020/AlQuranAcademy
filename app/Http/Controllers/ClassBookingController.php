<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ClassBooking;
use App\Models\ApplicantInfo;
use App\Models\ClassSchedule;
use App\Http\Requests\TeacherFilterRequest;

class ClassBookingController extends Controller
{
    public function list(Request $request){
        if ($request->ajax()) {
            $schedule = ClassBooking::query();
            return datatables($schedule)
                ->addIndexColumn()
                ->addColumn('status', function ($schedule) {
                    return getStatusHtml($schedule->status);
                })
                ->addColumn('teacher_name', function ($state) {
                    return $state->teacher_list->name;
                })
                ->addColumn('date', function ($state) {
                    return $state->date ? \Carbon\Carbon::parse($state->start_time)->format('d F Y') : null;
                })
                ->addColumn('start_time', function ($state) {
                    return $state->start_time ? \Carbon\Carbon::parse($state->start_time)->format('h:i A') : null;
                })
                ->addColumn('end_time', function ($state) {
                    return $state->end_time ? \Carbon\Carbon::parse($state->end_time)->format('h:i A') : null;
                })
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <button onclick="editCommonModal(\'' . route('admin.class-schedule.edit', $data->id) . '\'' . ', \'#editModal\')" class="border-0 bg-transparent" data-bs-toggle="modal" title="Edit">
                                    <img src="' . asset('dashboard/assets/img/icon/edit.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteCommonMethod(\'' . route('admin.class-schedule.delete', $data->id) . '\', \'classScheduleDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['teacher_name', 'date', 'start_time', 'end_time', 'status', 'action'])
                ->make(true);
        }

        $data['activeClassBookingMenu'] = 'active';
        $data['activeClassBooking'] = 'active';
        $data['pageTitle'] = __('Student Class Booking');

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

        $data['teachers'] = ClassSchedule::where(['date' => $date, 'course_id' => $courseId])->with('teacher_list')->get();

        return view('admin.class-booking.teacher-dropdown', $data)->render();
    }

    public function getTeacherClassList(Request $request){
        $date = $request->input('date');
        $teacherId = $request->input('teacher_id');

        if (!$date || !$teacherId) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        // $data['class_list'] = ClassSchedule::where(['date' => $date, 'teacher_id' => $teacherId])->get();

        $classes = ClassSchedule::query()
                    ->leftJoin('class_bookings', 'class_schedules.id', '=', 'class_bookings.class_schedule_id')
                    ->select(
                        'class_schedules.teacher_id',
                        'class_schedules.course_id',
                        'class_schedules.date',
                        'class_schedules.start_time',
                        'class_schedules.end_time',
                        DB::raw("CASE 
                            WHEN class_schedules.booking_status = 'not available' THEN 'Not Available'
                            WHEN class_schedules.booking_status = 'available' AND class_bookings.class_schedule_id IS NULL THEN 'Available'
                            ELSE 'Not Available'
                        END AS availability_status")
                    )
                    ->when($teacherId, function ($query) use ($teacherId) {
                        $query->where('class_schedules.teacher_id', $teacherId);
                    })
                    ->when($date, function ($query) use ($date) {
                        $query->where('class_schedules.date', $date);
                    })
                    ->groupBy('class_schedules.id')
                    ->get();


        return view('admin.class-booking.teacher-dropdown', $data)->render();
    }

}
