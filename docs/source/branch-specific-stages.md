PHPCI allows you configure plugins depending on the branch you configure in the project settings in the UI. You can replace a complete stage for a branch, or add extra plugins to a stage that run before or after the default plugins.

# Example config
    test: # Test stage config for all branches
        php_unit:
            allow_failures: 10
    success: # Success stage config for all branches
        shell: ./notify
    branch-release: # Test config for release branch
        run-option: replace # This can be set to either before, after or replace
        test:
            php_unit:
                allow_failures: 0
    branch-master: # Test config for release branch
        run-option: after # This can be set to either before, after or replace
        success:
            shell:
                - "rsync ..."

# How it works
When you have configured a branch eg "stable" in the project settings in the UI. Add a new config named "branch-BRANCH NAME", in this case "branch-stable" to the phpci.yml. In this config, specify all stages and plugins you wish to run.

Also add a new config value "run-option", that can heve 3 values:
* before: will cause the branch specific plugins to run before the default ones
* after: will cause the branch specific plugins to run after the default ones
* replace: will cause the branch specific plugins to run and the default ones not

# References
* https://github.com/Block8/PHPCI/issues/1045
* https://github.com/Block8/PHPCI/issues/1209
* https://github.com/Block8/PHPCI/blob/master/PHPCI/Plugin/Util/Executor.php