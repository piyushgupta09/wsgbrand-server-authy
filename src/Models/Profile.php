<?php

namespace Fpaipl\Authy\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Panel\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use Authx, BelongsToUser;

    protected $fillable = [
        'user_id',
        'contacts',
        'tags',
    ];
}
