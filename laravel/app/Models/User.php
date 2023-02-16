<?php

namespace App\Models;

use App\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements UserInterface
{
    protected $table = 'users';
    protected $fillable = ['id', 'login', 'role', 'active'];
    protected $guarded = ['password'];

    public static function getFromToken(string $key): ?static
    {
        $query = self::query()
            ->select([
                'users.id',
                'users.login',
                'users.active',
                'users.role',
            ])
            ->join('auth_keys', function ($join) {
                $join->on('auth_keys.user', '=', 'users.id');
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
}
