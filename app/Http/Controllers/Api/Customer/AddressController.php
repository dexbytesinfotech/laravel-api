<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Address;
use App\Http\Resources\Customer\AddressCollection;
use App\Http\Resources\Customer\AddressResource;
use App\Http\Requests\Customer\AddressStoreRequest;
use App\Http\Requests\Customer\AddressUpdateRequest;

class AddressController extends BaseController
{

    public function __construct()
    {
       $this->authorizeResource(Address::class);
    }

      /**
        * @OA\Get(
        * path="/api/customer/address",
        * operationId="address",
        * tags={"Customer Address"},
        * summary="Get list of Customer Address",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns list of Customer Address",
        *      @OA\Response(
        *          response=201,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function index()
    {
        try
        {
            return $this->sendResponse(AddressCollection::collection(Address::orderBy('is_primary', 'DESC')->where('user_id', auth()->user()->id)->get()), 'Successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

     /**
     * @OA\POST(
     * path="/api/customer/address",
     * operationId="customerAddressCreate",
     * tags={"Customer Address"},
     * summary="Store Customer Address",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns Customer Address data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"line_2_number_street", "address", "addrees_type", "latitude", "longitude"},
     *               @OA\Property(property="line_1_number_building", type="text" ,description="Building number"),
     *               @OA\Property(property="line_2_number_street", type="text" ,description="Street name / area as in map"),
     *               @OA\Property(property="line_3_area_locality", type="text" ,description="Area Locality"),
     *               @OA\Property(property="address", type="text", description="Address"),
     *               @OA\Property(property="latitude", type="decimal",description="Latitude"),
     *               @OA\Property(property="longitude", type="decimal",description="Longitude"),
     *               @OA\Property(property="apartment_number", type="text", description="Apartment number / office number"),
     *               @OA\Property(property="floor_number", type="text", description="Floor number"),
     *               @OA\Property(property="additional_information", type="text", description="Additional information"),
     *               @OA\Property(property="first_name", type="text" ,description="First Name"),
     *               @OA\Property(property="last_name", type="text" ,description="Last Name"),
     *               @OA\Property(property="phone", type="numeric" ,description="Phone "),
     *               @OA\Property(property="addrees_type", type="enum" ,description="Address Type (Home/Work/Shop/Other)"),
     *               @OA\Property(property="zip_postcode", type="text" ,description="Zip PostCode"),
     *               @OA\Property(property="city", type="text", description="City"),
     *               @OA\Property(property="state", type="text", description="State"),
     *               @OA\Property(property="country", type="text", description="Country"),
     *               @OA\Property(property="is_primary", type="integer", description="Is primary address or main address (0/1)")
     *
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Delivery address has been added successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Delivery address has been added successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(AddressStoreRequest $request, Address $address)
    {
        try
        {
            $validated = $request->validated();
            $validated['user_id'] = auth()->user()->id;

            $count = $address->where('user_id','=', auth()->user()->id)->count();
            if($count > 0) {
                if($validated['is_primary']){
                    $address->where('user_id', auth()->user()->id)->update(['is_primary'=> 0]);
                }
            }else{
                $validated['is_primary'] = 1;
            }

            $addressResource = $address->create($validated);

            return $this->sendResponse(new AddressResource($addressResource), trans('customer.Delivery address has been added successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
        * @OA\Get(
        * path="/api/customer/address/{id}",
        * operationId="customerAddressGet",
        * tags={"Customer Address"},
        * summary="Get Customer Address information",
        * security={
        *   {"passport": {}}
        * },
        * description="Returns Customer Address information",
        *  @OA\Parameter(
        *          name="id",
        *          description="address id",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="integer"
        *          )
        *      ),
        *      @OA\Response(
        *          response=201,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function show(Address $address)
    {
        try{
           
            return $this->sendResponse(new AddressResource($address), 'Successfully');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Put(
     * path="/api/customer/address/{id}",
     * operationId="customerAddressUpdate",
     * tags={"Customer Address"},
     * summary="Update existing Customer Address",
        * security={
        *   {"passport": {}}
        * },
     * description="Returns updated Customer Address data",
     *  @OA\Parameter(
    *          name="id",
    *          description="id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *    @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *               mediaType="application/x-www-form-urlencoded",
     *               @OA\Schema(type="object", required={"line_2_number_street", "address", "addrees_type", "latitude", "longitude"},
     *               @OA\Property(property="line_1_number_building", type="text" ,description="Building number"),
     *               @OA\Property(property="line_2_number_street", type="text" ,description="Street name / area as in map"),
     *               @OA\Property(property="line_3_area_locality", type="text" ,description="Area Locality"),
     *               @OA\Property(property="address", type="text", description="Address"),
     *               @OA\Property(property="latitude", type="decimal",description="Latitude"),
     *               @OA\Property(property="longitude", type="decimal",description="Longitude"),
     *               @OA\Property(property="apartment_number", type="text", description="Apartment number / office number"),
     *               @OA\Property(property="floor_number", type="text", description="Floor number"),
     *               @OA\Property(property="additional_information", type="text", description="Additional information"),
     *               @OA\Property(property="first_name", type="text" ,description="First Name"),
     *               @OA\Property(property="last_name", type="text" ,description="Last Name"),
     *               @OA\Property(property="phone", type="numeric" ,description="Phone "),
     *               @OA\Property(property="addrees_type", type="enum" ,description="Address Type (Home/Work/Shop/Other)"),
     *               @OA\Property(property="zip_postcode", type="text" ,description="Zip PostCode"),
     *               @OA\Property(property="city", type="text", description="City"),
     *               @OA\Property(property="state", type="text", description="State"),
     *               @OA\Property(property="country", type="text", description="Country"),
     *               @OA\Property(property="is_primary",  type="integer", description="Is primary address or main address (0/1)")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Delivery address has been updated successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Delivery address has been updated successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(AddressUpdateRequest $request, Address $address)
    {
        try
        {
            $validated = $request->validated();

            $validated['user_id'] = auth()->user()->id;
            $count = $address->where('user_id','=', auth()->user()->id)->count();
            if($count > 0) {
                if($validated['is_primary']){
                    $address->where('user_id', auth()->user()->id)->update(['is_primary'=> 0]);
                }
            }else{
                $validated['is_primary'] = 1;
            }

            $address->update($validated);
            return $this->sendResponse(new AddressResource($address), trans('customer.Delivery address has been updated successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/customer/address/{id}",
     *      operationId="deleteCustomerAddress",
     *      tags={"Customer Address"},
     *      summary="Delete existing Customer Address",
        * security={
        *   {"passport": {}}
        * },
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="address id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Delivery address deleted successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy(Address $address)
    {
        try
        {    
           
            $address->delete();
            return $this->sendResponse([], trans('customer.Delivery address deleted successfully'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
