<?php

use App\Actions\LostAndFound\UpdatePetStatus;
use App\Models\Pet;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Estado de mascota')] class extends Component {
    use AuthorizesRequests;

    #[Locked]
    public Pet $pet;

    public string $lost_reward = '';
    public string $lost_instructions = '';

    public function mount(Pet $pet): void
    {
        $this->authorize('update', $pet);
        $this->pet = $pet;
        $this->lost_reward = $pet->lost_reward ?? '';
        $this->lost_instructions = $pet->lost_instructions ?? '';
    }

    public function markLost(UpdatePetStatus $updatePetStatus): void
    {
        $this->authorize('update', $this->pet);

        $this->validate([
            'lost_reward' => ['nullable', 'string', 'max:500'],
            'lost_instructions' => ['nullable', 'string', 'max:1000'],
        ]);

        $updatePetStatus->markLost($this->pet, $this->lost_reward ?: null, $this->lost_instructions ?: null);
        $this->pet->refresh();

        Flux::toast(variant: 'success', text: __('Mascota marcada como perdida'));
    }

    public function markHome(UpdatePetStatus $updatePetStatus): void
    {
        $this->authorize('update', $this->pet);

        $updatePetStatus->markHome($this->pet);
        $this->pet->refresh();
        $this->lost_reward = '';
        $this->lost_instructions = '';

        Flux::toast(variant: 'success', text: __('Mascota marcada como en casa'));
    }
}; ?>

<div class="mx-auto max-w-lg">
    <flux:heading size="xl" class="mb-6">{{ __('Estado') }}: {{ $pet->name }}</flux:heading>

    @if ($pet->isLost())
        <flux:callout variant="warning" class="mb-6">{{ __('Esta mascota está perdida') }}</flux:callout>
        <flux:button wire:click="markHome" variant="primary">{{ __('Marcar como en casa') }}</flux:button>
    @else
        <form wire:submit="markLost" class="flex flex-col gap-4">
            <flux:field>
                <flux:label>{{ __('Recompensa') }} ({{ __('opcional') }})</flux:label>
                <flux:textarea wire:model="lost_reward" rows="2" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Instrucciones') }}</flux:label>
                <flux:textarea wire:model="lost_instructions" rows="2" />
            </flux:field>
            <flux:button type="submit" variant="danger">{{ __('Marcar como perdida') }}</flux:button>
        </form>
    @endif
</div>
