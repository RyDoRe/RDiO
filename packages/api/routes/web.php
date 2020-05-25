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

$router->group(['middleware' => 'jwt'], function () use ($router) {
    $router->get('users/me', 'UserController@showSelf');
    $router->put('users/me', 'UserController@updateSelf');
    $router->put('users/me', 'UserController@updateSelf');
    $router->get('playlists', 'PlaylistController@index');
    $router->post('playlists', 'PlaylistController@store');
    $router->get('playlists/{id}', 'PlaylistController@show');
    $router->put('playlists/{id}', 'PlaylistController@update');
    $router->delete('playlists/{id}', 'PlaylistController@destroy');
    $router->put('playlists/{id}/songs', 'PlaylistController@updateSong');
    $router->delete('playlists/{playlistId}/songs/{songId}', 'PlaylistController@removeSong');
    $router->post('playlists/{playlistId}/songs/{songId}', 'PlaylistController@addSongToPlaylist');
    $router->post('songUpload', 'SongController@upload');
    $router->get('songs', 'SongController@showAllUserSongs');
    $router->delete('songs/{id}', 'SongController@deleteSong');

    // radio
    $router->post('radios', 'RadioController@store');
    $router->get('radios/{id}', 'RadioController@start');
});

$router->group(['middleware' => 'jwt:admin'], function () use ($router) {
    $router->get('users', 'UserController@index');
    $router->get('users/{id}', 'UserController@show');
    $router->put('users/{id}', 'UserController@update');
    $router->delete('users/{id}', 'UserController@destroy');
});
