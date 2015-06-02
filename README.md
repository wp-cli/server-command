**IMPORTANT:** The server command now comes [bundled with WP-CLI](https://github.com/wp-cli/wp-cli/pull/1858). This repository is kept for historical purposes only.

`wp server`
===========

It uses the [PHP built-in server](http://php.net/manual/en/features.commandline.webserver.php) to serve a given WordPress instance.

The main advantage is that you don't have to install and configure Nginx or Apache, which can be overkill if you just want to work on a theme or a plugin.

### How is this different from [Whippet](https://github.com/dxw/whippet-server)?

The main difference is that we don't modify the `wp-config.php` file, so you can run other WP-CLI commands while the server is running.

### Can I add custom rewrite rules?

Yep; just write a WordPress plugin that checks `PHP_SAPI == 'cli-server'` and redirects as appropriate.

For example, here's a working plugin for the Roots theme: [leoj3n/wp-server-roots-rewrites](https://github.com/leoj3n/wp-server-roots-rewrites/).
