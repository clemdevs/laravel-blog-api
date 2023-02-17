## Project description

This is an example blog with Laravel using just back-end with authorization.

1. Administrator can Create/Edit/Update/Delete posts, categories, comments, tags.
2. Administrator can upload an image to post.
3. User can view posts with categories, tags, and comments (only approved comments).
4. User can add comments to posts and edit their own comments. 
5. User/Administrators can search for posts by categories/tags.
6. When showing a category/tag user can see the appropriate post and vice-versa.

## To run the project

1. Clone the repo.
2. Navigate to the project's folder and run `composer install` to install or update dependencies.
3. Create a new database and configure the .env file accordingly.
4. run `php artisan migrate:fresh --seed;` to seed the database with demo data.
5. run `php artisan serve;`
You can also run some tests with `php artisan test` command.

There is a Postman collection in `/postman` folder. You can also import it in Postman for testing.
