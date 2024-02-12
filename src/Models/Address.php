<?php

namespace Fpaipl\Authy\Models;

use Fpaipl\Panel\Traits\Authx;
use Spatie\Activitylog\LogOptions;
use Fpaipl\Panel\Traits\ManageModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use
        Authx,
        HasFactory,
        LogsActivity,
        SoftDeletes,
        ManageModel;

    protected $fillable = [
        'title',
        'addressable_id',
        'addressable_type',
        'name',
        'lname',
        'contacts',
        'gstin',
        'line1',
        'line2',
        'state',
        'pincode',
        'country',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            $model->print = $model->printable();
            $model->saveQuietly();
        });
    }
    
    public function addressable()
    {
        return $this->morphTo();
    }

    public function displayable()
    {
        return $this->gstin . ' | ' . $this->print . '.';
    }

    public function printable()
    {
        return $this->line1 . ' ' . $this->line2 . ', ' . $this->state . ', ' . $this->pincode . '.';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable);
    }
}
