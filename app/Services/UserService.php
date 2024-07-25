<?php
namespace App\Services;
use App\DTO\CreateUserDTO;
use App\Http\Middleware\RegisterToken;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
class UserService {

    public function __construct( protected readonly UserRepository $userRepository) {}

    public function create(CreateUserDTO $createUserDTO) {
        return $this->userRepository->create($createUserDTO);
    }

    public function find(string $id) {
        $id = intval($id);
        return $this->userRepository->find($id);
    }

    public function getUsersPagination(int $page, int $count) {
        $usersPaginationData = $this->userRepository->getPagination($page, $count);
        if( $usersPaginationData->isEmpty() ) {
            return [];
        }

        return [
            'page' => $usersPaginationData->currentPage(),
            'total_pages' => $usersPaginationData->lastPage(),
            'total_users' => $usersPaginationData->total(),
            'count' => $usersPaginationData->perPage(),
            'links' => [
                'next_url' => $usersPaginationData->nextPageUrl() . '&count=' . $usersPaginationData->perPage(),
                'prev_url' => $usersPaginationData->previousPageUrl() !== null ? $usersPaginationData->previousPageUrl() . '&count=' . $usersPaginationData->perPage() : null,
            ],
            'users' => $usersPaginationData->items() 
        ];
    }

    public function expireAccessToken(Request $request) {
        $token = RegisterToken::getTokenFromRequest($request);
        $tokenModel = Sanctum::$personalAccessTokenModel;

        $accessToken = $tokenModel::findToken($token);
        $accessToken->forceFill(['last_used_at' => now()])->save();

    }
}