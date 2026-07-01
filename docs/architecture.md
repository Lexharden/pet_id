# Arquitectura — Pet ID

## Estilo

Monolito modular Laravel. Dominios separados por carpetas (`Actions`, `Enums`, `Livewire`, `Policies`) sin DDD ceremonial.

## Módulos

| Módulo | Responsabilidad |
|--------|-----------------|
| Identity | Usuarios, roles, preferencias |
| Pets | Mascotas, fotos, emergencia |
| Contacts | Contactos con visibilidad pública |
| Tags | Placas, tokens, activación |
| PublicProfiles | Vista pública `/t/{token}` |
| Scans | Registro de escaneos |
| LostAndFound | Modo perdida, reportes |
| Notifications | Email con throttling |

## Flujo de datos

```
Placa física → /t/{public_token} → ResolveTag → ScanEvent + Vista pública
Owner → Livewire → Action → Eloquent → Event → Job/Notification
```

## Autorización

- `PetPolicy`: `user_id` del propietario
- Rutas staff: middleware `role:operator,admin` (futuro)
- Perfiles públicos: sin auth; solo datos con toggle explícito

## Almacenamiento

- `pets-public`: fotos públicas
- `pets-private`: fotos privadas / originales
- Producción: migrar a S3 (Fase 7)
