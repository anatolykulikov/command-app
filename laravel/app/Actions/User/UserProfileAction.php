<?php

namespace App\Actions\User;

use App\Helpers\UserHelper;
use App\Models\User;
use App\Actions\User\DTO\UserMetaDTO;
use App\Repository\Communities\CommunitiesRepository;
use App\Repository\EventsRepository\EventsRepository;
use App\Repository\Tasks\TasksRepository;
use App\Repository\Teams\TeamsRepository;
use Illuminate\Support\Collection;

class UserProfileAction
{
    protected ?User $user;
    protected UserHelper $userHelper;

    /* Публичные поля */
    public string $login;
    public string $role;
    public bool $active;
    public UserMetaDTO $meta;

    public Collection $tasks;
    public Collection $teams;
    public Collection $communities;
    public Collection $events;

    public function __construct(
        ?User $user = null,
        UserHelper $userHelper = null
    )
    {
        $this->user = $user;
        $this->userHelper = $userHelper;
    }

    /**
     * @param User $user
     * @param bool $isCurrent
     * @return static
     */
    public function create(User $user, bool $isCurrent = true): static
    {
        $this->setUser($user);
        $this->fetchMetas();

        if($isCurrent) {
            $this->fetchTasks();
            $this->fetchTeams();
            $this->fetchCommunities();
            $this->fetchEvents();
        }

        return $this;
    }

    private function setUser(User $user): void
    {
        $this->user = $user;
        $this->login = $user->getLogin();
        $this->role = $this->userHelper->getUserRoleTitle($user->getRole());
        $this->active = $user->getActive();
    }

    /**
     * Получаем мета-данные пользователи
     * (отображаемое имя, аватар, контакты и прочее)
     * @return void
     */
    private function fetchMetas(): void
    {
        $this->meta = new UserMetaDTO($this->user->getId());
    }

    /**
     * Текущие задачи пользователя по всем проектам
     * @return void
     */
    private function fetchTasks(): void
    {
        $this->tasks = (new TasksRepository())
            ->getListByUser($this->user->getId());
    }

    /**
     * Список команд пользователя
     * @return void
     */
    private function fetchTeams(): void
    {
        $this->teams = (new TeamsRepository())
            ->getUserTeams($this->user->getId());
    }

    /**
     * Список сообществ пользователя
     * @return void
     */
    private function fetchCommunities(): void
    {
        $this->communities = (new CommunitiesRepository())
            ->getUserCommunity($this->user->getId());
    }

    /**
     * Список актуальных событий для пользователя
     * @return void
     */
    private function fetchEvents(): void
    {
        $this->events = (new EventsRepository())
            ->getUserEvents($this->user->getId());
    }
}
