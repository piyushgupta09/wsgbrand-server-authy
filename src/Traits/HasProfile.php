<?php

namespace Fpaipl\Authy\Traits;

use Fpaipl\Authy\Models\Profile;

trait HasProfile {
    
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
}
