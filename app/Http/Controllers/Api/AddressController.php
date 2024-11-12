<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Services\AddressesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    protected $addressService;

    public function __construct(AddressesService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = $this->addressService->getUserAddresses(Auth::user()->id);
        return returnSuccess('success', $addresses, 'success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressRequest $request)
    {
        $this->addressService->createAddress(Auth::user()->id, $request->validated());
        return returnSuccess('success', null, 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressRequest $request, string $id)
    {
        $this->addressService->updateAddress($id, Auth::user()->id, $request->validated());
        return returnSuccess('success', null, 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->addressService->deleteAddress($id, Auth::user()->id);
        return returnSuccess('success', null, 'success');

    }

    public function favouriteAddress($id)
    {
        (new AddressesService())->markAsFavourite($id, Auth::user()->id);
        return returnSuccess('success', null, 'success');
    }
}
