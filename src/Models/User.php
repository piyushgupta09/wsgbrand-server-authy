<?php

namespace Fpaipl\Authy\Models;

use Fpaipl\Panel\Models\Webpush;
use Fpaipl\Panel\Traits\Authx;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Fpaipl\Authy\Mail\SendOtpMail;
use Spatie\Activitylog\LogOptions;
use Fpaipl\Authy\Traits\HasProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use
        Authx,
        HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        SoftDeletes,
        HasProfile,
        InteractsWithMedia,
        LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otpcode',
        'utype',
        'type',
        'uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'password' => 'hashed',
    ];

    const MEDIA_COLLECTION_NAME = 'profile';

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) \Illuminate\Support\Str::uuid();
        });

        static::created(function ($model) {
            if ($model->utype == null) {
                $model->utype = $model->getUtype();
                $model->saveQuietly();
            }
            $model->assignRole('user');
            $model->profile()->create();
        });
    }

    public function sendOTPNotification($OTP)
    {
        if ($this->usernameIsEmailId()) {
            Mail::to($this->email)->send(new SendOtpMail($OTP));
        }
        if ($this->usernameIsMobileNo()) {
            Log::info("OTP {$OTP} sent to mobile no. {$this->email}");
        }
    }

    /**
     * Scope a query to only include users with exactly one role, specifically 'user'.
     *
     * This scope filters users to those who have been assigned only one role,
     * and that role must be 'user'. It's useful for identifying new users
     * who haven't been assigned additional roles. It assumes that the 'roles'
     * relationship exists and is a typical many-to-many relationship between
     * users and roles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNewUsers($query)
    {
        return $query->whereHas('roles', function ($query) {
            // Inside this closure, $query refers to the query for the Role model.
            // Filter to include only 'user' role.
            $query->where('name', 'user');
        }, '=', 1); // Ensure that the user is associated with exactly one role.
    }

    public function usernameIsEmailId()
    {
        $validator = Validator::make(['email' => $this->email], [
            'email' => 'required|email',
        ]);

        return !$validator->fails();
    }

    private function getUtype()
    {
        return $this->email ? 'email' : 'mobile';
    }

    public function usernameIsMobileNo()
    {
        return is_numeric($this->email) && strlen($this->email) == 10;
    }

    public function getAvatar()
    {
        return 'https://ui-avatars.com/api/?name=' . $this->name . '&color=7F9CF5&background=EBF4FF';
    }

    public function getProfileImage()
    {
        // check if a media is attached to the model
        if ($this->userHasMedia()) {
            // return the url for the media uploaded with the name profile
            return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_NAME, 'thumb');
        } else {
            // return the default image
            return $this->getAvatar();
        }
    }

    public function userHasMedia()
    {
        return $this->hasMedia(self::MEDIA_COLLECTION_NAME);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['id', 'name', 'email', 'created_at', 'updated_at', 'deleted_at'])
            ->useLogName('model_log');
    }

    public function getTableData($key)
    {
        switch ($key) {
            case 'role': return $this->roles->first()->name;
            default: return $this->{$key};
        }
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function isSuperAdmin()
    {
        return $this->email == 'admin@admin.com' || $this->email == 'pg.softcode@gmail.com';
    }

    public function pushNotifications()
    {
        return $this->morphMany(Webpush::class, 'subscribable');
    }


    /**
    * Determine if the model owns the given subscription.
    *
    * @param  \NotificationChannels\WebPush\PushSubscription  $subscription
    * @return bool
    */
    public function ownsPushNotification($subscription)
    {
            return (string) $subscription->subscribable_id === (string) $this->getKey() &&
                        $subscription->subscribable_type === $this->getMorphClass();
    }

    /**
    * Delete subscription by endpoint.
    *
    * @param  string  $endpoint
    * @return void
    */
    public function deletePushNotification($endpoint)
    {
        $this->pushNotifications()
                ->where('endpoint', $endpoint)
                ->delete();
    }

    /**
    * Get all of the subscriptions.
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function routeNotificationForWebPush()
    {
        return $this->pushNotifications;
    }
	

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_NAME)
            ->singleFile()
            ->useFallbackPath(public_path('storage/assets/images/placeholder.jpg'));

    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
    }

    public function getMyPrefix()
    {
         return str_replace('user-', '', $this->type);
    }

    public function getMyRoles()
    {
        $myRoles = [];
        $roleNames = $this->roles->pluck('name')->toArray();
        foreach ($roleNames as $role) {
              if (!str_contains($role, 'user')) {
                $myRoles[] = $role;
              }
        }
        return $myRoles;
    }

    // owner and other staff pending
    // current login with only manager id for all
    public function getMyMainRole()
    {
        $myRole = null;
        $roleNames = $this->roles->pluck('name')->toArray();
        $mainRoles = [
            'user-brand' => 'manager-brand',
            'user-vendor' => 'manager-vendor',
            'user-factory' => 'manager-factory',
            'admin' => 'admin',
        ];
        foreach ($mainRoles as $userType => $mainRole) {
            if ($this->type == $userType && in_array($mainRole, $roleNames)) {
                $myRole = $mainRole;
                break; 
            }
        }
        return $myRole;
    }
}
