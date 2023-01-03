<?php

namespace App\Models;

use App\Enums\UserRole as UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissionsTrait, SoftDeletes, SanctumHasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pin_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function users_roles()
    {
        return $this->hasOne(UserRole::class, 'user_id');
    }
    public function role()
    {
        return $this->users_roles()->role();
    }

    public function usercompany()
    {
        return $this->hasMany(UserCompany::class, 'user_id');
    }

    public function outlet_billers()
    {
        return $this->hasMany(OutletBiller::class, 'user_id');
    }

    public function getCurrentCompanyAttribute()
    {
        return $this->usercompany()->where('is_selected', 1)->first();
    }

    public function isBiller(): bool
    {
        return $this->users_roles()->whereHas('role', function ($q) {
            return $q->where('slug', Str::slug(UserRoleEnum::BILLER));
        })->exists();
    }
    public function hasRole($value)
    {
        return  $this->users_roles()->whereHas('role', function ($q) use ($value) {
            return $q->where('slug', Str::slug($value));
        })->exists();
    }
    public function IsSuperAdmin(): bool
    {
        return $this->users_roles()->whereHas('role', function ($q) {
            return $q->where('slug', Str::slug(UserRoleEnum::SUPER_ADMIN));
        })->exists();
    }

    public function hasOutlet($outletId): bool
    {
        return $this->outlet_billers()->where('outlet_id', $outletId)->exists();
    }

    public function canAccessOutlet(int $outletId)
    {
        if ($this->IsSuperAdmin())
            return true;
        return $this->hasOutlet($outletId);
    }

    public function getOutlets()
    {
        return Outlet::when(!$this->IsSuperAdmin(), function ($q) {
            $q->whereHas('outlet_billers', function ($q) {
                $q->where('user_id', $this->id);
            });
        })->get();
    }

    public function outletAlertCount(int $outletId): int
    {
        return  OutletProduct::where('outlet_id', $outletId ?? null)
            ->whereRaw('primary_stock <= primary_stock_alert')
            ->orWhereRaw('secondary_stock <= secondary_stock_alert')
            ->count();
    }

    public function getSessionOutlet()
    {
        $outletId = request()->session()->get('outlet');

        //if session has no outlet id then get user outlet
        if (!$outletId) {

            //if user is super admin then get one of the outlet
            //otherwise get own outlet
            if ($this->IsSuperAdmin()) {
                return Outlet::first();
            } else {
                $billerOulet = $this->outlet_billers()->first();
                $outletId = $billerOulet['outlet_id'] ?? null;
            }
        }

        return Outlet::find($outletId);
    }

    public function isImporter(): bool
    {
        $userCompany = UserCompany::where('user_id', $this->id)->first();

        if (!$userCompany)
            return false;

        $company = $userCompany->company;

        return ($company && $company->is_importer) ? true : false;
    }
}
