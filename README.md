# Lite Stack PHP

## Summary
PHP Lite Stack is a collection of public libraries that work together in harmony.

PHP Lite Stack is provided as a broiler plate to quickly drop into an Apache or Nginx environment.

PHP Lite Stack has been used on small and large projects -- even if libraries say they should only  be small.

PHP Lite Stack provides an MVC - Model View Controller environment that anyone can easily become accustomed to,
which will save your time and headaches with eased development, and organized code and structure.

## Frameworks

### [Slim](http://slimframework.com/) - v2.4.3
*Controller*

Handles the routing for the site. Allows you to create "Fancy / Virtual URLs" and easy RESTful services.
> Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs

### [Bootstrap](http://getbootstrap.com/) - v3.3.4
*Frosting*

Used to provide a mobile first approach as well as standardized basic visual styles.
> Bootstrap, a sleek, intuitive, and powerful mobile first front-end framework for faster and easier web development.
> Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web.

### [jQuery](https://jquery.com/) - v1.11.2
*Frosting*

Used to provide a browser unified JavaScript environment that builds in best practices and allows for 
easy DOM selection and navigation.
> jQuery is a fast, small, and feature-rich JavaScript library. It makes things like HTML document traversal 
and manipulation, event handling, animation, and Ajax much simpler with an easy-to-use API that works across a 
multitude of browsers. With a combination of versatility and extensibility, jQuery has changed the way that 
millions of people write JavaScript.

### [LESS PHP](http://leafo.net/lessphp/) - Custom Modified v0.4.0
*Frosting*

Compiles less files via PHP.  Custom caching was added to prevent rebuilding the less every time.
#### LESS CSS
Used to give semantic meaning to plain CSS as well as better structure and
create more efficient & manageable code.

### [Idiorm & Paris](http://j4mie.github.io/idiormandparis/) - v1.5.1 & v1.5.4
*Model*

Used as an ORM for database communication.  Switch between different database types
without worrying about syntax differences.
> A minimalist database toolkit for PHP5.
> Idiorm is an object-relational mapper and fluent query builder.
> Paris is an Active Record implementation based on Idiorm.
> > Database agnostic. Currently supports SQLite, MySQL, Firebird and PostgreSQL. May support others, please give it a try!

### [Twig](http://twig.sensiolabs.org/) - v1.16.2
*View*

Handles the templating for the site.
> Twig is a modern template engine for PHP
> The flexible, fast, and secure template engine for PHP

### Other Recommended Libraries

#### Google Fonts
Used as a CDN to provide custom fonts

#### Leafletjs
Provides a mobile friendly widget to interact with map tiles
##### OpenStreetMap
Provides the tiles that Leaflet displays

## Folder Structure

### Public vs Private
PHP Lite Stack is divided into a public and private layout.
You may need to rename the web folder to www, public_html, html, whatever your server is currently
using as the root for your web server.
The private folder is inaccessible to the public but available to your code, and should thus
be in the same directory as your public folder and *NOT contained within it*
If your host does not allow you to have access to the directory above the web root,
you can place the private directory into the root but you will need to restrict its permissions
and update pathing in index.php.

### Libraries
For example /web/assets/js/libs/ there are a few folders with lib folders
the lib folders should be used for 3rd party code, where as the parent level is used for
pages and original content created for the site.

### LESS
LESS is compiled by calling *doLessFileCached(fileName)*
It will look in /private/assets/less/ for the file
which gets compiled to /web/assets/css/
```php
doLessFileCached('FILENAME');
```