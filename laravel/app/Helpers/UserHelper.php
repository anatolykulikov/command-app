<?php

namespace App\Helpers;

use App\Actions\Auth\DTO\CreateNewToken;
use App\Actions\Auth\DTO\CreateToken;
use Illuminate\Http\Request;

class UserHelper
{
    const CookieName = 'applud';

    /**
     * Все пользовательские роли системы
     * @return array[]
     */
    protected function userRoles(): array
    {
        return [
            'admin' => [
                'title' => 'Администратор',
                'level' => 100
            ],
            'owner' => [
                'title' => 'Владелец',
                'level' => 90
            ],
            'supervisor' => [
                'title' => 'Управленец',
                'level' => 50
            ],
            'user' => [
                'title' => 'Пользователь',
                'level' => 10
            ],
            'guest' => [
                'title' => 'Гость',
                'level' => 0
            ]
        ];
    }

    /**
     * @param string $role
     * @return int|null
     */
    public function getUserRolePoint(string $role): ?int
    {
        $roles = (new static())->userRoles();
        if (!isset($roles[$role]) || !isset($roles[$role]['level'])) return null;
        return $roles[$role]['level'];
    }

    /**
     * @param string $role
     * @return string
     */
    public function getUserRoleTitle(string $role): string
    {
        $roles = (new static())->userRoles();
        if (!isset($roles[$role]) || !isset($roles[$role]['title'])) return $role;
        return $roles[$role]['title'];
    }

    /**
     * Получает список пользовательских ролей (учитывая уровень роли текущего пользователя)
     * @param string $role
     * @return array
     */
    public function getUserRoleList(string $role): array
    {
        $instance = new static();
        $currentRoleLevel = $role ? $instance->getUserRolePoint($role) : null;

        $list = [];

        if($currentRoleLevel) {
            foreach ($instance->userRoles() as $title => $userRole) {
                if($currentRoleLevel > $userRole['level']
                    || $currentRoleLevel === $instance->getUserRolePoint('admin')) {
                    $list[$title] = $userRole['title'];
                }
                if(!$currentRoleLevel) $list[$title] = $userRole['title'];
            }
        }

        return $list;
    }

    // TODO: Разрешенные мета-записи
    public function allowedMetas(): array
    {
        return [];
    }

    /**
     * @param CreateNewToken $token
     * @return void
     */
    public function setAuthCookie(CreateNewToken $token): void
    {
        $value = base64_encode(implode(':', [
            'key' => $token->getKey(),
            'expires' => $token->getExpired(),
        ]));

        setcookie(
            self::CookieName,
            $value,
            [
                'path' => '/',
                'expires' => $token->getExpired(),
                'httponly' => true,
                'samesite' => 'strict'
            ]
        );
    }

    /**
     * @param Request $request
     * @return CreateToken|null
     */
    public function readAuthCookie(Request $request): ?CreateToken
    {
        if(!$request->hasCookie(UserHelper::CookieName)) return null;
        $cookie = explode(':', base64_decode($request->cookie(UserHelper::CookieName)));
        if(!$cookie) return null;
        return new CreateToken($cookie[0], $cookie[1], $cookie[2] ?? null);
    }

    /**
     * @return void
     */
    public function deleteAuthCookie(): void
    {
        setcookie(
            self::CookieName,
            '',
            [
                'path' => '/',
                'expires' => time()-3600,
                'httponly' => true,
                'samesite' => 'strict'
            ]
        );

    }

    /**
     * @param string $pass
     * @return string
     */
    public function hashPassword(string $pass): string
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    /**
     * @param int $length
     * @return string
     */
    public function generateRandomPassword(int $length = 64): string
    {
        try {
            return self::hashPassword(bin2hex(random_bytes($length)));
        } catch (\Exception $e) {
            return self::hashPassword(bin2hex(mt_rand(100000, 9999999)));
        }
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function comparePasswords(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * @param string $current
     * @param string $assignedRole
     * @return bool
     */
    public function assignRoleAbility(string $current, string $assignedRole): bool
    {
        $instance = new static();
        $currentRoleLevel = $instance->getUserRolePoint($current);
        $assignedRoleLevel = $instance->getUserRolePoint($assignedRole);

        if ($currentRoleLevel === 0) return false;
        if (!$currentRoleLevel
            || !$assignedRoleLevel
            || $currentRoleLevel < $assignedRoleLevel) return false;
        return true;
    }
}
