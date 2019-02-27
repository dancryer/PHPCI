Connects to a given PostgreSQL server and runs a list of queries.

### Example Configuration:

```yaml
build_settings:
    pgsql:
        host: 'localhost'
        user: 'testuser'
        pass: '12345678'

setup:
    pgsql:
        - "CREATE DATABASE my_app_test;"

complete:
    pgsql:
        - "DROP DATABASE my_app_test;"
```