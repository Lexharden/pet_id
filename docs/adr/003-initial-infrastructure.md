# ADR 003: Infraestructura inicial

## Estado

Aceptado

## Contexto

VPS propio con Nginx; sin Kubernetes ni Redis en MVP.

## Decisión

- Desarrollo: PostgreSQL local (SQLite opcional)
- Producción: Docker Compose (web, PHP-FPM, worker, PostgreSQL)
- Cola/caché/sesión: drivers `database`
- Almacenamiento dev: local; prod: S3 antes de lanzamiento

## Consecuencias

- Redis/Horizon solo cuando métricas lo justifiquen
- Worker supervisado en contenedor
