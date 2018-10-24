# Levelup Forum

An open source forum project to showcase my ongoing efforts to master fullstack development. 

Note that although laradock was used in development, custom dockerfiles were used in the final production. 



## Features
+ Thread subscriptions and user notification system.
+ Basic role system with admin, moderator and user accounts.
+ All threads are created with unique hashID's.
+ Editable thread comments with favorites and @mentions.
+ A thread owner can designate a threads best reply.
+ Customizeable user profiles.
+ User avatar and photo uploads.
+ Integerated Quilljs editor for markdown support. 
+ Configurable spam detection system with keyword detection, repeated key detection and comment throttling.
+ Recaptcha verification.
+ User registration system with email confirmation.
+ Thread filters for active, popular, new, etc.
+ Trending threads with Redis as well as various other caching. 
+ Heavy use of S.O.L.I.D design principles, polymorphism and patterns including Strategy, Factory, Builder, Template.
+ Heavy use of the IoC container and other higher level Laravel concepts.


## Installation
 > __Note__: The latest update, v0.17.0 and onward uses Amazon S3 for user upload storage. If you do not have an Amazon S3 account but would still like to use the application, you may use v0.16.0 or below which still utilize the local storage driver. 

> ***Prequisite***: to run the project files you must have PHP 7, Redis, Mysql installed on your development machine. 

> __If you are a docker user:__ Instead of installing the dependencies listed above on your dev machine you can use the laradock submodule linked in this directory. 
>
> Make sure you edit the directories .env-example file to fit your needs and then rename it to .env before you run docker.

### For Docker: 

```
git clone --recurse-submodules git@github.com:ko1eda/levelup-forum.git

cd laradock 

mv ./.env-example ./.env

docker-compose up -d nginx mysql redis workspace
```
More info can be found at http://laradock.io/documentation/

Read these docs and then follow steps below

### Step 1 :
Clone the project to your development machine, cd into the project directory and install all composer dependencies.  You will also need to generate an application key. 

```
git clone git@github.com:ko1eda/levelup-forum.git

cd levelup-form && composer install

php artisan generate:key
```

### Step 2:
Initialize the relevant services (mysql, redis) insert the relevant information into the projects included .env.example file, when you are finished rename the file to .env

__Note:__ If you are using docker you would use container hostnames instead of the local loopback address for _HOST fields 

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=level_up_forum
DB_USERNAME=root
DB_PASSWORD=


REDIS_HOST=127.0.0.1 
REDIS_PASSWORD=null
REDIS_PORT=6379

```
### Step 3:
After creating and wiring up your database you must then run all the included migration files

``` php artisan migrate ```

  
### Step 4:

After migrating the database you can generate dummy content using

``` php artisan db:seed ```

This will create 50 random users, 50 random posts and 500 replies.


### Step 5:

Finally, Start up a development server, visit localhost/threads in your browser, register a new user and enjoy.
