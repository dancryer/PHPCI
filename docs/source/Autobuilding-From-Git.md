## Requirements
- A git repository on a server (bare or plain does not matter)
- [curl](http://curl.haxx.se) to send the web hook

## Installation
1. Create a new file `post-receive` inside the [git `hooks` directory](http://www.git-scm.com/book/en/Customizing-Git-Git-Hooks) with the following content:

```shell
#!/bin/sh

PROJECT_ID=1
PHPCI_URL="http://my.server.com/PHPCI/"

trigger_hook() {
        NEWREV="$2"
        REFNAME="$3"

        if [ "$NEWREV" = "0000000000000000000000000000000000000000" ]; then
                # Ignore deletion
                return
        fi

        case "$REFNAME" in
                # Triggers only on branches and tags
                refs/heads/*|refs/tags/*) ;;
                # Bail out on other references
                *) return ;;
        esac

        BRANCH=$(git rev-parse --symbolic --abbrev-ref "$REFNAME")
        COMMITTER=$(git log -1 "$NEWREV" --pretty=format:%ce)
        MESSAGE=$(git log -1 "$NEWREV" --pretty=format:%s)

        echo "Sending webhook"
        curl \
	        --data-urlencode branch="$BRANCH" \
	        --data-urlencode commit="$NEWREV" \
	        --data-urlencode committer="$COMMITTER" \
	        --data-urlencode message="$MESSAGE" \
	        "$PHPCI_URL/webhook/git/$PROJECT_ID"
}

if [ -n "$1" -a -n "$2" -a -n "$3" ]; then
        PAGER= trigger_hook $1 $2 $3
else
        while read oldrev newrev refname; do
                trigger_hook $oldrev $newrev $refname
        done
fi
```

2. Change the file to be executable: `chmod a+x post-receive`
3. Push changes to the repository