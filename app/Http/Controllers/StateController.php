<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateRequest;
use App\Models\Country;
use App\Models\State;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StateController extends Controller
{
    use JsonResponseTrait;

    public function all(Request $request){
        if ($request->ajax()) {
            $state = State::with('country')->orderBy('id', 'desc');

            return datatables($state)
                ->addIndexColumn()
                ->addColumn('country_name', function ($state) {
                    return $state->country->name;
                })
                ->addColumn('status', function ($state) {
                    return getStatusHtml($state->status);
                })
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <button onclick="editCommonModal(\'' . route('admin.state.edit', $data->id) . '\'' . ', \'#editModal\')" class="border-0 bg-transparent" data-bs-toggle="modal" title="Edit">
                                    <img src="' . asset('dashboard/assets/img/icon/edit.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteCommonMethod(\'' . route('admin.state.delete', $data->id) . '\', \'stateDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['country_name', 'status', 'action'])
                ->make(true);
        }

        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['activeMenu'] = 'active';
        $data['activeState'] = 'active';
        $data['pageTitle'] = __('All State');

        return view('admin.state.index', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __("State Edit");
        $data['countryList'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['state'] = State::find($id);
        return view('admin.state.edit', $data);
    }

    public function store(StateRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', 0);
            if ($id != 0) {
                $id = $id;
                $state = State::find($id);
                $msg = __(MSG_UPDATED_SUCCESSFULLY);

            } else {
                $state = new State();
                $msg = __(MSG_CREATED_SUCCESSFULLY);
            }

            $state->name = $request->name;
            $state->country_id = $request->country_id;
            $state->status = isset($request->status) ? $request->status : STATUS_ACTIVE;
            $state->save();
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
            $state = State::find($id);
            $state->delete();
            return $this->successResponse([], __(MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

}

