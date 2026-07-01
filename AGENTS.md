# Pet ID — Guía para agentes

## Stack

- Laravel 13, PHP 8.3+
- Livewire 4 (componentes de archivo único en `resources/views/pages/`)
- Flux UI + Tailwind CSS 4
- Fortify (auth, 2FA, passkeys)
- PostgreSQL en producción; SQLite en memoria para tests
- Pest 4, Pint, Larastan nivel 7

## Convenciones

- Monolito modular: lógica de negocio en `app/Actions/{Domain}/`
- Autorización con Policies
- Enums string-backed en `app/Enums/`
- Rutas públicas de placas: `/t/{public_token}` (nunca ID de mascota)
- Locale: español (México); fechas en UTC, UI en `America/Mexico_City`
- No instalar paquetes sin justificar
- No usar Repository pattern sobre Eloquent

## Comandos

```bash
composer test      # Pint + PHPStan + Pest
composer lint      # Formatear
bun run build      # Assets
php artisan migrate
```

## Fases

0. Fundamentos (locale, docs, roles)
1. Núcleo mascotas
2. Placas y activación
3. Perfil público y escaneos
4. Mascota perdida/encontrada
5+. Comercio, admin, producción

## Documentación

Ver `docs/` para arquitectura, modelo de datos, seguridad y roadmap.
