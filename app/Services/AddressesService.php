<?php

namespace App\Services;


use App\Models\Address;
use Illuminate\Support\Facades\Cache;


class AddressesService
{

    public function getUserAddresses($user_id)
    {
        return Cache::remember('user_addresses_' . $user_id, now()->addMinutes(10), function () use ($user_id) {
            return Address::where('user_id', $user_id)->latest()->get();
        });
    }

    public function createAddress($userId, $data)
    {
        Cache::forget('user_addresses_' . $userId);
        $data['user_id'] = $userId;
        return Address::create($data);
    }

    public function updateAddress($id, $user_id, $data)
    {
        Cache::forget('user_addresses_' . $user_id);
        $address = Address::findOrFail($id);
        $address->update($data);
        return $address;
    }

    public function deleteAddress($id, $user_id)
    {
        Cache::forget('user_addresses_' . $user_id);
        return Address::find($id)->delete();
    }

    public function markAsFavourite($id, $user_id)
    {
        Address::where('user_id', $user_id)
            ->where('favourite', 1)
            ->update(['favourite' => 0]);
        $address = Address::find($id);
        $address->update(['favourite' => 1]);
//        $address = Address::find($id);
        $address->favourite = true;
    }
}
