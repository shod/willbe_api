<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

use App\Models\ClientUser;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** For UserInfo reference*/
    protected $user_key = '';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
    ];

    public function __construct()
    {
    }

    /**
     * Get User key
     */
    public function getUserKey()
    {
        $user_key = env('USER_KEY_SALT') . $this->id . $this->email;
        return md5($user_key);
    }

    /**
     * Check user key
     */
    public function isKeyValid($hash_to_check)
    {
        //if (Hash::check($this->user_key, $hash_to_check)) {                    
        if ($hash_to_check === md5($this->user_key)) {
            return true; // Valid
        } else {
            return false; // Invalid
        }
    }

    /** 
     * Get User Info 
     */
    public function user_info()
    {
        return UserInfo::where('user_key', $this->getUserKey())->first();
    }

    /**
     * Clients
     */
    public function clients()
    {
        return $this->hasManyThrough(User::class, ClientUser::class, 'user_id', 'id', 'id', 'client_id');
    }
}
