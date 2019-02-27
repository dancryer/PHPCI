It is possible to use Stashes External Post Receive Hooks. 
Create a post_receive.sh script with execution rights.
Have the EPRH execute the shell script with a positional parameter of your PHPCI build id.

        PROJECT_ID=$1
        PHPCI_URL="http://ci.site.com"
        while read from_ref to_ref ref_name; do
                BRANCH=$(git rev-parse --symbolic --abbrev-ref $ref_name)
                echo "Sending webhook"
                curl "$PHPCI_URL/webhook/git/$PROJECT_ID?branch=$BRANCH&commit=$to_ref"
        done

Optional, here is a way to send the stash user e-mail and the first 50 characters of the commit message.
```
#!/bin/bash
PROJECT_ID=$1
PHPCI_URL="http://ci.site.com"
while read from_ref to_ref ref_name; do
        BRANCH=$(git rev-parse --symbolic --abbrev-ref $ref_name)
        COMMITMESSAGE=$(git log -n 1 --pretty=format:%s $to_ref | cut -c1-50)
        curl -G \
                "$PHPCI_URL/webhook/git/$PROJECT_ID" \
                --data branch=$BRANCH \
                --data commit=$to_ref \
                --data committer=$STASH_USER_EMAIL \
                --data-urlencode message="$COMMITMESSAGE"
done
```