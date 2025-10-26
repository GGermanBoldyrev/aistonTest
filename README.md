## Тестовое

---

## Запуск приложения

### 1. Клонируем репозиторий
``` bash
    git clone <repository-url>
    cd aistonTest
```
### 2. Копируем файл окружения
``` bash
    cp env.example .env
```
## 3. Настраиваем .env файл
#### Базовые настройки уже корректно настроены в env.example
### Для базы

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

MYSQL_EXTRA_OPTIONS=
```

## 4. Запускаем контейнеры
``` bash
    ./vendor/bin/sail up -d
```

## 5. Генерируем ключ приложения
``` bash
    ./vendor/bin/sail artisan key:generate
```

## 6. Запускаем миграции и сиды
``` bash
    ./vendor/bin/sail artisan migrate --seed
```

## Команды Make
### Docker / Sail

**Запускает контейнеры Sail в фоне (sail up -d)**
```bash 
    make up
```

**Останавливает и удаляет контейнеры (sail down)**
```bash 
    make down
```
### Artisan (Laravel)

**Запускает любую artisan-команду вручную, например:
make artisan CMD="tinker"**
```bash 
    make artisan CMD="..."
```
**Применяет миграции**
```bash 
    make migrate
```
**Запускает сиды (db:seed)**
```bash 
    make seed
```
**Полностью пересоздает БД (migrate:fresh)**
```bash 
    make fresh
```

**Обновляет БД и сразу выполняет сиды (migrate:fresh --seed)
make rollback	Откатывает последнюю миграцию
make reset	Сбрасывает все миграции**
```bash 
    make mfs
```
**Откатывает последнюю миграцию**
```bash 
    make rollback
```
**Сбрасывает все миграции**
```bash 
    make reset
```

### Как создается тикет: ###
#### 1) Фронт стучится на ручку получает attachment ####
#### 2) Прокидывает attachment в запрос ####

#### Пример запроса: ####
```
{
  "data": {
    "type": "tickets",
    "attributes": {
      "number": "Настроить CI/CD для проекта",
      "description": "Использовать GitHub Actions для автоматической сборки и тестирования",
      "topic": "new",
      "user_id": "12",
      "ticketAttachments": [
        "325efcaf-28b4-4ab6-8acc-071a4ced9973"
      ]
    },
    "relationships": {
      "pharmacy": {
        "data": {
          "type": "pharmacies",
          "id": "5"
        }
      },
      "priority": {
        "data": {
          "type": "priorities",
          "id": "2"
        }
      },
      "category": {
        "data": {
          "type": "categories",
          "id": "5"
        }
      },
      "status": {
        "data": {
          "type": "statuses",
          "id": "1"
        }
      },
      "technician": {
        "data": {
          "type": "technicians",
          "id": "10"
        }
      }
    }
  }
}
```
