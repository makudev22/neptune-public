# Neptune

Neptune — открытое ядро Minecraft: Bedrock Edition. .

## Поддерживаемые версии

- 1.1.x
- 1.16–1.26.30
- 1.26.50 и 1.26.51 

## Запуск Neptune на Linux

### 1. Скачайте PHP-бинарники

Скачайте архив для Linux x64:

[PHP 8.3 Linux x86_64](https://github.com/pmmp/PHP-Binaries/releases/download/pm5-php-8.3-latest/PHP-8.3-Linux-x86_64-PM5.tar.gz)


Распакуйте архив в корень нужной папки:

```bash
tar -xzf PHP-8.3-Linux-x86_64-PM5.tar.gz
mv bin/php8 bin/php
chmod +x bin/php/php
```

### 2. Скачайте Neptune из страницы Releases

### 3. Запустите сервер

```bash
chmod +x start.sh
./start.sh
```

Конфигурация находится здесь:

```text
server-data/neptune.yml
```

Чтобы подключиться с Minecraft Bedrock, используйте IP сервера и порт `19132`.

## Лицензия

Neptune распространяется по лицензии MIT. Подробности — в [LICENSE](LICENSE).
