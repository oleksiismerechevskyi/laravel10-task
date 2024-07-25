<?php

namespace App\Http\Controllers;

use App\DTO\CreateUserDTO;
use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use App\Utilities\ImageUtility;
use Illuminate\Http\Request;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function __construct(protected readonly UserService $userService, protected readonly ImageUtility $imageUtility) {}

    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="Get all users",
     *      tags={"users"},
     *      summary="Retrieve all users with pagination",
     *      description="Retrieve all users with pagination",
     *      @OA\Parameter(
     *          name="page",
     *          description="Pagination page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="count",
     *          description="Users per page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessible Entity"
     *      )
     * )
     */
    public function index(Request $request)
    {

        $page = $request->input('page', 1);
        $count = $request->input('count', 5);

        $users = $this->userService->getUsersPagination($page, $count);

        if( empty( $users ) ) {
            throw new NotFoundHttpException("Page not found", null, 404);
        }

        return Response::json(
            [
                'success' => 'true',
                'page' => $users['page'],
                'total_pages' => $users['total_pages'],
                'total_users' => $users['total_users'],
                'count' => $users['count'],
                'links' => [
                    'next_url' => $users['links']['next_url'],
                    'prev_url' => $users['links']['prev_url']
                ],
                'users' => $users['users'] 
            ]
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/users",
     *     tags={"users"},
     *     summary="Create a user",
     *     description="This can only be done by the logged in user.",
     *     operationId="createUser",
     *     @OA\Response(
     *         response="200",
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *     )
     * )
     */
    public function create(CreateUserRequest $request)
    {
       $validated = $request->validated();
       $filename = $this->imageUtility->cropImage($validated['photo']->get());
        
        if( empty($filename) ) {
            throw new \Exception(
                "Cannot crop the image.",
                422
            );
        }

        $fileUrl = asset('storage/' . $filename);

        $createUserDto = new CreateUserDTO(
            $validated['name'],
            $validated['email'],
            $validated['phone'],
            $validated['position_id'],
            $fileUrl
        );

        $userId = $this->userService->create($createUserDto);
        $this->userService->expireAccessToken($request);

        if(!$userId) {
            throw new \Exception("Error when trying to create a user", 422);
        }

        return Response::json(
            [
                "status" => "true",
                "user_id" => $userId,
                "message" => "New user successfully registered",
            ],
            201
        );
    }

    /**
     * Get user by Id
     *
     * @OA\Get(
     *     path="/users/{$id}",
     *     tags={"users"},
     *     summary="Get a user by id",
     *     description="Get a user by id",
     *     operationId="getUserById",
     *     @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function getUserById(string $id) {
        
        $user = $this->userService->find($id);
        if(!$user) {
            throw new NotFoundHttpException(
                "User is not found",
                null,
                404
            );
        }

        return Response::json(
            [
                "status" => "true",
                "user" => $user,
            ],
            200
        );
    }
}
