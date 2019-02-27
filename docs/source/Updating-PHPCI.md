Updating PHPCI to the latest release, or even dev-master updates is something that will need to be done from time to time. Most of this may be self-explanatory, but for clarity and completeness, it should be added to the documentation.

1. Go to your PHPCI root folder in a Terminal.
2. Pull the latest code. On Linux and Mac this would look like this: `git pull`
3. Update the composer and its packages: `composer self-update && composer install`
4. Update the PHPCI database: `./console phpci:update`
5. Return to the PHPCI admin screens and check your desired plugins are still installed correctly.
7. Run a build to make sure everything is working as expected.