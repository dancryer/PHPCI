In order to access the PHPCI web interface, you need to set up a virtual host in your web server. 

Below are a few examples of how to do this for various different web servers.

## Nginx Example:

```
server {
        ... standard virtual host ...

        location / {
                try_files $uri @phpci;
        }

        location @phpci {
                # Pass to FastCGI:
                fastcgi_pass    unix:/path/to/phpfpm.sock;
                fastcgi_index   index.php;
                fastcgi_buffers 256 4k;
                include         fastcgi_params;
                fastcgi_param   SCRIPT_FILENAME $document_root/index.php;
                fastcgi_param   SCRIPT_NAME index.php;
        }
}
```

## Apache Example:

For Apache, you can use a standard virtual host, as long as your server supports PHP. All you need to do is add the following to a `.htaccess` file in your PHPCI `/public` directory.

```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
</IfModule>
```

### Installing in Vagrant
- Edit virtual host in apache2.
```
<VirtualHost *:80>
    ServerAdmin user@domain.com
    DocumentRoot /var/www/phpci/public
    ServerName phpci.vagrant
    ServerAlias phpci.vagrant

    <Directory /var/www/phpci/public >
        Options Indexes FollowSymLinks
        AllowOverride All
        # comment the following line if meet 500 server error
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/phpci-error_log
    CustomLog ${APACHE_LOG_DIR}/phpci-access_log combined
</VirtualHost>
```

- In Mac OS X add in /etc/hosts
```
127.0.0.1   phpci.vagrant
```
## Other Servers

### Lighttpd Example (lighttpd >= `1.4.24`):

This example uses the `$HTTP["host"]` conditional block because `$HTTP["url"]` blocks aren't supported with url rewriting in lighttpd <= `1.4.33` (source: [lighttpd docs for mod_rewrite](http://redmine.lighttpd.net/projects/1/wiki/Docs_ModRewrite)). In lighttpd >= `1.4.34` however, [this has been fixed](http://redmine.lighttpd.net/issues/2526).

### lighttpd <= `1.4.33`
```
$HTTP["host"] =~ "^phpci\.example\.com$" {
        # Rewrite all requests to non-physical files
        url.rewrite-if-not-file =
        (
            "^(.*)$" => "index.php/$1"
        )
}
```

### lighttpd >= `1.4.34`
```
$HTTP["url"] =~ "^\/PHPCI/$" {
        # Rewrite all requests to non-physical files
        url.rewrite-if-not-file =
        (
            "^(.*)$" => "index.php/$1"
        )
}
```

If you ~~would like~~ are forced to use lighttpd <= `1.4.24`, [you can use mod_magnet and Lua instead] (http://redmine.lighttpd.net/projects/1/wiki/AbsoLUAtion).

### Built-in PHP Server Example:

You can use the built-in PHP server `php -S localhost:8080` by adding `public/routing.php`.

```php
<?php

if (file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
    return false; // serve the requested resource as-is.
} else {
    include_once 'index.php';
}
```