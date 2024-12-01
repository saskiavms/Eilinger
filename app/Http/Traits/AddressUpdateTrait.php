<?php

namespace App\Http\Traits;

trait AddressUpdateTrait
{
    public $street = '';

    public $number = '';

    public $plz = '';

    public $town = '';

    public $country_id = '';
    protected function getAddressData()
    {
        return session()->get('address_data', [
            'street' => '',
            'plz' => '',
            'town' => '',
            'country_id' => null,
            // ... other default values
        ]);
    }

    protected function setAddressData($data)
    {
        session()->put('address_data', $data);
    }
}
