**Example 1:**

`curl --data "branch=<branch>&commit=<commit>" http://phpci.example.com/webhook/git/<project_id>`

**Example 2:**

`curl http://phpci.example.com/webhook/git/<project_id>`

The default branch is the master branch.

You can create a cron with one of the commands above:

`0 1 * * * /usr/bin/curl http://phpci.example.com/webhook/git/<project_id>`