<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Wishlist;
use Lunar\Models\Customer;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;
use Illuminate\Database\Eloquent\Relations\HasOne;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'email_verified_at'
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

    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function staffMembers()
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    public function shopOwner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'owner_id', 'owner_id');
    }

    
    // @todo then owner creates staff
    public static function createStaff(array $data, $ownerId)
    {
        $staff = new self($data);
        $staff->owner_id = $ownerId;
        $staff->save();
        return $staff;

        // $staff = User::createStaff([
        //     'name' => 'Staff Name',
        //     'email' => 'staff@example.com',
        //     'password' => bcrypt('password'),
        // ], auth()->id());
    }

    protected static function booted()
    {
        // @todo add a check if user register as a shop owner
        static::created(function ($user) {
            if (is_null($user->owner_id)) {
                $user->owner_id = $user->id;
                $user->save();
            }
        });
        
    }


}
