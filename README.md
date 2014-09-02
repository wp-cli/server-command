`wp server`
===========

This is a package that implements the `wp server` command for [WP-CLI](http://wp-cli.org).

It uses the [PHP built-in server](http://php.net/manual/en/features.commandline.webserver.php) to serve a given WordPress instance.

The main advantage is that you don't have to install and configure Nginx or Apache, which can be overkill if you just want to work on a theme or a plugin.

### Requirements

* PHP 5.4 or newer
* WordPress 3.5 or newer

### Installation

See <https://github.com/wp-cli/wp-cli/wiki/Community-Packages#installing-a-package-without-composer>

### Usage

Just run it:

```bash
$ wp server
PHP 5.5.1 Development Server started at Wed Aug 14 03:34:13 2013
Listening on http://localhost:8080
Document root is /Users/User/wp/core
Press Ctrl-C to quit.
```

### How is this different from [Whippet](https://github.com/dxw/whippet/)?

The main difference is that we don't modify the `wp-config.php` file, so you can run other WP-CLI commands while the server is running.

### Can I add custom rewrite rules?

Yep; just write a WordPress plugin that checks `PHP_SAPI == 'cli-server'` and redirects as appropriate.

For example, here's a working plugin for the Roots theme: [leoj3n/wp-server-roots-rewrites](https://github.com/leoj3n/wp-server-roots-rewrites/).
