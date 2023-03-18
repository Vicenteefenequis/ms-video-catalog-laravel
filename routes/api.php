<?php

use App\Http\Controllers\Api\{
    CastMemberController,
    GenreController
};
use Illuminate\Support\Facades\Route;

Route::apiResource('/categories', CastMemberController::class);

Route::apiResource(
    name: '/genres',
    controller: GenreController::class
);

Route::get('/', function () {
    return response()->json(['message' => 'success']);
});


