`wp server`
===========

This is a package that implements the `wp server` command.

It uses the [PHP built-in server](http://php.net/manual/en/features.commandline.webserver.php) to serve a given WordPress instance.

The main advantage is that you don't have to install and configure Nginx or Apache, which can be overkill if you just want to work on a theme or a plugin.

### Requirements

* PHP 5.4 or newer
* WordPress 3.5 or newer

### Installation

First, make sure you have the [package index](http://wp-cli.org/package-index/) configured:

```
cd ~/.wp-cli/
php composer.phar config repositories.wp-cli composer http://wp-cli.org/package-index/
```

Then, just install the package:

```
php composer.phar require wp-cli/server-command=dev-master
```

### How is this different from [Whippet](https://github.com/dxw/whippet/)?

The main difference is that we don't modify the `wp-config.php` file, so you can run other WP-CLI commands while the server is running.

### Can I add custom rewrite rules?

Yep; just write a WordPress plugin that checks `PHP_SAPI == 'cli-server'` and redirects as appropriate.

For example, here's a working plugin for the Roots theme: [leoj3n/wp-server-roots-rewrites](https://github.com/leoj3n/wp-server-roots-rewrites/).
