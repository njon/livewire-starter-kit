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
        'email_verified_at',
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

        // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_STAFF_VIEWER = 'staff_viewer';
    const ROLE_CUSTOMER = 'customer';

    // All available roles
    public static function getAvailableRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_STAFF,
            self::ROLE_STAFF_VIEWER,
            self::ROLE_CUSTOMER,
        ];
    }

    // Role hierarchy (who can manage whom)
    public static function getRoleHierarchy(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => [
                self::ROLE_SUPER_ADMIN,
                self::ROLE_ADMIN,
                self::ROLE_STAFF,
                self::ROLE_STAFF_VIEWER,
                self::ROLE_CUSTOMER,
            ],
            self::ROLE_ADMIN => [
                self::ROLE_STAFF,
                self::ROLE_STAFF_VIEWER,
                self::ROLE_CUSTOMER,
            ],
            self::ROLE_STAFF => [
                self::ROLE_CUSTOMER,
            ],
            self::ROLE_STAFF_VIEWER => [],
            self::ROLE_CUSTOMER => [],
        ];
    }

    // Role checking methods
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isStaffViewer(): bool
    {
        return $this->role === self::ROLE_STAFF_VIEWER;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    // Check if user can manage another role
    public function canManageRole(string $targetRole): bool
    {
        $hierarchy = self::getRoleHierarchy();
        
        if (!isset($hierarchy[$this->role])) {
            return false;
        }

        return in_array($targetRole, $hierarchy[$this->role]);
    }

    public function canManageUser(User $targetUser): bool
    {
        return $this->canManageRole($targetUser->role);
    }

    // Check permission levels
    public function hasFullAccess(): bool
    {
        return $this->isSuperAdmin();
    }

    public function hasAdminAccess(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function hasStaffAccess(): bool
    {
        return $this->hasAdminAccess() || $this->isStaff();
    }

    public function hasViewAccess(): bool
    {
        return $this->hasStaffAccess() || $this->isStaffViewer();
    }

    public function getSuperAdminAttribute(): bool
    {
        return $this->isSuperAdmin();
    }


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

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'lunar_customer_user')
                    ->withTimestamps();
    }

    public function latestCustomer()
    {
        return $this->customers()->latest()->first();
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
