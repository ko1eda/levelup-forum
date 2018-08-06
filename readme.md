# Levelup Forum


An open source forum project to showcase my ongoing efforts to master fullstack development. This project is still in development.


## Features
+ Thread subscriptions and user notification system.
+ Editable thread comments with favorites and @mentions.
+ Customizeable user profiles.
+ User avatar and photo uploads.
+ Configurable spam detection system with keyword detection, repeated key detection and comment throttling.
+ User registration system with mailing.
+ Thread filters for trending, popular, new, etc.
+ Caching with Redis.
+ Heavy use of S.O.L.I.D design principles, polymorphism and patterns including Strategy, Factory, Builder, Template.
+ Heavy use of the IoC container and other higher level Laravel concepts.


## Installation

> ***Prequisite***: to run the project files you must have PHP 7 installed on your development machine.

### Step 1 :
Clone the project to your development machine, cd into the project directory and install all composer dependencies.  You will also need to generate an application key. 

```
git clone git@github.com:ko1eda/levelup-forum.git
cd levelup-form && composer install 
php artisan generate:key 
```

### Step 2:
Create a database and insert the relevant information for username/password into the projects included .env.example file, when you are finished rename the file to .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=level_up_forum
DB_USERNAME=root
DB_PASSWORD=
```
### Step 3:
After creating and wiring up your database you must then run all the included migration files

``` php artisan migrate ```

  
### Step 4:

After migrating the database you can generate dummy content using

``` php artisan db:seed ```

This will create 50 random users, 50 random posts and 500 replies.


### Step 5:

Finally, Start up a development server, visit http:://levelupforum.test/threads in your browser, register a new user and enjoy.
