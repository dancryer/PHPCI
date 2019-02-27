If you would like your builds to run automatically whenever there is a commit or other similar activity in your GitHub repository, perform the following steps:

1. Log into PHPCI.
2. Click on your GitHub project.
3. Copy the web hook link specified on the right of the page.
4. Log into GitHub and go to your repository.
5. Click the settings icon on the lower right sidebar.
6. Click on "Webhooks & Services".
7. Add your web hook link you copied above, and specify when you would like it to run.
8. Add the public key for the project in PHPCI to the deploy keys for the repository on GitHub.
9. Verify that the initial test delivery was successful. If not, make sure that your PHPCI machine is reachable from the internet.