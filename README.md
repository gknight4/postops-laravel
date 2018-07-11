PostOps backend, implemented in Laravel

install php / mysql - tested with php7.2

git clone https://github.com/gknight4/postops-laravel

rename .env.example to .env, and edit it:

DB_CONNECTION=mysql\
DB_HOST=127.0.0.1\
DB_PORT=3306\
DB_DATABASE=postops\
DB_USERNAME=homestead\
DB_PASSWORD=bwXY2Xjr\

create the database (postops), the user (homestead / bwXY2Xjr), and give that user privileges on the database

composer install\
composer update\
php artisan migrate\
php artisan jwt:secret\
php artisan serve

using Postman:

register:\
Post localhost:8000/open/users\
Header:\
Content-Type: application/json\
{"useremail": "here@there.com", "password": "password"}

Note the addition to the "users" table

login:\
GET localhost:8000/open/authenticate/here@there.com/password\
Header:\
Accept: application/json\
this returns a header:\
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvb3BlblwvYXV0aGVudGljYXRlXC9oZXJlQ...

copy the "Bearer eyJ..." part, and supply it as authorization for the next step

add a string:\
POST localhost:8000/auth/stringstore\
header:\
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvb3BlblwvYXV0aGVudGljYXRlXC9oZXJlQ...\
[{"text": "http://myplace.com", "type": "url"}]

note the addition to the "stringstores" table

Changelog:

Version 0.2.1

Fixed / Verified that the instructions above actually work.

Version 0.1.1

Almost fully implemented, undoubtedly with issues:

Register / Login, JWT tokens working\
sets initial strings, saves and reloads strings from MySQL database.\
works with React front end on Git, with the change of local port from 6026 (go), to 8000

Does *not* implement the Proxy mode.

Probably will get rewrit from a new Laravel project, as a part of a simple Laravel / ReST / JWT tutorial.