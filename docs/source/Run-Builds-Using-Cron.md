Running builds using cron is a quick and simple method of getting up and running with PHPCI. It also removes the need for PHPCI to be running all the time.

If you want a little more control over how PHPCI runs, you may want to [set up the PHPCI daemon](/Block8/PHPCI/wiki/Run-Builds-Using-a-Daemon) instead.

## Setting up the Cron Job:

You'll want to set up PHPCI to run as a regular cronjob, so run `crontab -e` and enter the following:

```sh
* * * * * /usr/bin/php /path/to/phpci/console phpci:run-builds
```

**Note:** Make sure you change the `/path/to/phpci` to the directory in which you installed PHPCI, and update the PHP path if necessary.