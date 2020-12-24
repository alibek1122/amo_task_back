# Установка
Выполнить ```docker-compose up -d```

Зайти в контейнер:
```docker-compose exec app sh```

Скопировать .env.example в .env
```cp .env.example .env```

Настроить .env файл

# Важно
Для работы необходимо провести миграцию бд

Заходим в контейнер:
```docker-compose exec app sh```

Запускаем миграцию
```php artisan migrate```