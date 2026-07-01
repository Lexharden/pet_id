<?php

use App\Actions\Pets\CreatePet;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use App\Models\Pet;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Mis mascotas')] class extends Component {
    use AuthorizesRequests;

    #[Computed]
    public function pets()
    {
        return auth()->user()->pets()->latest()->get();
    }
}; ?>

<div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Mis mascotas') }}</flux:heading>
            <flux:button :href="route('pets.create')" icon="plus" wire:navigate>
                {{ __('Agregar mascota') }}
            </flux:button>
        </div>

        @if ($this->pets->isEmpty())
            <flux:card class="flex flex-col items-center gap-4 py-12 text-center">
                <flux:icon.heart class="size-12 text-zinc-400" />
                <flux:heading>{{ __('No tienes mascotas registradas') }}</flux:heading>
                <flux:text>{{ __('Comienza agregando el perfil de tu primera mascota.') }}</flux:text>
                <flux:button :href="route('pets.create')" variant="primary" wire:navigate>
                    {{ __('Agregar mascota') }}
                </flux:button>
            </flux:card>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->pets as $pet)
                    <flux:card class="flex flex-col gap-3" wire:key="pet-{{ $pet->id }}">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <flux:heading size="lg">{{ $pet->name }}</flux:heading>
                                <flux:text>{{ $pet->species->label() }}</flux:text>
                            </div>
                            @if ($pet->isLost())
                                <flux:badge color="amber">{{ __('Perdida') }}</flux:badge>
                            @else
                                <flux:badge color="green">{{ __('En casa') }}</flux:badge>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <flux:button size="sm" :href="route('pets.edit', $pet)" wire:navigate>
                                {{ __('Editar') }}
                            </flux:button>
                            <flux:button size="sm" variant="ghost" :href="route('pets.privacy', $pet)" wire:navigate>
                                {{ __('Privacidad') }}
                            </flux:button>
                            <flux:button size="sm" variant="ghost" :href="route('pets.tags', $pet)" wire:navigate>
                                {{ __('Placas') }}
                            </flux:button>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        @endif
</div>
