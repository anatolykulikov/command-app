<?php

namespace App\Actions\User\DTO;

use App\Repository\User\UserMetaRepository;

class UserMetaDTO
{
    /**
     * Массив всех метаданных
     * @var array
     */
    protected array $metaFields;

    /* Общедоступные мета-данные */
    public string $name;
    public string $avatar;
    public string $position;

    public function __construct(int $userId)
    {
        $metas = (new UserMetaRepository())->getUserMeta($userId);
        foreach ($metas as $meta) {
            $this->metaFields[$meta->key] = $meta->value;
        }

        /**
         * Устанавливаем публичные меты
         */
        $this->setName($this->getMeta('name'));
        $this->setAvatar($this->getMeta('avatar'));
        $this->setPosition($this->getMeta('position'));
    }

    /**
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function getMeta(string $key, string $default = null): ?string
    {
        if(isset($this->metaFields[$key])) return $this->metaFields[$key];
        return $default;
    }


    /**
     * @param string|null $name
     */
    public function setName(?string $name = null): void
    {
        $this->name = $name ?? '';
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar = null): void
    {
        $this->avatar = $avatar ?? '';
    }

    /**
     * @param string|null $position
     */
    public function setPosition(?string $position = null): void
    {
        $this->position = $position ?? '';
    }
}
