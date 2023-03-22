<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



## Public Route

- **api/login**
accept two param [**email**] - [**password**] and return token

- **api/register**
acsept [**name**] - [**email**] - [**password**] - [**confirm_password**] 
and return token and user data

## Protected Route

- **api/posts**
GET all posts 

- **api/posts/{id}**
GET single Post

- **api/posts**
POST create Post  acsept  [**title**] - [**images**] (as array)

- **api/posts/{id}**
PUT update Post acsept  [**title**]

- **api/posts/{id}**
DELETE Post acsept

- **api/posts/{id}/comment**
GET comment for post

- **api/posts/{id}/comment**
POST comment acsept [**comment**] 

- **api/comments/{id}**
PUT comment acsept [**comment**] 

- **api/comments/{id}**
DELETE comment

- **api/posts/{id}/likes**
GET like or dislike post

- **api/posts/user**
GET user info

- **api/posts/user**
POST update user info acsept [**name**] - [**phone**] - [**address**] - [**photo**]  


- **api/logout**
GET to Logout
