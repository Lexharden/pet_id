# ADR 001: Monolito modular Laravel

## Estado

Aceptado

## Contexto

Pet ID necesita velocidad de desarrollo, un solo despliegue y equipo pequeño.

## Decisión

Monolito Laravel 13 con módulos por dominio (Actions, Policies, Livewire) sin microservicios ni CQRS.

## Consecuencias

- Despliegue simple
- Refactor a servicios separados posible en fronteras claras (pagos, webhooks)
