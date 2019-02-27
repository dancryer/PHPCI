# Run Builds Using a Daemon

The PHPCI daemon runs in the background on your server and continuously checks for new builds. Unless already running a build, the daemon should pick up and start running new builds within seconds of being created.

The daemon is also useful if you want to run multiple PHPCI workers in a virtualised environment (i.e. Docker)

If you want to run PHPCI builds on a regular schedule instead, you should [set up a cron-job](Run-Builds-Using-Cron).

## Starting the Daemon

On a Linux/Unix server, the following command will start the daemon and keep it running even when you log out of the server:

```sh
nohup php ./daemonise phpci:daemonise >/dev/null 2>&1  &
```

If you need to debug what's going on with your builds, you can also run the daemon directly using the following command, which will output the daemon's log directly to your terminal:

```sh
php daemonise phpci:daemonise
```