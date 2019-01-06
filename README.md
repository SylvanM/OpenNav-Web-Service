# OpenNav-Web-Service
Code to the web service that controls the database for OpenNav

## Organization
The website is organized like the database - layouts, and users. There is a file for all 'users' code as well as the associated SQL code, same thing for layouts.

### Users
The [users.php](https://github.com/SylvanM/OpenNav-Web-Service/blob/master/users.php) file controls all interaction with the user database. When the app OpenNav launches, it uploads its user code and id. This is done by calling the function:
```php
addUser($user_id, $public_key)
```
The function checks to see if the id is already in the user database. If not, it will create a SQl statement to add a user, and execute it with the runSQL() function in [userssql.php](https://github.com/SylvanM/OpenNav-Web-Service/blob/master/userssql.php)
