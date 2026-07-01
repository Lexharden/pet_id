<?php

use App\Actions\LostAndFound\SubmitFoundPetReport;
use App\Models\Pet;
use Flux\Flux;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public int $pet_id;

    #[Locked]
    public ?int $scan_event_id = null;

    public string $finder_name = '';
    public string $finder_phone = '';
    public string $message = '';

    public bool $submitted = false;

    public function mount(int $pet_id, ?int $scan_event_id = null): void
    {
        $this->pet_id = $pet_id;
        $this->scan_event_id = $scan_event_id;
    }

    public function submit(SubmitFoundPetReport $submitFoundPetReport): void
    {
        $validated = $this->validate([
            'finder_name' => ['nullable', 'string', 'max:100'],
            'finder_phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $pet = Pet::query()->findOrFail($this->pet_id);

        if (! $pet->isLost()) {
            return;
        }

        try {
            $submitFoundPetReport->execute(
                $pet,
                $validated,
                $this->scan_event_id,
                request()->ip(),
            );
        } catch (\RuntimeException $e) {
            $this->addError('message', $e->getMessage());

            return;
        }

        $this->submitted = true;
        Flux::toast(variant: 'success', text: __('Tu mensaje fue enviado al propietario'));
    }
}; ?>

<div>
    @if ($submitted)
        <flux:callout variant="success">{{ __('Tu mensaje fue enviado al propietario') }}</flux:callout>
    @else
        <flux:card>
            <flux:heading size="lg" class="mb-4">{{ __('Encontré esta mascota') }}</flux:heading>
            <form wire:submit="submit" class="flex flex-col gap-4">
                <flux:field>
                    <flux:label>{{ __('Tu nombre') }}</flux:label>
                    <flux:input wire:model="finder_name" />
                </flux:field>
                <flux:field>
                    <flux:label>{{ __('Tu teléfono') }}</flux:label>
                    <flux:input wire:model="finder_phone" type="tel" />
                </flux:field>
                <flux:field>
                    <flux:label>{{ __('Mensaje') }}</flux:label>
                    <flux:textarea wire:model="message" rows="3" required />
                    <flux:error name="message" />
                </flux:field>
                <flux:button type="submit" variant="primary" class="w-full">{{ __('Reportar hallazgo') }}</flux:button>
            </form>
        </flux:card>
    @endif
</div>
