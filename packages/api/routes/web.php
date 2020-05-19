<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', 'AuthController@authenticate');
    $router->post('register', 'AuthController@register');
    $router->delete('logout', 'AuthController@logout');
});

$router->group(['middleware' => 'jwt:admin'], function () use ($router) {
    $router->get('users', function () {
        $users = \App\User::all();
        return response()->json($users);
    });
});

$router->group(['middleware' => 'jwt'], function () use ($router) {
    $router->get('playlists', 'PlaylistController@index');
    $router->post('playlists', 'PlaylistController@store');
    $router->get('playlists/{id}', 'PlaylistController@show');
    $router->put('playlists/{id}', 'PlaylistController@update');
    $router->delete('playlists/{id}', 'PlaylistController@destroy');
    $router->put('playlists/{id}/songs', 'PlaylistController@updateSong');
    $router->delete('playlists/{playlistId}/songs/{songId}', 'PlaylistController@removeSong');
});
