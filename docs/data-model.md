# Modelo de datos

Ver plan maestro para tablas por fase. Resumen:

## Fase 1
`pets`, `pet_photos`, `pet_contacts`, `pet_emergency_summaries`, `pet_public_settings`, `user_notification_preferences`

## Fase 2
`tag_batches`, `tags`, `tag_activation_codes`, `tag_assignments`

## Fase 3
`scan_events`

## Fase 4
`found_pet_reports`, `owner_alerts`

## Tokens

- `tags.public_token`: string aleatorio 32+ chars, unique index
- `tag_activation_codes.code_hash`: bcrypt del código plaintext

## Sensibles

Teléfonos, códigos activación, IPs (retención limitada).
