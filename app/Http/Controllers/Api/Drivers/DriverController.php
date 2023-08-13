<?php

namespace App\Http\Controllers\Api\Drivers;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Drivers\Driver;
use App\Models\Users\User;
use App\Http\Resources\Drivers\DriverCollection;
use App\Http\Resources\Drivers\DriverResource;
use App\Http\Requests\Drivers\DriverStoreRequest;
use App\Http\Requests\Drivers\DriverUpdateRequest;

class DriverController extends BaseController
{
    public function __construct()
    {
        //
    }

    public function index()
    {
        return $this->sendResponse(DriverCollection::collection(Driver::all()), 'Successfully.');
    }

    public function store(DriverStoreRequest $request, Driver $driver)
    {
        try
        {

            $driverResource = $driver->create($request->all());

            return $this->sendResponse(new DriverResource($driverResource), Response::HTTP_CREATED);
        }
        catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }
}
