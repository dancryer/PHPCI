The PHPCI configuration on the server is automatically generated into the `phpci/PHPCI/config.yml` file during installation. One might need to also edit the file manually.

For example, to [disable authentication](https://www.phptesting.org/news/phpci-1-5-released), one could log into phpci and go into the settings to disable it. But if you have already set up a username/password pair and have forgotten the password, and if the server is on a local network, and it's not sending the `forgot password` email, then editing the config file manually would be handy. To do so, just edit the `phpci` section in the config file (which is in [yaml format](https://en.wikipedia.org/wiki/YAML)), and add

    phpci:
        authentication_settings:
          state: 1
          user_id: 1

where you can get the user_id by logging into the mysql database and selecting your user ID from the `users` table in the `phpci` database.