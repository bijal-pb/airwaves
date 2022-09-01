<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController as BaseController;

use Illuminate\Http\Request;
use App\Models\Genres;
use App\Http\Resources\GenresResource;
class GenresController extends BaseController
{
     /**
     * @OA\Get(
     *     path="/api/get-genres",
     *     tags={"Get Genres"},
     *     summary="get genres",
     *     security={{"bearer_token":{}}},
     *     operationId="get-genres",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     * )
    **/
    public function get_genres(Request $request)
    {
                $genres = Genres::get();
                $genres =  GenresResource::collection($genres);
                return $this->sendResponse($genres, 'Genres data.');
    }
}
