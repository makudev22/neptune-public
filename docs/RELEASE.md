# Публикация Neptune

## Перед первым push

```powershell
git init -b main
git add .
git status
git commit -m "Initial Neptune release"
git remote add origin https://github.com/makudev22/Neptune-public.git
git push -u origin main
```

Перед коммитом убедитесь, что в `git status` нет `server-data`, `bin`, `vendor`, `build`, миров, плагинов или логов.

## Релиз

```powershell
./build.ps1
```

Скрипт собирает `build/Neptune.phar` и проверяет протокол 2193. Создайте GitHub Release с тегом, например `v3.0.0`, и прикрепите `build/Neptune.phar` к релизу. PHAR не добавляется в Git-коммиты.

## Проверка после публикации

1. Скачайте исходники в отдельную папку.
2. Выполните `composer install --no-dev --prefer-dist --optimize-autoloader`.
3. Выполните `./build.ps1`.
4. Запустите `./start.ps1`.
5. Подключитесь клиентом 1.26.51 и проверьте вход, инвентарь, установку блоков и крафт.
