<?php

use App\Actions\Tags\ActivateTag;
use App\Exceptions\TagActivationException;
use App\Models\Pet;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Activar placa')] class extends Component {
    use AuthorizesRequests;

    public string $activation_code = '';
    public ?int $pet_id = null;

    #[Computed]
    public function pets()
    {
        return auth()->user()->pets()->orderBy('name')->get();
    }

    public function activate(ActivateTag $activateTag): void
    {
        $this->validate([
            'activation_code' => ['required', 'string', 'min:6', 'max:32'],
            'pet_id' => ['required', 'exists:pets,id'],
        ]);

        $pet = Pet::query()->findOrFail($this->pet_id);
        $this->authorize('update', $pet);

        try {
            $tag = $activateTag->execute(auth()->user(), $this->activation_code, $pet);
        } catch (TagActivationException $e) {
            $this->addError('activation_code', $e->getMessage());

            return;
        }

        Flux::toast(variant: 'success', text: __('Placa activada correctamente'));

        $this->reset(['activation_code']);
        $this->redirect(route('pets.tags', $pet), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-lg">
    <flux:heading size="xl" class="mb-2">{{ __('Activar placa') }}</flux:heading>
    <flux:text class="mb-6">{{ __('Ingresa el código que viene en el empaque de tu placa y selecciona la mascota.') }}</flux:text>

    <form wire:submit="activate" class="flex flex-col gap-6">
        <flux:field>
            <flux:label>{{ __('Código de activación') }}</flux:label>
            <flux:input wire:model="activation_code" class="uppercase" autocomplete="off" required />
            <flux:error name="activation_code" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Selecciona una mascota') }}</flux:label>
            <flux:select wire:model="pet_id" placeholder="{{ __('Selecciona una mascota') }}">
                @foreach ($this->pets as $pet)
                    <flux:select.option value="{{ $pet->id }}">{{ $pet->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="pet_id" />
        </flux:field>

        @if ($this->pets->isEmpty())
            <flux:callout variant="warning">
                {{ __('No tienes mascotas registradas') }}.
                <flux:link :href="route('pets.create')" wire:navigate>{{ __('Agregar mascota') }}</flux:link>
            </flux:callout>
        @else
            <flux:button type="submit" variant="primary">{{ __('Activar placa') }}</flux:button>
        @endif
    </form>
</div>
