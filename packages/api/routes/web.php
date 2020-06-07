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

    /**
     * @api {post} /auth/login Login the user
     * @apiName login
     * @apiGroup Auth
     * @apiParamExample {json} Request:
     *     {
     *       "name": "Username",
     *       "email": "UserEmail@mail.com",
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
    $router->post('login', 'AuthController@authenticate');

    /**
     * @api {post} /auth/register Register a user
     * @apiName register
     * @apiGroup Auth
     * @apiParamExample {json} Request:
     *     {
     *       "email": "UserEmail@mail.com"
     *       "name": "Username"
     *       "password": "12345678aB!"
     *       "password_confirmation": "12345678aB!"
     *     }
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "id": "35123",
     *       "name": "Username",
     *       "email": "UserEmail@mail.com",
     *       "created_at": "2020-05-24T11:07:26.000000Z",
     *       "updated_at": "2020-05-24T11:07:26.000000Z"
     *     }
     * @apiSuccess {String} message User registration succeeded
     */
    $router->post('register', 'AuthController@register');

        /**
     * @api {delete} /auth/logout Logout the user
     * @apiName logout
     * @apiGroup Auth
     *
     * @apiSuccess {String} message User has logged out.
     */
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
     *            "id": 13,
     *            "playlist_id": 2,
     *            "song_id": 11,
     *            "song_order": 1
     *        }
     *      }
     */
    $router->get('playlists/{id}', 'PlaylistController@show');

    /**
     * @api {put} /playlists/{id} Update Playlistname
     * @apiName UpdatePlaylistname
     * @apiGroup Playlist
     *
     * @apiParamExample {json} Request:
     *     {
     *       "name": "Playlistname",
     *     }
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

    /**
     * @api {put} /playlists/{id}/songs Update Songorder in Playlist
     * @apiName UpdateSong
     * @apiGroup Playlist
     *
     * @apiParamExample {json} Request:
     *     {
     *       "currentPosition": "1",
     *       "newPosition": "2",
     *     }
     *
     * @apiSuccess {String} message Updated song in playlist.
     */
    $router->put('playlists/{id}/songs', 'PlaylistController@updateSong');


    /**
     * @api {delete} /playlists/{playlistId}/songs{songId} Delete Song from Playlist
     * @apiName RemoveSong
     * @apiGroup Playlist
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {"message":"Removed song from playlist."}
     */
    $router->delete('playlists/{playlistId}/songs/{songId}', 'PlaylistController@removeSong');

    /**
     * @api {post} /playlists/{playlistId}/songs{songId} Add song to playlist
     * @apiName addSongToPlaylist
     * @apiGroup Playlist
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {"Song was successfully added to playlist."}
     */
    $router->post('playlists/{playlistId}/songs/{songId}', 'PlaylistController@addSongToPlaylist');

    /**
     * @api {post} /songUpload Upload a Song
     * @apiName Upload a song
     * @apiGroup Song
     *
     * @apiParamExample {json} Request:
     *   {
     *      "title": "Songname",
     *      "path": "(binary)",
     *      "thumbnail": "(binary)",
     *      "genre": "Genrename",
     *      "rating": "4",
     *      "artist": "Artistname"
     *   }
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {"message":"Uploaded song."}
     */
    $router->post('songUpload', 'SongController@upload');

    /**
     * @api {get} /songs Show all songs associated with the user
     * @apiName showAllUserSongs
     * @apiGroup Song
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *   [
     *   {
     *       "id": 8,
     *       "title": "CHAPTER X. The Lobster Quadrille The Mock Turtle.",
     *       "thumbnail": "https:\/\/lorempixel.com\/640\/480\/?38855",
     *       "genre": "Rock",
     *       "rating": "0",
     *       "path": "\/songs",
     *       "artist_id": 7,
     *       "user_id": 1,
     *       "created_at": "2020-06-06T21:05:46.000000Z",
     *       "updated_at": "2020-06-06T21:05:46.000000Z",
     *       "artist": {
     *       "id": 7,
     *       "name": "Miss Alaina Gibson III",
     *       "created_at": "2020-06-06T21:05:46.000000Z",
     *       "updated_at": "2020-06-06T21:05:46.000000Z"
     *       }
     *   },
     *   {
     *       "id": 10,
     *       "title": "Gryphon, and all must have a trial: For really.",
     *       "thumbnail": "https:\/\/lorempixel.com\/640\/480\/?50155",
     *       "genre": "Rock",
     *       "rating": "0",
     *       "path": "\/songs",
     *       "artist_id": 4,
     *       "user_id": 1,
     *       "created_at": "2020-06-06T21:05:46.000000Z",
     *       "updated_at": "2020-06-06T21:05:46.000000Z",
     *       "artist": {
     *       "id": 4,
     *       "name": "Mrs. Maryse Rippin Sr.",
     *       "created_at": "2020-06-06T21:05:46.000000Z",
     *       "updated_at": "2020-06-06T21:05:46.000000Z"
     *       }
     *  }
     *  ]
     */
    $router->get('songs', 'SongController@showAllUserSongs');


    /**
     * @api {delete} songs{id} Delete Song
     * @apiName deleteSong
     * @apiGroup Song
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {"message":"Deleted Song"}
     */
    $router->delete('songs/{id}', 'SongController@deleteSong');

    /**
     * @api {get} /radios Get Radios from other users
     * @apiName GetAllRadios
     * @apiGroup Radio
     *
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [{
     *       "id":2,
     *       "name":"Test",
     *       "description":"Test",
     *       "icon":null,
     *       "active":0,
     *       "playlist_id":2,
     *       "user": {
     *         "id": 1,
     *         "name": "admin",
     *         "email": "admin@mail.de",
     *         "role": "admin",
     *         "created_at": "2020-06-06T21:05:46.000000Z",
     *         "updated_at": "2020-06-06T21:05:46.000000Z"
     *       }
     *       "created_at":"2020-06-07T10:29:00.000000Z",
     *       "updated_at":"2020-06-07T11:26:53.000000Z"
     *     }]
     */
    $router->get('radios', 'RadioController@index');

    /**
     * @api {get} /radios/my Get radios of auth user
     * @apiName GetUserRadios
     * @apiGroup Radio
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [{
     *       "id":2,
     *       "name":"Test",
     *       "description":"Test",
     *       "icon":null,
     *       "active":0,
     *       "playlist_id":2,
     *       "user": {
     *         "id": 1,
     *         "name": "admin",
     *         "email": "admin@mail.de",
     *         "role": "admin",
     *         "created_at": "2020-06-06T21:05:46.000000Z",
     *         "updated_at": "2020-06-06T21:05:46.000000Z"
     *       }
     *       "created_at":"2020-06-07T10:29:00.000000Z",
     *       "updated_at":"2020-06-07T11:26:53.000000Z"
     *     }]
     */
    $router->get('radios/my', 'RadioController@indexUserRadios');

    /**
     * @api {get} /radios/{id} Get the radio stream
     * @apiName RadioStream
     * @apiGroup Radio
     *
     * @apiSuccess {audio/mpeg}.
     */
    $router->get('radios/{id}/stream', 'RadioController@stream');

    /**
     * @api {post} /radios Create radio
     * @apiName CreateRadio
     * @apiGroup Radio
     *
     * @apiParamExample {json} Request:
     *     {
     *       "name": "radio name",
     *       "description": "radio description",
     *       "genre": "radio genre",
     *       "playlist_id": "playlist id",
     *     }
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "id":2,
     *       "name":"Test",
     *       "description":"Test",
     *       "icon":null,
     *       "active":0,
     *       "playlist_id":2,
     *       "user_id":1,
     *       "created_at":"2020-06-07T10:29:00.000000Z",
     *       "updated_at":"2020-06-07T11:26:53.000000Z"
     *     }
     */
    $router->post('radios', 'RadioController@store');
    /**
     * @api {put} /radios/{id}/activate Activate radio
     * @apiName ActivateRadio
     * @apiGroup Radio
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "id":2,
     *       "name":"Test",
     *       "description":"Test",
     *       "icon":null,
     *       "active":0,
     *       "playlist_id":2,
     *       "user_id":1,
     *       "created_at":"2020-06-07T10:29:00.000000Z",
     *       "updated_at":"2020-06-07T11:26:53.000000Z"
     *     }
     */
    $router->put('radios/{id}/activate', 'RadioController@activate');
    /**
     * @api {delete} /radios/{id} Delete the radio
     * @apiName DeleteRadio
     * @apiGroup Radio
     *
     * @apiSuccess {String} message Deleted radio.
     */
    $router->delete('radios/{id}', 'RadioController@destroy');
    /**
     * @api {get} /radios/favorties Get radio favorites
     * @apiName Favorites
     * @apiGroup Radio
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [{
     *       "id":2,
     *       "name":"Test",
     *       "description":"Test",
     *       "icon":null,
     *       "active":1,
     *       "playlist_id":2,
     *       "user_id":1,
     *       "created_at":"2020-06-07T10:29:00.000000Z",
     *       "updated_at":"2020-06-07T10:29:43.000000Z",
     *       "pivot": {
     *         "user_id":1,
     *         "radio_id":2,
     *         "created_at":"2020-06-07T11:19:01.000000Z",
     *         "updated_at":"2020-06-07T11:19:01.000000Z"
     *       }
     *     }]
     */
    $router->get('radios/favorites', 'RadioController@getFavorites');
    /**
     * @api {post} /radios/favorties Toggle radio favorite
     * @apiName ToggleFavorite
     * @apiGroup Radio
     *
     * @apiParamExample {json} Request:
     *     {
     *       "radio_id": 1,
     *     }
     *
     * @apiSuccess {String} message Deleted user.
     */
    $router->post('radios/favorites', 'RadioController@toggleFavorite');
});

$router->group(['middleware' => 'jwt:admin'], function () use ($router) {

    /**
     * @api {get} /users Show users
     * @apiName ShowUsers
     * @apiGroup User
     * @apiPermission admin
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [{
     *       "id": "35123",
     *       "name": "Username",
     *       "email": "UserEmail@mail.com",
     *       "role": "user",
     *       "created_at": "2020-05-24T11:07:26.000000Z",
     *       "updated_at": "2020-05-24T11:07:26.000000Z"
     *     }]
     */
    $router->get('users', 'UserController@index');

    /**
     * @api {get} /users/{id} Show single user
     * @apiName ShowUser
     * @apiGroup User
     * @apiPermission admin
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
    $router->get('users/{id}', 'UserController@show');

    /**
     * @api {put} /users/{id} Update user
     * @apiName UpdateUser
     * @apiGroup User
     * @apiPermission admin
     *
     * @apiParamExample {json} Request:
     *     {
     *       "name": "user name",
     *       "email": "user email",
     *       "role": "user role",
     *       "password": "user password",
     *        "password_confirmation": "password confirmation",
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
    $router->put('users/{id}', 'UserController@update');

    /**
     * @api {delete} /user/{id} Delete the user
     * @apiName DeleteUser
     * @apiGroup User
     * @apiPermission admin
     *
     * @apiSuccess {String} message Deleted user.
     */
    $router->delete('users/{id}', 'UserController@destroy');
});
