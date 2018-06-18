# Levelup Forum 

An open source forum project to showcase my ungoing efforts to master fullstack development. This project is still in development.

**10/17/18:** I appologize for the unstyled login and registration forms pls bear with me :-(

**10/17/18:** Also make sure after you register a user, you navigate to http:://levelupforum.test/threads as the home route is not currently set up.
 
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
Start up a development server, visit http:://levelupforum.test/threads in your browser, register a new user and enjoy.
