This plugin joins a [HipChat](https://www.hipchat.com/) room and sends a user-defined message, for example a "Build Succeeded" message.

**Configuration Options:**

| Field | Required? | Description |
|-------|-----------|-------------|
| `authToken` | Yes | Your HipChat API authentication token (v1) |
| `room`      | Yes | Your Hipchat room name or ID number. This can also be an array of room names or numbers, and the message will be sent to all rooms. |
| `message`   | No  | The message to send to the room. Default - `%PROJECT_TITLE% built at %BUILD_URI%` |
| `color`     | No  | Message color. Valid values: yellow, green, red, purple, gray, random. Default - `yellow`|
| `notify`    | No  | Whether or not this message should trigger a notification for people in the room (change the tab color, play a sound, etc). Default - `false`. |

Message can be formatted via HTML. Example:
```html
<b>%PROJECT_TITLE%</b> - build <a href="%BUILD_URI%">%BUILD%</a> failed!
```

Configuration example: 
```yaml
hipchat_notify:
    authToken: 123
    room: 456
    message: <b>%PROJECT_TITLE%</b> - build <a href="%BUILD_URI%">%BUILD%</a> failed!
    color: red
    notify: true
```
