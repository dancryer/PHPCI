This plugin joins a [Slack](https://www.slack.com/) room and sends a user-defined message, for example a "Build Succeeded" message.

**Configuration Options:**

| Field | Required? | Description |
|-------|-----------|-------------|
| `webhook_url` | Yes | The URL to your Slack WebHook |
| `room`      | No | Your Slack room name. Default - #phpci |
| `username`  | No | The name to send the message as. Default - PHPCI |
| `icon`      | No | The URL to the user icon or an emoji such as :ghost:. Default - The value configured on Slack's WebHook setup |
| `message`   | No | The message to send to the room. Default - `<%PROJECT_URI%|%PROJECT_TITLE%> - <%BUILD_URI%|Build #%BUILD%> has finished for commit <%COMMIT_URI%|%SHORT_COMMIT% (%COMMIT_EMAIL%)> on branch <%BRANCH_URI%|%BRANCH%>` |
| `show_status` | No | Whether or not to append the build status as an attachment in slack. Default - true

Send a message if the build fails:
```yaml
failure:
    slack_notify:
        webhook_url: "https://hooks.slack.com/services/R212T827A/G983UY31U/aIp0yuW9u0iTqwAMOEwTg"
        room: "#phpci"
        username: "PHPCI"
        icon: ":ghost:"
        message: "%PROJECT_TITLE% - build %BUILD% failed! :angry:"
        show_status: false
```

Send a message if the build is successful:
```yaml

success:
    slack_notify:
        webhook_url: "https://hooks.slack.com/services/R212T827A/G983UY31U/aIp0yuW9u0iTqwAMOEwTg"
        room: "#phpci"
        username: "PHPCI"
        icon: ":ghost:"
        message: "%PROJECT_TITLE% - build %BUILD% succeeded! :smiley:"
        show_status: false
```

Send a message every time the build runs:

```yaml
complete:
    slack_notify:
        webhook_url: "https://hooks.slack.com/services/R212T827A/G983UY31U/aIp0yuW9u0iTqwAMOEwTg"
        room: "#phpci"
        username: "PHPCI"
        icon: ":ghost:"
        message: "%PROJECT_TITLE% - build %BUILD% completed"
        show_status: true
```