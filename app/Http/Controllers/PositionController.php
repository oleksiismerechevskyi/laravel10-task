<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Exception;
use Illuminate\Database\RecordsNotFoundException;
use Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PositionController extends Controller
{

     /**
     * Get Positions
     *
     * @OA\Get(
     *     path="/positions",
     *     tags={"positions"},
     *     summary="Get all positions",
     *     description="Get all positions",
     *     operationId="GetPositions",
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Positions not found"
     *     )
     * )
     */
    public function index() {
        $positions = Position::all();
        
        if( $positions->isEmpty() ) {
            throw new NotFoundHttpException("Positions are not found", null, 404);
        }

        return Response::json(
            [
                'status' => 'success',
                'positions' => $positions
            ]
        );
    }
}
