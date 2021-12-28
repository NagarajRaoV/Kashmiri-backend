<?php


use Carbon\Carbon;
use Illuminate\Support\Facades\Route;


Route::any('pages/{slug}', '\App\Http\Controllers\API\PagesController@getSinglePage');
Route::any('login/google', '\App\Http\Controllers\API\PagesController@LoginByGoogle');
Route::any('register', '\App\Http\Controllers\API\PagesController@Register');


Route::any('config', '\App\Http\Controllers\API\PagesController@getConfig');
Route::any('deep/categories', '\App\Http\Controllers\API\PagesController@getAllDepCategories');
Route::any('search', '\App\Http\Controllers\API\PagesController@getSearch');

Route::any('git_pull', function (){
    file_put_contents(base_path('git_p/pull_98.txt'), request()->all());

    return response()->json(
        [
            'msg'   =>  'ok'
        ]
    , 200);
});



Route::any('verify/{token}', '\App\Http\Controllers\API\PagesController@VerfiyAccount')->name('customer.verify');


Route::group([
    'middleware' => ['auth:api']
], function () {

    Route::post('settings', '\App\Http\Controllers\API\PagesController@updateHomeSettings');
    Route::any('profile/config', '\App\Http\Controllers\API\PagesController@getUserConfig');


});