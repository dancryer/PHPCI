Triggers a deployment of the project to run via [Deployer](http://phpdeployment.org)

**Configuration Options:**
### Options
* **webhook_url** [required, string] - The URL to your Deployer WebHook 
* **reason** [optional, string] - Your deployment message. Default - PHPCI Build #%BUILD% - %COMMIT_MESSAGE%
* **update_only** [optional, bool, true|false] - Whether the deployment should only be run if the currently deployed branches matches the one being built. Default - true

### Example
```yaml
success:
    deployer:
        webhook_url: "https://deployer.example.com/deploy/QZaF1bMIUqbMFTmKDmgytUuykRN0cjCgW9SooTnwkIGETAYhDTTYoR8C431t"
        reason: "PHPCI Build #%BUILD% - %COMMIT_MESSAGE%"
        update_only: true
```