Maintainers help to keep the PHPCI project moving, they do this by helping to manage issues, merging pull requests, improving documentation and by contributing code to the project. 

Want to be a maintainer? Email Dan at dan@dancryer.com for more information.

The current team of maintainers are as follows:

**Core Maintainers**

* Dan (@dancryer)

**Maintainers**

* This project is actively seeking maintainers. Please contact @dancryer if you are interested in helping to keep it going.

## Guidelines for Maintainers

### Commit Access

Maintainers, by definition, have commit access to the project and can commit changes on behalf of contributors. However, with the exception of the core maintainers, maintainers are not entitled to bypass the contribution / pull request process. 

### Merging Pull Requests

It is the responsibility of the maintainers to merge pull requests, but only where they meet the contribution guidelines and pass our merging checklist.

Once the checklist has been completed, the pull request should be merged using the "Squash and Merge" option.

**The Checklist**

**Is the pull-request ready to be merged?**

_The maintainer should check that the author has fully completed the bug fix / feature / refactor before merging the pull request._

**Has the author properly explained the intended purpose of their pull request, and detailed their changes?**

_Authors need to explain in clear, concise language, what their patch is intended to do. Where their implementation is large or in any way complex, the changes themselves should be documented at the code level._

**Has the author provided documentation updates for any new or changed features?**

_The wiki should not be updated until the pull request is merged, but documentation updates should be detailed in the pull request itself._

**Has the pull request passed PHPCI checks?**

_It goes without saying that the project master branch should always pass its own PHPCI tests. This means that the patch will need to be PSR-2 compliant, all classes and methods should have valid docblocks, and all tests should pass._

**Have at least two maintainers approved this pull request?**

_At least two maintainers should approve every pull request before it is merged. If you are the second maintainer to review a pull request, pending your approval it can be merged. Otherwise, apply the "flag:ready-to-merge" label to the pull request and consider tagging another maintainer to follow you._
