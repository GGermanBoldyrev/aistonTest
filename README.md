## Тестовое

---

## Запуск приложения


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
