# ADR 002: Modelo de token público para placas

## Estado

Aceptado

## Contexto

Las placas físicas deben tener URL estable e impredecible. No debe usarse el ID de mascota.

## Decisión

- URL: `https://dominio.mx/t/{public_token}`
- `public_token`: string aleatorio 32+ caracteres, columna única en `tags`
- Separado de: ID interno, código de activación (hasheado)

## Consecuencias

- Reemplazo de placa sin cambiar perfil de mascota
- Anti-enumeración vs IDs secuenciales
