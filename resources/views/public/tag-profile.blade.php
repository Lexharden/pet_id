<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>{{ $profile['name'] ?? config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-100 antialiased dark:bg-zinc-950">
        <main class="mx-auto min-h-screen max-w-lg px-4 py-6">
            @include('partials.public-pet-profile', ['profile' => $profile])

            @if (! empty($profile['is_lost']))
                <div class="mt-6">
                    <livewire:pages::public.found-pet-form :pet-id="$tag->activeAssignment->pet_id" :scan-event-id="$scan?->id" />
                </div>
            @endif

            <footer class="mt-8 text-center text-xs text-zinc-500">
                {{ config('app.name') }}
            </footer>
        </main>
    </body>
</html>
