**From email address:**

If you want to change the from email address, where the application send emails from, than you are able to change the configuration in /phpci/PHPCI/config.yml like this:

    b8:
        database: { servers: { read: localhost, write: localhost }, name: example, username: example, password: example }
    phpci:
        url: 'http://phpci.example.com'
        email_settings:
            from_address: 'phpci@example.com'