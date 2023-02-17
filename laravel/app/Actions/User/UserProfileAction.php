<?php

namespace App\Actions\User;

use App\Models\User;
use App\Actions\User\DTO\UserMetaDTO;
use App\Repository\Communities\CommunitiesRepository;
use App\Repository\EventsRepository\EventsRepository;
use App\Repository\Tasks\TasksRepository;
use App\Repository\Teams\TeamsRepository;
use Illuminate\Support\Collection;

class UserProfileAction
{
    protected User $user;

    /* Публичные поля */
    public string $login;
    public string $role;
    public bool $active;
    public UserMetaDTO $meta;

    public Collection $tasks;
    public Collection $teams;
    public Collection $communities;
    public Collection $events;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->login = $user->getLogin();
        $this->role = $user->getRole();
        $this->active = $user->getActive();
        $this->fetchMetas();
        $this->fetchTasks();
        $this->fetchTeams();
        $this->fetchCommunities();
        $this->fetchEvents();
    }

    /**
     * @param User $user
     * @return static
     */
    public static function create(User $user): static
    {
        return new static($user);
    }

    private function fetchMetas(): void
    {
        $this->meta = new UserMetaDTO($this->user->getId());
    }

    private function fetchTasks(): void
    {
        $this->tasks = (new TasksRepository())
            ->getListByUser($this->user->getId());
    }

    private function fetchTeams(): void
    {
        $this->teams = (new TeamsRepository())
            ->getUserTeams($this->user->getId());
    }

    private function fetchCommunities(): void
    {
        $this->communities = (new CommunitiesRepository())
            ->getUserCommunity($this->user->getId());
    }

    private function fetchEvents(): void
    {
        $this->events = (new EventsRepository())
            ->getUserEvents($this->user->getId());
    }
}
