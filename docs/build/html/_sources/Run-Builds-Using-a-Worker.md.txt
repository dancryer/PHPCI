The PHPCI Worker (added in v1.7) runs in the background on your server and waits for new builds to be added to a Beanstalkd queue. Unless already running a build, the worker will pick up and start running new builds almost immediately after their creation.

The worker is the recommended way to run PHPCI builds. You can run several workers all watching one queue, allowing jobs to be run simultaneously without the overhead of polling your MySQL database. 

If you can't run Beanstalkd on your server, or would prefer to run builds on a regular schedule, you should consider using the [build daemon](https://github.com/Block8/PHPCI/wiki/Run-Builds-Using-a-Daemon) or [running builds via Cron](https://github.com/Block8/PHPCI/wiki/Run-Builds-Using-Cron).

## Pre-Requisites

* You need to install [Beanstalkd](http://kr.github.io/beanstalkd/) - On Ubuntu, this is as simple as running `apt-get install beanstalkd`.
* [Supervisord](http://supervisord.org/) needs to be installed and running on your server.

## Setting up the PHPCI Worker

### On a new installation:

Setting up the worker on a new installation of PHPCI is as simple as entering the appropriate values for your Beanstalkd server hostname and queue name when running the PHPCI installer. By default, the installer assumes that you'll be using beanstalkd on `localhost` and will use the queue name `phpci`.

![PHPCI Worker Installer](https://www.phptesting.org/media/render/f48f63699a04444630352643af18b643)

### On an existing installation:

On an existing installation, to set up the worker, you simply need to add the beanstalkd host and queue names directly into your `/PHPCI/config.yml` file. You should add a `worker` key beneath the `phpci` section, with the properties `host` and `queue` as outlined in the screenshot below:

![PHPCI Worker Config](https://www.phptesting.org/media/render/9a88e9298670f2913f5798e68b94c9ed)

## Running the PHPCI Worker:

Once you've set up PHPCI to add your jobs to a beanstalkd queue, you need to start the worker so that it can pick up and run your builds. On most servers, it is best to manage this using supervisord. The following instructions work on Ubuntu, but will need slight amendments for other distributions.

Using your preferred text editor, create a file named `phpci.conf` under `/etc/supervisor/conf.d`. In it, enter the following config:

```
[supervisord]
logfile = /tmp/supervisord.log
logfile_maxbytes = 50MB
logfile_backups = 10
loglevel = info
pidfile = /tmp/supervisord.pid

[program:phpci]
command=/path/to/phpci/latest/console phpci:worker
process_name=%(program_name)s_%(process_num)02d
stdout_logfile=/var/log/phpci.log
stderr_logfile=/var/log/phpci-err.log
user=phpci
autostart=true
autorestart=true
environment=HOME="/home/phpci",USER="phpci"
numprocs=2
```

You'll need to edit the '/path/to/phpci', the `user` value and the `environment` value to suit your server. The user needs to be an actual system user with suitable permissions to execute PHP and PHPCI.

Once you've created this file, simply restart supervisord using the command `service supervisor restart` and 2 instances of PHPCI's worker should start immediately. You can verify this by running the command `ps aux | grep phpci`, which should give you output as follows:

```
➜  ~ ps aux | grep phpci
phpci    19057  0.0  0.9 200244 18720 ?        S    03:00   0:01 php /phpci/console phpci:worker
phpci    19058  0.0  0.9 200244 18860 ?        S    03:00   0:01 php /phpci/console phpci:worker
```

That's it! Now, whenever you create a new build in PHPCI, it should start building immediately.