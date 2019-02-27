Builds a tar or zip archive of your build and places it in a directory of your choosing.

### Configuration Options:

* **directory** - Required - Directory in which to put the package file.
* **filename** - Required - File name for the package.
* **format** - Required - `tar` or `zip`

You can use following variables in filename:

* %build.commit%
* %build.id%
* %build.branch%
* %project.title%
* %date%
* %time%