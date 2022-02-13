# tictactoe
sample project

## Docker
- up.cmd `docker-compose up -d`
- down.cmd `docker-compose down`

## Composer (Windows)
- composer.cmd `docker run --rm --interactive --tty --volume %cd%:/app composer %*`

Init: `$ composer install`

Use: `$ composer [options] <package>`

## PHPUnit
- phpunit.cmd `docker-compose exec php php phpunit.phar %*`

Use: `$ phpunit`