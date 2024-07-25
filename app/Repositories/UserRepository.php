<?php
namespace App\Repositories;

use App\DTO\CreateUserDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository {
    protected $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getUsers() {
        return $this->user::all();
    }

    public function find(int $id) {
        return $this->user->find($id);
    }

    public function create(CreateUserDTO $createUserDto): int {
        $data = $createUserDto->toArray();
        $user = $this->user->create($data);
        return $user->id;
    }

    public function getPagination(int $page, int $count) {
        return $this->user->paginate($count, ['*'], 'page', $page);
    }
}