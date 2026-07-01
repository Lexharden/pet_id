<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} — {{ __('Identidad digital para tu mascota') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <header class="border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6">
                <div class="flex items-center gap-2">
                    <x-app-logo-icon class="size-8 fill-current text-zinc-800 dark:text-white" />
                    <span class="text-lg font-semibold">{{ config('app.name') }}</span>
                </div>
                @if (Route::has('login'))
                    <nav class="flex items-center gap-3 text-sm">
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-lg px-4 py-2 font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800" wire:navigate>
                                {{ __('Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-lg px-4 py-2 font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">
                                {{ __('Ya tengo cuenta') }}
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-lg bg-zinc-900 px-4 py-2 font-medium text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                                    {{ __('Crear cuenta gratis') }}
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:py-24">
            <div class="mx-auto max-w-2xl text-center">
                <p class="mb-4 text-sm font-medium uppercase tracking-wide text-sky-600">{{ config('app.name') }}</p>
                <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl">
                    {{ __('Protege a tu mascota con una placa inteligente') }}
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('Escanea el QR o acerca tu teléfono al NFC para ver el perfil y contactar al dueño. Sin app, sin batería, sin GPS.') }}
                </p>
                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    @guest
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-zinc-900 px-6 py-3 text-base font-medium text-white hover:bg-zinc-800 sm:w-auto dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                                {{ __('Crear cuenta gratis') }}
                            </a>
                        @endif
                        <a href="#como-funciona" class="inline-flex w-full items-center justify-center rounded-xl border border-zinc-300 px-6 py-3 text-base font-medium text-zinc-800 hover:bg-zinc-100 sm:w-auto dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-900">
                            {{ __('Cómo funciona') }}
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-6 py-3 text-base font-medium text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900">
                            {{ __('Dashboard') }}
                        </a>
                    @endguest
                </div>
            </div>

            <section id="como-funciona" class="mt-24 grid gap-8 sm:grid-cols-3">
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-full bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-300">1</div>
                    <h2 class="font-semibold">{{ __('Activa tu placa') }}</h2>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Registra tu mascota y vincula la placa con el código del empaque.</p>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-full bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-300">2</div>
                    <h2 class="font-semibold">{{ __('Configura qué es público') }}</h2>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Tú decides qué contactos e información de emergencia pueden ver los demás.</p>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-3 flex size-10 items-center justify-center rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300">3</div>
                    <h2 class="font-semibold">{{ __('Escaneo y contacto') }}</h2>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Quien encuentre a tu mascota puede llamar, escribir por WhatsApp o reportar el hallazgo.</p>
                </div>
            </section>
        </main>

        <footer class="border-t border-zinc-200 py-8 text-center text-sm text-zinc-500 dark:border-zinc-800 dark:text-zinc-500">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </footer>
    </body>
</html>
