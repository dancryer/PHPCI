Connects to a given MySQL server and runs a selection of queries.

### Example Configuration:

```yaml
build_settings:
    mysql:
        host: 'localhost'
        user: 'testuser'
        pass: '12345678'

setup:
    mysql:
        - "CREATE DATABASE my_app_test;"

complete:
    mysql:
        - "DROP DATABASE my_app_test;"
```

Import SQL from file:
```yaml
setup:
    mysql:
        import-from-file:                   # This key name doesn´t matter
            import:
                database: "foo"             # Database name
                file: "/path/dump.sql"      # Relative path in build folder
```