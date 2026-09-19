# Neptune

Neptune — открытое ядро Minecraft: Bedrock Edition. Проект основан на Submarine и развивается независимо под лицензией MIT.

## Поддерживаемые версии

- 1.1.x
- 1.16–1.26.30
- 1.26.50 и 1.26.51 (протокол 2193)

## Быстрый запуск на Windows

1. Установите PHP-бинарники PocketMine-MP для PHP 8.3 и укажите путь к `php.exe` в переменной `NEPTUNE_PHP`, либо поместите бинарники в `bin/php/php.exe`.
2. В корне проекта выполните `./build.ps1`.
3. Запустите сервер командой `./start.ps1`.
4. Сервер создаст каталог `server-data`. Конфигурация Neptune хранится в `server-data/neptune.yml`.

Для локальной сети в клиенте Minecraft используйте IPv4-адрес компьютера и порт `19132`.

## Сборка из исходников

Требуются PHP 8.3 с расширением `pmmpthread` и Composer.

```powershell
composer install --no-dev --prefer-dist --optimize-autoloader
./build.ps1
./bin/php/php.exe ./tools/verify-protocols.php
```

Готовый файл находится в `build/Neptune.phar`. Не коммитьте его в репозиторий: прикладывайте этот файл к GitHub Release.

## Публикация на GitHub

В репозиторий входят исходники, `composer.json`, `composer.lock`, документация и скрипты. Локальные миры, плагины, логи, PHP-бинарники, `vendor` и PHAR исключены через `.gitignore`.

Перед публикацией выполните:

```powershell
./build.ps1
./bin/php/php.exe ./tools/verify-protocols.php
```

Затем создайте GitHub Release и прикрепите `build/Neptune.phar` как отдельный релизный файл.

## Лицензия

Neptune распространяется по лицензии MIT. Подробности — в [LICENSE](LICENSE).
