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
    
    /**
     * @api {get} /user/me Request User information
     * @apiName GetUser
     * @apiGroup User
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "id": "35123",
     *       "name": "Username",
     *       "email": "UserEmail@mail.com",
     *       "role": "user",
     *       "created_at": "2020-05-24T11:07:26.000000Z",
     *       "updated_at": "2020-05-24T11:07:26.000000Z"
     *     }
     */
    $router->get('users/me', 'UserController@showSelf');

    /**
     * @api {put} /user/me Update User information
     * @apiName UpdateUser
     * @apiGroup User
     * @apiParamExample {json} Request:
     *     {
     *       "name": "Username",
     *       "email": "UserEmail@mail.com",
     *       "current_password": "password",
     *       "password": "newpassword",
     *       "password_confirmation": "newpassword"
     *     }
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "id": "35123",
     *       "name": "Username",
     *       "email": "UserEmail@mail.com",
     *       "role": "user",
     *       "created_at": "2020-05-24T11:07:26.000000Z",
     *       "updated_at": "2020-05-24T11:07:26.000000Z"
     *     }
     */
    $router->put('users/me', 'UserController@updateSelf');
    
    /**
     * @api {get} /playlists Display a listing of playlists.
     * @apiName GetPlaylists
     * @apiGroup Playlist
     *
     *
     * @apiSuccess {Object} data includes list playlists.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *       "id": 2,
     *       "name": "When the procession came opposite to Alice, and.",
     *       "created_at": "2020-05-24T11:07:26.000000Z",
     *       "updated_at": "2020-05-24T11:07:26.000000Z",
     *       "songs_count": 16
     *   },
     *   {
     *       "id": 3,
     *       "name": "By this time the Queen to-day?' 'I should like.",
     *       "created_at": "2020-05-24T11:07:26.000000Z",
     *       "updated_at": "2020-05-24T11:07:26.000000Z",
     *       "songs_count": 34
     *   }  
     */
    $router->get('playlists', 'PlaylistController@index');
    
    /**
     * @api {post} /playlists Create new Playlist
     * @apiName CreatePlaylist
     * @apiGroup Playlist
     * @apiParam {String} name Playlistname
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "name": "Username",
     *       "updated_at": "2020-05-24T11:07:26.000000Z",
     *       "created_at": "2020-05-24T11:07:26.000000Z",
     *       "id": "12"
     *     }
     * @apiSuccess {String} message Playlist stored successful
     */
    $router->post('playlists', 'PlaylistController@store');

    /**
     * @api {get} /playlists/{id} Show Playlist
     * @apiName ShowPlaylist
     * @apiGroup Playlist
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "id": 2,
     *      "name": "When the procession came opposite to Alice, and.",
     *      "created_at": "2020-05-24T11:07:26.000000Z",
     *      "updated_at": "2020-05-24T11:07:26.000000Z",
     *      "songs": [
     *       {
     *        "id": 11,
     *        "title": "Hatter continued, 'in this way:-- \"Up above the.",
     *        "thumbnail": "https://lorempixel.com/640/480/?66246",
     *        "genre": "Rock",
     *        "rating": "0",
     *        "path": "/songs",
     *        "artist_id": 10,
     *        "user_id": 1,
     *        "created_at": "2020-05-24T11:07:26.000000Z",
     *        "updated_at": "2020-05-24T11:07:26.000000Z",
     *        "pivot": {
     *            "playlist_id": 2,
     *            "song_id": 11,
     *            "song_order": 1
     *        }
     *      }
     */
    $router->get('playlists/{id}', 'PlaylistController@show');

    /**
     * @api {put} /playlists/{id} Update Playlist
     * @apiName UpdatePlaylist
     * @apiGroup Playlist
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "id": 2,
     *          "name": "peter",
     *          "created_at": "2020-05-24T11:07:26.000000Z",
     *          "updated_at": "2020-05-24T23:39:38.000000Z"
     *      }
     */
    $router->put('playlists/{id}', 'PlaylistController@update');
    
    /**
     * @api {delete} /playlists/{id} Delete Playlist
     * @apiName DeletePlaylist
     * @apiGroup Playlist
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {"message":"Deleted playlist."}
     */
    $router->delete('playlists/{id}', 'PlaylistController@destroy');
    

    $router->put('playlists/{id}/songs', 'PlaylistController@updateSong');
    
 
    $router->delete('playlists/{playlistId}/songs/{songId}', 'PlaylistController@removeSong');


    $router->post('playlists/{playlistId}/songs/{songId}', 'PlaylistController@addSongToPlaylist');


    $router->post('songUpload', 'SongController@upload');


    $router->get('songs', 'SongController@showAllUserSongs');


    $router->delete('songs/{id}', 'SongController@deleteSong');

    // radio
    $router->get('radios', 'RadioController@index');
    $router->get('radios/my', 'RadioController@indexUserRadios');
    $router->get('radios/{id}/stream', 'RadioController@stream');
    $router->post('radios', 'RadioController@store');
    $router->put('radios/{id}/activate', 'RadioController@activate');
    $router->delete('radios/{id}', 'RadioController@destroy');
    $router->get('radios/favorites', 'RadioController@getFavorites');
    $router->post('radios/favorites', 'RadioController@toggleFavorite');
});

$router->group(['middleware' => 'jwt:admin'], function () use ($router) {
    

    $router->get('users', 'UserController@index');


    $router->get('users/{id}', 'UserController@show');

    $router->put('users/{id}', 'UserController@update');


    $router->delete('users/{id}', 'UserController@destroy');
});
