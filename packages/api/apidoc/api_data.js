define({ "api": [
  {
    "type": "post",
    "url": "/playlists",
    "title": "Create new Playlist",
    "name": "CreatePlaylist",
    "group": "Playlist",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Playlistname</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"name\": \"Username\",\n  \"updated_at\": \"2020-05-24T11:07:26.000000Z\",\n  \"created_at\": \"2020-05-24T11:07:26.000000Z\",\n  \"id\": \"12\"\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Playlist stored successful</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "put",
    "url": "/playlists/{id}",
    "title": "Delete Playlist",
    "name": "DeletePlaylist",
    "group": "Playlist",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"message\":\"Deleted playlist.\"}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "get",
    "url": "/playlists",
    "title": "Display a listing of playlists.",
    "name": "GetPlaylists",
    "group": "Playlist",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>includes list playlists.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "  HTTP/1.1 200 OK\n{\n    \"id\": 2,\n    \"name\": \"When the procession came opposite to Alice, and.\",\n    \"created_at\": \"2020-05-24T11:07:26.000000Z\",\n    \"updated_at\": \"2020-05-24T11:07:26.000000Z\",\n    \"songs_count\": 16\n},\n{\n    \"id\": 3,\n    \"name\": \"By this time the Queen to-day?' 'I should like.\",\n    \"created_at\": \"2020-05-24T11:07:26.000000Z\",\n    \"updated_at\": \"2020-05-24T11:07:26.000000Z\",\n    \"songs_count\": 34\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "get",
    "url": "/playlists/{id}",
    "title": "Show Playlist",
    "name": "ShowPlaylist",
    "group": "Playlist",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n \"id\": 2,\n \"name\": \"When the procession came opposite to Alice, and.\",\n \"created_at\": \"2020-05-24T11:07:26.000000Z\",\n \"updated_at\": \"2020-05-24T11:07:26.000000Z\",\n \"songs\": [\n  {\n   \"id\": 11,\n   \"title\": \"Hatter continued, 'in this way:-- \\\"Up above the.\",\n   \"thumbnail\": \"https://lorempixel.com/640/480/?66246\",\n   \"genre\": \"Rock\",\n   \"rating\": \"0\",\n   \"path\": \"/songs\",\n   \"artist_id\": 10,\n   \"user_id\": 1,\n   \"created_at\": \"2020-05-24T11:07:26.000000Z\",\n   \"updated_at\": \"2020-05-24T11:07:26.000000Z\",\n   \"pivot\": {\n       \"playlist_id\": 2,\n       \"song_id\": 11,\n       \"song_order\": 1\n   }\n }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "put",
    "url": "/playlists/{id}",
    "title": "Update Playlist",
    "name": "UpdatePlaylist",
    "group": "Playlist",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"id\": 2,\n     \"name\": \"peter\",\n     \"created_at\": \"2020-05-24T11:07:26.000000Z\",\n     \"updated_at\": \"2020-05-24T23:39:38.000000Z\"\n }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "Playlist"
  },
  {
    "type": "get",
    "url": "/user/me",
    "title": "Request User information",
    "name": "GetUser",
    "group": "User",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"id\": \"35123\",\n  \"name\": \"Username\",\n  \"email\": \"UserEmail@mail.com\",\n  \"role\": \"user\",\n  \"created_at\": \"2020-05-24T11:07:26.000000Z\",\n  \"updated_at\": \"2020-05-24T11:07:26.000000Z\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "User"
  },
  {
    "type": "put",
    "url": "/user/me",
    "title": "Update User information",
    "name": "UpdateUser",
    "group": "User",
    "parameter": {
      "examples": [
        {
          "title": "Request:",
          "content": "{\n  \"name\": \"Username\",\n  \"email\": \"UserEmail@mail.com\",\n  \"current_password\": \"password\",\n  \"password\": \"newpassword\",\n  \"password_confirmation\": \"newpassword\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"id\": \"35123\",\n  \"name\": \"Username\",\n  \"email\": \"UserEmail@mail.com\",\n  \"role\": \"user\",\n  \"created_at\": \"2020-05-24T11:07:26.000000Z\",\n  \"updated_at\": \"2020-05-24T11:07:26.000000Z\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "routes/web.php",
    "groupTitle": "User"
  }
] });