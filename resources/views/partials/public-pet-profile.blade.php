@php
    $profile = $profile ?? [];
@endphp

<article class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
    @if (! empty($profile['is_lost']))
        <div class="bg-amber-500 px-4 py-3 text-center text-sm font-semibold text-white">
            {{ __('Esta mascota está perdida') }}
        </div>
    @endif

    @if (! empty($profile['photo_url']))
        <img src="{{ $profile['photo_url'] }}" alt="" class="aspect-square w-full object-cover" />
    @endif

    <div class="flex flex-col gap-4 p-5">
        @if (! empty($profile['name']))
            <h2 class="text-2xl font-semibold">{{ $profile['name'] }}</h2>
        @endif

        @if (! empty($profile['species']))
            <p class="text-zinc-600 dark:text-zinc-400">{{ $profile['species'] }}@if(!empty($profile['breed'])) · {{ $profile['breed'] }}@endif</p>
        @endif

        @if (! empty($profile['sex']))
            <p class="text-sm text-zinc-600">{{ $profile['sex'] }}</p>
        @endif

        @if (! empty($profile['color']))
            <p class="text-sm"><span class="font-medium">{{ __('Color') }}:</span> {{ $profile['color'] }}</p>
        @endif

        @if (! empty($profile['markings']))
            <p class="text-sm">{{ $profile['markings'] }}</p>
        @endif

        @if (! empty($profile['description']))
            <p class="text-sm">{{ $profile['description'] }}</p>
        @endif

        @if (! empty($profile['approach_instructions']))
            <div class="rounded-lg bg-sky-50 p-3 text-sm dark:bg-sky-950">
                {{ $profile['approach_instructions'] }}
            </div>
        @endif

        @if (! empty($profile['is_lost']) && ! empty($profile['lost_reward']))
            <div class="rounded-lg bg-amber-50 p-3 text-sm dark:bg-amber-950">
                <span class="font-medium">{{ __('Recompensa') }}:</span> {{ $profile['lost_reward'] }}
            </div>
        @endif

        @if (! empty($profile['contacts']) && count($profile['contacts']))
            <div class="flex flex-col gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                @foreach ($profile['contacts'] as $contact)
                    <div class="flex flex-col gap-2">
                        <p class="font-medium">{{ $contact['name'] }}</p>
                        <div class="flex flex-wrap gap-2">
                            @if (! empty($contact['phone']))
                                <a href="tel:{{ $contact['phone'] }}" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-medium text-white">
                                    {{ __('Llamar') }}
                                </a>
                            @endif
                            @if (! empty($contact['whatsapp']))
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $contact['whatsapp']) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-xl bg-green-500 px-4 py-3 text-sm font-medium text-white">
                                    {{ __('WhatsApp') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if (! empty($profile['emergency']))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-950">
                <p class="mb-2 font-semibold text-red-800 dark:text-red-200">{{ __('Información de emergencia') }}</p>
                @foreach ($profile['emergency'] as $label => $value)
                    @if ($value)
                        <p class="text-sm text-red-900 dark:text-red-100">{{ $value }}</p>
                    @endif
                @endforeach
            </div>
        @endif

        @if (empty($profile['name']) && empty($profile['description']) && empty($profile['contacts']))
            <p class="text-center text-sm text-zinc-500">{{ __('No hay información pública configurada.') }}</p>
        @endif
    </div>
</article>
