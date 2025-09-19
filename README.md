# Task Management API — документация

Проект: REST API для управления задачами в команде разработчиков на Laravel.

## Технологический стек
- PHP 8.3+
- Laravel 12.x
- MySQL (локально допустимо SQLite для быстрых тестов)
- Очереди (sync/redis) для фоновых заданий

## Быстрый старт
1) Установите зависимости:
```
composer install
npm install
```

2) Скопируйте переменные окружения и сгенерируйте ключ приложения:
```
cp .env.example .env
php artisan key:generate
```

3) Настройте подключение к БД в `.env` (MySQL или SQLite). Для SQLite можно указать:
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

4) Накатите миграции и выполните сиды (опционально):
```
php artisan migrate --seed
```

5) Запустите сервер разработки:
```
php artisan serve --port=8001
```

6) (Опционально) Запустите обработчик очереди:
```
php artisan queue:work
```

## Основные возможности
- Создание задач с автоназначением менеджера при отсутствии `user_id`
- Изменение статуса задачи, авто‑комментарий при `completed`
- Добавление комментариев (запрещено для отменённых задач)
- Уведомления менеджеров через Job (создание записей + лог)
- Команда проверки просроченных задач `tasks:check-overdue` (поддерживает `--dry-run`)

## API (кратко)
- GET `/api/tasks` — список задач (фильтры: `status`, `priority`, `user_id`)
- POST `/api/tasks` — создание задачи: `title` (обяз.), `description`, `user_id`, `priority` (`low|medium|high|normal` → `normal` трактуется как `medium`)
- PUT `/api/tasks/{id}/status` — изменение статуса: `status`, `user_id`
- POST `/api/tasks/{id}/comments` — комментарий к задаче: `comment`, `user_id`
- GET `/api/tasks/{id}` — задача с пользователем и комментариями

HTTP‑коды: 200/201/404/422/500 (по стандарту Laravel).

## Очереди и уведомления
Job: `SendTaskStatusNotification` принимает `taskId` и `notificationType` (`status_changed|task_assigned|overdue`), создаёт записи в `task_notifications` для всех менеджеров и пишет лог.

## Команда просроченных задач
```
php artisan tasks:check-overdue {--dry-run}
```
- Ищет задачи `in_progress`, старше 7 дней
- Добавляет комментарий: "Task is overdue! Created <дата>"
- Диспатчит job с типом `overdue`
- В режиме `--dry-run` только выводит количество

## Тестирование
Запуск тестов (если директория тестов не исключена локально):
```
php artisan test
```

## Примечания по разработке
- Используются паттерны Service/Repository/DTO
- Валидация через Form Request
- Рекомендуется запускать очередь отдельно для асинхронных уведомлений

## Полезные команды
```
php artisan migrate:fresh --seed
php artisan route:list
php artisan queue:work
```


