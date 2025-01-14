<?php

namespace App\Traits;


trait JsonResponseTrait
{
    protected $success = 200;
    protected $error = 500;

    public $response = ['status' => false, 'data' => [], 'message' => ''];

    public function successResponse($data = [], $message = NULL)
    {
        $this->response['status'] = true;
        $this->response['data'] = $data;
        $this->response['message'] = !is_null($message) ? $message : getMessage(MSG_DATA_FETCH_SUCCESSFULLY);
        return response()->json($this->response, $this->success);
    }

    public function errorResponse($data = [], $message = NULL)
    {
        $this->response['status'] = false;
        $this->response['data'] = $data;
        $this->response['message'] = !is_null($message) ? $message : getMessage(MSG_SOMETHING_WENT_WRONG);
        return response()->json($this->response, $this->error);
    }
}
