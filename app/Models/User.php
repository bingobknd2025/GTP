<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Zone;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasRoles,HasFactory, Notifiable;

    protected $fillable = [
            'company_name', 'name', 'email', 'password', 'mobile_no', 'address',
            'city', 'state', 'pin_code', 'zone', 'zone_id','role_id', 'gst_no', 'pan_no','brand_ids','user_id'
        ];


    public function zone_data()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }





    public function brands()
    {
        return Brand::whereIn('id', explode(',', $this->brand_ids))->get();
    }

    public function getBrandNamesAttribute()
    {
        if (!$this->brand_ids) {
            return null;
        }

        // Decode the JSON string into an array
        $ids = json_decode($this->brand_ids, true);

        // If it's not an array or empty, return null
        if (!is_array($ids) || empty($ids)) {
            return null;
        }

        return Brand::whereIn('id', $ids)->pluck('brand_name')->implode(', ');
    }


    public function getRoleNameAttribute()
    {
        return $this->roles->pluck('name')->implode(', ');
    }
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
