### Status Image
Most Continuous Integration systems provide a simple image URL that you can use to display your project status on other web sites (like Github) - PHPCI is no different.

You can find the status image at the following location: `http://{PHPCI URL}/build-status/image/{PROJECT ID}`

So for example, our instance of PHPCI is at `phpci.block8.net`, and our PHPCI project ID is `2`, so the image URL is: `http://phpci.block8.net/build-status/image/2`.

Example:

![](http://phpci.block8.net/build-status/image/2)

### Status Page
PHPCI also provides a public project status page, that is accessible for everyone.

You can find the status page at the following location: `http://{PHPCI URL}/build-status/view/{PROJECT ID}`

Example:
http://phpci.block8.net/build-status/view/2

#### Where do I find my project ID?
Go to your instance of PHPCI, and open the project you are interested in. The project ID is the number in the last part of the URL in your browser.

Example:
http://phpci.block8.net/project/view/2 ~> PROJECT ID: `2`

#### Enable/disable status image and page
You can enable or disable access to the public status image and page in your project's settings.