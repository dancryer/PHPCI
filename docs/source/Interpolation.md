# Variable Interpolation

Most strings used in the build configuration can have variables related to the build inserted into them with the following syntax:

"My important message is about %SOMETHING%"

Where something can be one of the following:


* **COMMIT** - The commit hash
* **SHORT_COMMIT** - The shortened version of the commit hash
* **COMMIT_EMAIL** - The email address of the committer
* **COMMIT_MESSAGE** - The message written by the committer
* **COMMIT_URI** - The URL to the commit
* **BRANCH** - The name of the branch
* **BRANCH_URI** - The URL to the branch
* **PROJECT** - The ID of the project
* **BUILD** - The build number
* **PROJECT_TITLE** - The name of the project
* **BUILD_PATH** - The path to the build
* **BUILD_URI** - The URL to the build in PHPCI