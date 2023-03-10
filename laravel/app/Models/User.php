<?php

namespace App\Models;

use App\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements UserInterface
{
    protected $table = 'users';
    protected $fillable = ['id', 'login', 'role', 'active', 'deleted_at'];
    protected $guarded = ['password'];

    public static function getFromId(int $userId): ?static
    {
        $query = self::query()
            ->where('id', '=', $userId)
            ->get()
            ->first();

        if(!$query) return null;
        return new static($query->attributes);
    }

    public static function getFromToken(string $key): ?static
    {
        $query = self::query()
            ->select([
                'users.id',
                'users.login',
                'users.active',
                'users.role',
                'deleted_at',
            ])
            ->join('auth_keys', function ($join) {
                $join->on('auth_keys.user_id', '=', 'users.id');
            })
            ->where('auth_keys.token', '=', $key)
            ->where('auth_keys.expired', '>', date('c'))
            ->get()
            ->first();

        if(!$query) return null;
        return new static($query->attributes);
    }

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getLogin(): string
    {
        return $this->attributes['login'];
    }

    public function getActive(): bool
    {
        return boolval($this->attributes['active']);
    }

    public function getRole(): string
    {
        return $this->attributes['role'];
    }

    public function isDeleted(): bool
    {
        return boolval($this->attributes['deleted_at']);
    }
}
