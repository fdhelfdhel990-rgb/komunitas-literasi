<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'status', 'privileges'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Check if user is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['super-admin', 'super_admin', 'Super Admin']);
    }

    /**
     * Check if user has privilege for a specific page.
     */
    public function hasPrivilege(string $page): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (is_null($this->privileges)) {
            return true;
        }

        $privileges = $this->privileges;
        if (! is_array($privileges)) {
            $privileges = json_decode($privileges, true) ?? [];
        }

        return in_array($page, $privileges);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'status',
        'privileges',
    ];

    protected $casts = [
        'privileges' => 'array',
    ];
}
