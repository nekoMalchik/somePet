# LuckyController - пишет в бд
```
 SELECT * FROM pg_log ORDER BY id DESC LIMIT 10;
 id | level | message |     created_at      
----+-------+---------+---------------------
  8 | INFO  | 83      | 2026-06-03 18:41:15
  7 | INFO  | 5       | 2026-06-03 18:41:15
  6 | INFO  | 71      | 2026-06-03 18:41:14
```
 channels: [pg] в monolog.yaml заставляет Symfony создать отдельный сервис monolog.logger.pg (объект Monolog\Logger с именем канала pg).
 В контроллере по ИМЕНИ $pgLogger - вызывается.

# HelloWorldController Дефолтный логгер - перевели в json + прокинули userID
config: formatter: monolog.formatter.json

```
{"message":"User has joined","context":{},"level":300,"level_name":"WARNING","channel":"app","datetime":"2026-06-03T18:51:45.005283+00:00","extra":{"token":"d51e405a-41901853","userEmail":"as@ya.ru"}}
```

# Command
тупа вывод три секции по документации
src/Command/TestCommand.php

# CustomDoctrineType
Реализуем методы асбтрактного типа Type
use Doctrine\DBAL\Types\Type;
Реализацию типа можно "подглядеть" как раз у соседей (тех, кто реализует Type) - там всё понятно
