<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Models\Country;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    use JsonResponseTrait;

    public function all(Request $request){
        if ($request->ajax()) {
            $country = Country::query();
            return datatables($country)
                ->addIndexColumn()
                // ->addColumn('status', function ($country) {
                //     return getStatusHtml($country->status);
                // })
                ->addColumn('action', function ($data){
                    return '<div class="d-flex align-items-center g-10 justify-content-center">
                                <button onclick="editCommonModal(\'' . route('country.edit', $data->id) . '\'' . ', \'#editModal\')" class="border-0 bg-transparent" data-bs-toggle="modal" title="Edit">
                                    <img src="' . asset('dashboard/assets/img/icon/edit.svg') . '" alt="edit" />
                                </button>
                                <button onclick="deleteCommonMethod(\'' . route('country.delete', $data->id) . '\', \'countryDataTable\')" class="border-0 bg-transparent" title="Delete">
                                    <img src="' . asset('dashboard/assets/img/icon/delete.svg') . '" alt="delete">
                                </button>
                        </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data['activeMenu'] = 'active';
        $data['activeCountry'] = 'active';
        $data['pageTitle'] = __('All Country');

        return view('admin.country.index', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __("Country Edit");
        $data['country'] = Country::find($id);
        return view('admin.country.edit', $data);
    }

    public function store(CountryRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', 0);
            if ($id != 0) {
                $id = $id;
                $country = Country::find($id);
                $msg = __(MSG_UPDATED_SUCCESSFULLY);

            } else {
                $country = new Country();
                $msg = __(MSG_CREATED_SUCCESSFULLY);
            }

            $country->name = $request->name;
            $country->status = isset($request->status) ? $request->status : STATUS_ACTIVE;
            $country->save();
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
            $student = Country::find($id);
            $student->delete();
            return $this->successResponse([], __(MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->errorResponse([], __(MSG_SOMETHING_WENT_WRONG));
        }
    }

}
