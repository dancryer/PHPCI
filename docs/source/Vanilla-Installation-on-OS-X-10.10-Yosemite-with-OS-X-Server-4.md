**Important: Please reference [Installing PHPCI](https://github.com/Block8/PHPCI/wiki/Installing-PHPCI) for additional information!**

### OS X Server
Install latest OS X Server (4.x as of this writing) from the App Store.

### Xcode Commandline Tools
1. Open a Terminal and type: `xcode-select --install` and click the Install button in the resulting alert window.

### MySQL
1. [Download](http://dev.mysql.com/downloads/mysql/) the latest (5.6.x as of this writing) version of MySQL Server (DMG Archive). Note that as of this writing, you need to select the DMG for OS X 10.9, even though we are installing on 10.10. The limitation is that the 
2. Open the archive and proceed through the installation.
3. At the time of this writing, MySQL installer will display a warning, indicating that the installation was not successful if you have a fresh installation of Yosemite. This warning should not display if you did an upgrade installation of Yosemite. The problem is that MySQL will not automatically start after rebooting, so you will need to manually start it (see next step).
4. Open System Preferences, select MySql. Then click the **Start MySql Server** button.
5. Add MySql commands to your PATH (open a Terminal, then paste the following):

  ```
export PATH=/usr/local/mysql/bin:$PATH >> ~/.bash_profile
  ```

5. Set the root user password (substitute "{new-password}" with a password of your choice, of course):

  ```
/usr/local/mysql/bin/mysqladmin -u root password {new-password}
/usr/local/mysql/bin/mysqladmin -u root -p{new-password} -h localhost password {new-password}
/usr/local/mysql/bin/mysqladmin -u root -p{new-password} reload
history -c
  ```

That last command will clear the history cache to remove any clear-text traces of the password being entered in the terminal.
6. Use that password to log into mysql:

  ```
cd ~
/usr/local/mysql/bin/mysql -u root -p{new-password}
  ```

7. Create the new PHPCI database:

  ```
CREATE DATABASE phpci;
  ```

If this command fails, restart your server and try again.
8. Create the new PHPCI user, of course supplementing 'password' with your own password:

  ```
use mysql;
GRANT ALL PRIVILEGES ON *.* to 'phpci'@'localhost' IDENTIFIED by 'password' WITH GRANT OPTION;
FLUSH PRIVILEGES;
  ```
9. Exit MySQL if all went well using the `exit` command.
10. Test that the new PHPCI user has access:

  ```
mysql -u phpci -p
  ```
  If all went well, you should now be at the mysql prompt. Go ahead and exit back out.

### xDebug
Yosemite comes enabled for xDebug, so all we need to do is enable it in php.ini:

```
sudo nano /etc/php.ini
```

Now, search for 'extension=' using `CTRL-W`, then add the following under the existing comment section:

```
zend_extension = "xdebug.so"
```

And finally restart Apache:

```
sudo apachectl restart
```

### mCrypt
Install mCrypt:

1. Open a Terminal and enter:

  ```
cd ~
mkdir ~/mcrypt
cd ~/mcrypt
curl -O http://tcpdiag.dl.sourceforge.net/project/mcrypt/Libmcrypt/2.5.8/libmcrypt-2.5.8.tar.gz
  ```

2. Determine the version of PHP installed:

  ```
php -v
  ```

Yosemite 10.10 PHP is running at version 5.5.14. If your version varies, please adjust the file names in the following steps accordingly.
3. Download the appropriate [source code in tar.gz](http://php.net/releases/index.php) for your php version into the mcrypt folder.
4. Expand both files and remove the archives:

  ```
cd ~/mcrypt
tar -zxvf libmcrypt-*.tar*
tar -zxvf php-*.tar*
rm *.tar*
  ```

5. Configure libmcrypt:

  ```
cd ~/mcrypt/libmcrypt-*
./configure
make
sudo make install
  ```
6. Install autoconf:

  ```
cd ~/mcrypt
curl -O http://ftp.gnu.org/gnu/autoconf/autoconf-latest.tar.gz
tar xvfz autoconf-latest.tar.gz
cd autoconf-*/
./configure
make
sudo make install
  ```
7. Compile PHP mcrypt extension:

  ```
cd ~/mcrypt/php-*/ext/mcrypt/
/usr/bin/phpize
  ```
  The output should look something like the following:

  > PHP Api Version:         20121113

  > Zend Module Api No:      20121212

  > Zend Extension Api No:   220121212

  ```
./configure
make
sudo make install
  ```
  This should result in the following text at the end:

  > Installing shared extensions:      /usr/lib/php/extensions/no-debug-non-zts-20121212/
8. Add the module to php.ini:
  ```
sudo nano /etc/php.ini
  ```
  If this file is empty, `CTRL-X` out of nano, and do the following, then nano back into php.ini with the above command (otherwise skip this):

  ```
sudo cp /etc/php.ini.default /etc/php.ini
sudo chmod u+w /etc/php.ini
sudo nano /etc/php.ini
  ```
  Look for 'extension=' using `CTRL-W` again, and add the following under any existing entries:

  ```
extension=mcrypt.so
  ```
  Look for the `extension_dir` declaration using `CTRL-W`. It should look something like this (if you can't find it, add it under the "extension_dir" comments):

  > extension_dir = "/usr/lib/php/extensions/no-debug-non-zts-20121212/"
9. Restart Apache:

  ```
sudo apachectl restart
  ```
10. Verify that mcrypt is installed, by searching through the output for crypt (open a new Terminal window using `CMD-T`, then type the following command, then use `CMD-F` to find 'mcrypt'):

  ```
php -i
  ```
11. Delete the mcrypt folder we used for installation:

  ```
cd ~
sudo rm -R mcrypt
  ```

### MongoDB
**(Optional)** If your project uses MongoDB, you will need to install it as well:

1. Install the latest version of MongoDB:

  ```
mkdir ~/mongo
cd ~/mongo
curl -O http://fastdl.mongodb.org/osx/mongodb-osx-x86_64-2.4.9.tgz
tar -zxvf mongodb-osx-x86_64-2.4.9.tgz
sudo mkdir -p /usr/local/mongodb-osx-x86_64-2.4.9
  ```
2. Then (commands are separated to ensure that the sudo password isn't filled in by accident):

  ```
sudo cp -R -n mongodb-osx-x86_64-2.4.9/ /usr/local/mongodb-osx-x86_64-2.4.9
sudo mkdir /data
sudo mkdir /data/db
sudo chown `id -u` /data/db
sudo ln -s /usr/local/mongodb-osx-x86_64-2.0.0/ /usr/local/bin/mongodb
  ```
6. Add the binaries to your PATH variable:

  ```
export PATH=${PATH}:/usr/local/mongodb/bin
  ```
7. Test that MongoDB works:

  ```
cd ~
mongod
  ```
  It should start the mongodb service and wait for a connection. [Open your browser](http://localhost:28017) to connect.
8. Download and install the [MongoDB Preferences Pane](https://github.com/ivanvc/mongodb-prefpane/downloads). This will help with automatic startup on boot, etc. (Unfortunately it won't allow you to configure automatic start on boot, but that seems to be planned.) Add

  ```
/usr/local/mongodb/bin/mongod
  ```
  as the binary location, and turn it on. You will have to do this after each reboot.

#### PHP mongo Module
**(Optional)** Install PHP-mongo module, if you installed MongoDB.
1. Install php-mongo:

  ```
sudo pecl install mongo
  ```
2. Add extension to php.ini:

  ```
sudo nano /etc/php.ini
  ```
  Paste the following right below the mysql module we added earlier:

  ```
extension=mongo.so
  ```
  Then `CTRL-X` and save to exit.
3. Verify that php-mongo is installed, open a new Terminal:

  ```
php -i
  ```
  Search for 'mongo' using `CMD-F`.

### Web Server
Now we need to install the web server:

  1. Open Server App, and go to the first item, typically named after your computer.
  2. Change your Host name to the url you want your machine accessed by (i.e. from the internet).
  3. Change your computer name to something that makes sense.
  4. Next, click on the Websites menu item, but don't turn the service on just yet.
  5. Make sure that 'Enable PHP web applications' is checked.

### PHPCI
Download PHPCI:

1. Open a Terminal:

  ```
cd /Library/Server/Web/Data/Sites
sudo git clone https://github.com/Block8/PHPCI.git
  ```
2. Set ownership of files to your user and make composer.json writable.:

  ```
cd PHPCI
sudo chmod 766 composer.json
  ```

### Configure your web site:
1. If you want to host on a port other than 80, create a new website. Otherwise open the default web site (called "Server Website") settings by double-clicking on it.
2. Set the following items:

  1. Specify the root folder as '/Library/Server/Web/Data/Sites/PHPCI/public'
  2. Edit Advanced Settings and make sure that .htaccess overrides are turned on.
  3. Press OK.
3. Back at the terminal, edit the appropriate vhosts file in '/Library/Server/Web/Config/apache2/sites/'. If you are using the default site on port 80, it will be called 0000_any_80_.conf. Example:

  ```
sudo nano /Library/Server/Web/Config/apache2/sites/0000_any_80_.conf
  ```
4. Make the following changes:

  1. Change the DocumentRoot to `"/Library/Server/Web/Data/Sites/PHPCI/public"`.
  2. Change the Directory directive to `<Directory "/Library/Server/Web/Data/Sites/PHPCI">`.
  3. Change AllowOverride to `All`.
  4. Add the following before the closing </Directory> tag:

  ```
                RewriteEngine On
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteCond %{REQUEST_FILENAME} !-d
                RewriteRule . /index.php [L]
  ```
  5. Rename the supplied .htaccess.dist file to .htaccess:

  ```
cd /Library/Server/Web/Data/Sites/PHPCI/public
sudo mv .htaccess.dist .htaccess
  ```

5. Install Composer locally to PHPCI:

  ```
cd /Library/Server/Web/Data/Sites/PHPCI
sudo curl -sS https://getcomposer.org/installer | sudo php
sudo mv composer.phar composer
sudo chmod +x composer
  ```

6. Let's make sure the project vendors are installed and updated:

  ```
sudo ./composer update
  ```

7. Run the following command to finish the installation on the Terminal (this will help to determine if any errors occur, as you wouldn't be able to tell using the web interface):

  ```
cd /Library/Server/Web/Data/Sites/PHPCI/
sudo ./console phpci:install
  ```

Be sure to specify `127.0.0.1` instead of localhost as the host, as well as the domain name you specified earlier when configuring the Server app.
8. Back in the Server App, go to Web Sites, and turn the service on.
9. You should now be able to browse to your PHPCI server by clicking on the arrow at the bottom, by default called 'View Server Website'.
10. If everything is working as expected, rename the install.php file (for safety reasons):

  ```
sudo mv /Library/Server/Web/Data/Sites/PHPCI/public/install.php /Library/Server/Web/Data/Sites/PHPCI/public/install.old
  ``` 

### First Time Builds with GIT
The first time you add a project from a new git repository, be sure to run the build manually, as you might otherwise encounter the following error:

  ```
Failed to clone remote git repository.
  ```
To get around this, schedule a build in PHPCI, then run the build command manually:

  ```
sudo /usr/bin/php /Library/Server/Web/Data/Sites/PHPCI/console phpci:run-builds
  ```
This will add the RSA key for your git repo host to your key file, essentially telling the system that you trust this server. (This is discussed in detail in [issue #114](https://github.com/Block8/PHPCI/issues/114).)

### Automatic Builds with Cron Jobs
Perform the following command in a Terminal to enable the automatic build process:

```
env EDITOR=nano crontab -e
```
Then paste the following:

```
* * * * * sudo /usr/bin/php /Library/Server/Web/Data/Sites/PHPCI/console phpci:run-builds
```
Hit `CTRL-X`, save and quit. Now PHPCI will monitor every minute for pending jobs and fire them off.