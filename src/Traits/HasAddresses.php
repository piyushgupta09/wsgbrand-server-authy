<?php

namespace Fpaipl\Authy\Traits;

use Fpaipl\Authy\Models\Address;


trait HasAddresses {

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function address()
    {
        return $this->addresses->first();
    }

    public function billingAddress()
    {
        return $this->addresses->where('id', $this->billing_address_id)->first();
    }

    public function shippingAddress()
    {
        return $this->addresses->where('id', $this->shipping_address_id)->first();
    }
}
