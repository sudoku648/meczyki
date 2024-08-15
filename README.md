# Meczyki

Aplikacja do zbierania informacji o posędziowanych meczach.

## Getting Started

### Requirements

https://symfony.com/doc/7.1/reference/requirements.html

* PHP version: 8.3 or higher
* Apache
* MySQL

### Frontend

```bash
$ yarn install
$ yarn build
```

## Configuration

### Super admin user

```bash
# Create new user
$ php bin/console app:user:create username password
# Grant super administrator privileges
$ php bin/console app:user:sa username
```
