<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassScheduleRequest;
use App\Models\ClassSchedule;
use App\Models\User;
use App\Models\Course;
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
            $schedule = ClassSchedule::query();
            return datatables($schedule)
                ->addIndexColumn()
                // ->addColumn('status', function ($schedule) {
                //     return getStatusHtml($schedule->status);
                // })
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
            $id = $request->get('id', 0);
            if ($id != 0) {
                $id = $id;
                $schedule = ClassSchedule::find($id);
                $msg = __(MSG_UPDATED_SUCCESSFULLY);

            } else {
                $schedule = new ClassSchedule();
                $msg = __(MSG_CREATED_SUCCESSFULLY);
            }

            $checkSchedule = ClassSchedule::where('teacher_id', $request->teacher_id)->where('date', $request->date)->get();

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

            $schedule->date = $request->date;
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

}

