# Despliegue

## Desarrollo local

### PostgreSQL (recomendado)

```bash
# Crear base de datos
createdb pet_id
createuser pet_id -P

cp .env.example .env
# Configurar DB_* en .env

php artisan key:generate
php artisan migrate
composer run setup  # o npm install && npm run build
php artisan serve
```

### SQLite (solo desarrollo rápido)

En `.env`: `DB_CONNECTION=sqlite` y comentar variables PostgreSQL.

## Tests

PHPUnit usa SQLite en memoria (`phpunit.xml`); no requiere PostgreSQL para CI.

## Producción (Fase 7)

Docker Compose: web, app, worker, database. Nginx + TLS en host VPS.
