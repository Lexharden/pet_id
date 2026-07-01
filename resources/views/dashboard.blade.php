<x-layouts::app :title="__('Dashboard')">
    <div class="flex flex-col gap-6">
        <flux:heading size="xl">{{ __('Dashboard') }}</flux:heading>

        @php $petsCount = auth()->user()->pets()->count(); @endphp

        @if ($petsCount === 0)
            <flux:card class="flex flex-col items-center gap-4 py-12 text-center">
                <flux:icon.heart class="size-12 text-zinc-400" />
                <flux:heading>{{ __('No tienes mascotas registradas') }}</flux:heading>
                <flux:text>{{ __('Comienza agregando el perfil de tu primera mascota.') }}</flux:text>
                <flux:button :href="route('pets.create')" variant="primary" wire:navigate>
                    {{ __('Agregar mascota') }}
                </flux:button>
            </flux:card>
        @else
            <flux:card class="flex items-center justify-between">
                <div>
                    <flux:heading size="lg">{{ __('Mis mascotas') }}</flux:heading>
                    <flux:text>{{ trans_choice(':count mascota registrada|:count mascotas registradas', $petsCount, ['count' => $petsCount]) }}</flux:text>
                </div>
                <flux:button :href="route('pets.index')" wire:navigate>{{ __('Ver todas') }}</flux:button>
            </flux:card>
        @endif
    </div>
</x-layouts::app>
