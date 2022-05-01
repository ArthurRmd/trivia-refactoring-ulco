# TDD Kata

## Golden master refactoring

!! Penser à jouer les tests après chaque refactoring !!

- Refacto des magic string/number (remplacer par des constantes)
- Renommer les méthodes/variables peu claires
- Trouver les duplications et les supprimer
- Séparer en avec l'orienté objet plus correct (ajout de classes notamment, pour la séparation de responsabilités)
- Bien penser au S de SOLID -> Single Responsibility Principle
- Refactoriser le code complexe (écriture de sous fonction ou réécriture de ce code)

## How to run project

### Build the docker

```bash
make build
```

### Run the docker

```bash
make up
```

### Go in the docker

```bash
make ex
```

#### Composer
```bash
composer install
```

#### Run tests

```
vendor/bin/phpunit
```

## Configure xdebug

1. CTRL + ALT + S => PHP => CLI Interpreters => Click 3 dots (...)
2. Click + => From Docker => Tick Docker =>
    1. Server : `Docker`
    2. Image name : `ulco:latest`
    3. Interpreter path : `php`
3. PHP => Docker container => Click folder => Volume bindings => Changer container path to /app
4. PHP => Debug => Debug port => 9001
5. PHP => Servers => + =>
    1. Name: `docker.host.ip`
    2. Host: `docker.host.ip`
    3. Use path mappings : Absolute path on server `/app`