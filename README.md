# Meczyki

Aplikacja do zbierania informacji o posędziowanych meczach.

## Getting Started

### Requirements

https://symfony.com/doc/6.0/reference/requirements.html

* PHP version: 8.0 or higher
* Apache
* MySQL

### Frontend

```bash
$ yarn install
$ yarn build
```

## Configuration

### Admin user

```bash
# Create new user
$ php bin/console mg:user:create username email@exmple.com password
# Grant administrator privileges
$ php bin/console mg:user:admin username
```
