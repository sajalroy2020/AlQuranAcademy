<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    use JsonResponseTrait;

    public function all(Request $request){
        if ($request->ajax()) {
            $course = Course::query()->orderBy('id', 'desc');

            return datatables($course)
                ->addIndexColumn()
                ->addColumn('status', function ($course) {
                    return getStatusHtml($course->status);
                })
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <button onclick="editCommonModal(\'' . route('admin.course.edit', $data->id) . '\'' . ', \'#editModal\')" class="border-0 bg-transparent" data-bs-toggle="modal" title="Edit">
                                    <img src="' . asset('dashboard/assets/img/icon/edit.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteCommonMethod(\'' . route('admin.course.delete', $data->id) . '\', \'courseDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data['activeCourse'] = 'active';
        $data['pageTitle'] = __('All Book');

        return view('admin.course.index', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __("Course Edit");
        $data['course'] = Course::find($id);
        return view('admin.course.edit', $data);
    }

    public function store(CourseRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', 0);
            if ($id != 0) {
                $id = $id;
                $course = Course::find($id);
                $msg = __(MSG_UPDATED_SUCCESSFULLY);

            } else {
                $course = new Course();
                $msg = __(MSG_CREATED_SUCCESSFULLY);
            }

            $course->subject_name = $request->subject_name;
            $course->status = isset($request->status) ? $request->status : STATUS_ACTIVE;
            $course->save();
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
            $course = Course::find($id);
            $course->delete();
            return $this->successResponse([], __(MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

}
