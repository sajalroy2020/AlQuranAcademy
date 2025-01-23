<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassScheduleRequest;
use App\Models\ClassSchedule;
use App\Models\User;
use App\Models\Course;
use App\Models\Country;
use App\Models\ClassSlot;
use App\Models\TeacherApplyInfo;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClassScheduleController extends Controller
{
    use JsonResponseTrait;

    public function list(Request $request){
        if ($request->ajax()) {
            $schedule = ClassSchedule::with('course_list', 'class_slot')->orderBy('id', 'desc');
            
            return datatables($schedule)
                ->addIndexColumn()
                // ->addColumn('status', function ($schedule) {
                //     return getStatusHtml($schedule->status);
                // })
                ->addColumn('teacher_name', function ($state) {
                    return $state->teacher_list->name;
                })
                ->addColumn('subject', function ($state) {
                    return $state->course_list->subject_name;
                })
                ->addColumn('day', function ($state) {
                    return $state->day;
                })
                ->addColumn('time', function ($state) {
                    $timeSlots = []; 
                    foreach ($state->class_slot as $value) {
                        $formattedTime = ($value->start_time ? \Carbon\Carbon::parse($value->start_time)->format('h:i A') : null) . ' - ' . 
                                         ($value->end_time ? \Carbon\Carbon::parse($value->end_time)->format('h:i A') : null);
                        
                        if ($formattedTime) {
                            $timeSlots[] = $formattedTime;
                        }
                    }
                    return implode(', ', $timeSlots);
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
                ->rawColumns(['teacher_name', 'subject', 'day', 'time', 'status', 'action'])
                ->make(true);
        }
        $data['teachers'] = User::where(['role' => USER_ROLE_TEACHER, 'status' => USER_STATUS_ACTIVE])->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();
        $data['activeClassScheduleMenu'] = 'active';
        $data['activeClassSchedule'] = 'active';
        $data['pageTitle'] = __('Teacher Class Schedule');

        return view('admin.class-schedule.index', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __("Class Schedule Edit");
        $data['schedule'] = ClassSchedule::find($id);
        $data['teachers'] = User::where(['role' => USER_ROLE_TEACHER, 'status' => USER_STATUS_ACTIVE])->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();

        return view('admin.class-schedule.edit', $data);
    }

    public function store(ClassScheduleRequest $request)
    {
        DB::beginTransaction();
        try {
            $msg = __(MSG_CREATED_SUCCESSFULLY);  
            $days = is_string($request->days) ? explode(',', $request->days) : $request->days;
            $days = explode(',', $days[0]);

            $schedule = new ClassSchedule();
            $checkSchedule = ClassSchedule::where('teacher_id', $request->teacher_id)->whereIn('day', $days)->get();

            // Loop through each day in the request
            foreach ($days as $day) {
                $schedule = new ClassSchedule();
                $schedule->teacher_id = $request->teacher_id;
                $schedule->course_id = $request->course_id;
                $schedule->day = $day;
                $schedule->save();
                
                // Loop through each start time for the class
                foreach ($request->start_time as $key => $startTime) {
                    $requestedStartTime = \Carbon\Carbon::parse($startTime);
                    $requestedEndTime = \Carbon\Carbon::parse($request->end_time[$key]);

                    // Iterate through each 1-hour slot between start and end time
                    while ($requestedStartTime < $requestedEndTime) {
                        $nextSlotStart = $requestedStartTime->copy();
                        $nextSlotEnd = $nextSlotStart->copy()->addHour();

                        // Check for overlaps with existing schedules
                        foreach ($checkSchedule as $dataItem) {
                            $existingStartTime = \Carbon\Carbon::parse($dataItem->start_time);
                            $existingEndTime = \Carbon\Carbon::parse($dataItem->end_time);

                            // If the new slot overlaps with an existing slot, return error
                            if (
                                ($nextSlotStart >= $existingStartTime && $nextSlotStart < $existingEndTime) ||
                                ($nextSlotEnd > $existingStartTime && $nextSlotEnd <= $existingEndTime)
                            ) {
                                return $this->errorResponse([], __('Already booked for this time: ' . $nextSlotStart->format('h:i A')));
                            }
                        }

                        // Save this valid 1-hour slot
                        $slot = new ClassSlot();
                        $slot->class_schedule_id = $schedule->id;
                        $slot->day = $day;
                        $slot->start_time = $nextSlotStart;
                        $slot->end_time = $nextSlotEnd;
                        $slot->save();

                        // Move to the next 1-hour slot
                        $requestedStartTime = $nextSlotEnd;
                    }
                }
            }

            DB::commit();
            return $this->successResponse([], $msg);

        } catch (Exception $exception) {
            return $exception;
            DB::rollBack();
            Log::info($exception->getMessage());
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

    public function update(ClassScheduleRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', 0);
            if ($id != 0) {
                $id = $id;
                $schedule = ClassSchedule::find($id);
                $msg = __(MSG_UPDATED_SUCCESSFULLY);
            }

            $checkSchedule = ClassSchedule::where('teacher_id', $request->teacher_id)->where('day', $request->day)->get();

            if ($checkSchedule->isNotEmpty()) {
                foreach ($checkSchedule as $dataItem) {
                    $existingStartTime = \Carbon\Carbon::parse($dataItem->start_time)->format('h:i A');
                    $existingEndTime = \Carbon\Carbon::parse($dataItem->end_time)->format('h:i A');
                    $requestedStartTime = \Carbon\Carbon::parse($request->start_time)->format('h:i A');

                    if ( $existingStartTime <= $requestedStartTime && $existingEndTime > $requestedStartTime ) {
                        return $this->errorResponse([], __('Already booked for this time'));
                    }
                }
            }

            $schedule->day = $request->day;
            $schedule->teacher_id = $request->teacher_id;
            $schedule->course_id = $request->course_id;
            $schedule->start_time = $request->start_time;
            $schedule->end_time = $request->end_time;
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

    public function delete($id){
        try {
            $schedule = ClassSchedule::find($id);
            $schedule->delete();
            return $this->successResponse([], __(MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

    public function getFilterCourse(Request $request){
        $data['course'] = TeacherApplyInfo::where('teacher_id', $request->id)->with('course_list')->get();
        return view('admin.class-schedule.course-dropdown', $data)->render();
    }

    public function checkSchedule(Request $request){

        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['courseList'] = Course::where('status', STATUS_ACTIVE)->get();

        $data['activeScheduleCheck'] = 'active';
        $data['pageTitle'] = __('Check Class Schedule List');

        return view('admin.class-schedule.check', $data);
    }

    public function scheduleFilter(Request $request){
        $day = $request->input('day_list');
        $time = $request->input('start_time');

        if(empty($day) || !is_array($day)) {
            return response()->json(['error' => 'Select day'], 400);
        }

        $data['classes_list'] = User::where('role', USER_ROLE_TEACHER)
                                ->with(['Class_schedule' => function ($query) use ($day, $time) {
                                    $query->select(
                                        'class_schedules.teacher_id',
                                        'class_schedules.course_id',
                                        'class_schedules.id',
                                        'class_schedules.day',
                                        'class_schedules.start_time',
                                        'class_schedules.end_time',
                                        DB::raw("CASE 
                                            WHEN class_schedules.booking_status = 2 THEN 'Not Available'
                                            WHEN class_schedules.booking_status = 1 AND class_bookings.class_schedule_id IS NULL THEN 'Available'
                                            ELSE 'Not Available'
                                        END AS availability_status")
                                    )
                                    ->leftJoin('class_bookings', 'class_schedules.id', '=', 'class_bookings.class_schedule_id')
                                    ->where('class_schedules.status', STATUS_ACTIVE)
                                    ->when(!$time && $day, function ($query) use ($day) {
                                        $query->whereIn('class_schedules.day', $day);
                                    })
                                    ->when($time && $day, function ($query) use ($time, $day) {
                                        $query->where('class_schedules.start_time', $time)
                                        ->whereIn('class_schedules.day', $day);
                                    });
                                }])
                                ->select(
                                    'users.id',
                                    'users.name'
                                )
                                ->get();
        
        return view('admin.class-schedule.class-slot', $data)->render();
    }

}

