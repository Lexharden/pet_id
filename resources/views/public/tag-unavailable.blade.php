<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>{{ config('app.name') }}</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="flex min-h-screen items-center justify-center bg-zinc-100 px-4 dark:bg-zinc-950">
        <div class="max-w-sm text-center">
            <h1 class="text-lg font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Perfil no disponible') }}</h1>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Esta placa no está activa o no tiene una mascota asignada.') }}
            </p>
        </div>
    </body>
</html>
