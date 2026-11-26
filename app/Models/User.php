<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const MODULES = [
        'publikasi',
        'digital_library',
        'media_partners',
        'join_us',
        'identitas_konten',
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
        'privileges',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'privileges' => 'array',
    ];

    public static function fullPrivileges(): array
    {
        return array_fill_keys(self::MODULES, true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super-admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif' && ! $this->trashed();
    }

    public function canManage(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $privileges = $this->privileges;
        if (! is_array($privileges)) {
            $privileges = json_decode((string) $privileges, true) ?? [];
        }

        return (bool) ($privileges[$module] ?? false);
    }

    public function hasPrivilege(string $page): bool
    {
        if ($this->isSuperAdmin() || $page === 'dashboard') {
            return true;
        }

        return match ($page) {
            'dashboard'                        => true,
            'publications', 'publikasi'        => $this->canManage('publikasi'),
            'library', 'digital_library'       => $this->canManage('digital_library'),
            'media-partners', 'media_partners' => $this->canManage('media_partners'),
            'join-us', 'join_us'               => $this->canManage('join_us'),
            'settings', 'identitas_konten'     => $this->canManage('identitas_konten'),
            'users', 'admins'                  => false,
            default                            => false,
        };
    }
}
