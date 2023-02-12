## Project details

This is proejct built from the ground up using the Slim v3 micro framework.
The goal of the project was to build an MVC blogging website with CRUD for users and blog posts.
Here's what it includes:

- User account creation and deletion
- Authentication system with pepperred password security
- Request validation & user-facing error reporting
- User roles and their management
- Page restrictions based on the access level of the user
- Blog Post CRUD operations based on permissions
- File management system
- Environment configuration
- FE: Active page navigation + image previews when uploading

## How it was achieved

Technologies involved:
- Views - uisng the [Twig-View](https://github.com/slimphp/Twig-View) engine
- Bootstrap 4 - Layout, Components, Partials. Styling from [Eterna Theme](https://bootstrapmade.com/eterna-free-multipurpose-bootstrap-template/)
- Routing
- Controllers
- ORM Models - built on Laravel's [illuminate/database](https://github.com/illuminate/database) package
- Middleware for access restriction and error management
- Exception Handling
- Migration System
- Seeding System
- Commands - Implemented using [adrianfalleiro/slim-cli-runner](https://github.com/adrianfalleiro/slim-cli-runner)'s Middleware package
- Application Services with configurable params
- Tests

## Setup

Prerequisites:
- MySQL Databse called "microblog"
- PHP 8.2
- Apache or nginx webserver configuration pointing to the `public` folder as its document root. See [Slim v3 Configuration Guide](https://www.slimframework.com/docs/v3/tutorial/first-app.html#run-your-application-with-apache-or-nginx) for more details.


Run `composer setup` to setup the application.
This command will create an `.env.php` configuration file for your application.
Make sure to fill in these 3 things:
- database username
- database password
- hasher pepper

After your application is configured you can now run `composer seed`.
This command will run all of the necessary migrations for the application and will seed it with data (a single user role).

That's it!

## Testing

You can run `composer test` to run the application tests.
Some of the tests will actually interact with the DB, so make sure to configure a unique email in your `env.php` configuration so that it won't affect your real data.

## Publishing
Since this is a blogging site after all, feel free to use some of the provided Gallery images for your blog posts.
