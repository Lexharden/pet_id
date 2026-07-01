<?php

use App\Actions\Pets\CreatePet;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use Flux\Flux;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Agregar mascota')] class extends Component {
    public string $name = '';
    public string $species = 'dog';
    public string $breed = '';
    public string $sex = '';
    public string $birth_date_approx = '';
    public string $color = '';
    public string $markings = '';
    public string $description = '';
    public string $approach_instructions = '';

    public function save(CreatePet $createPet): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'species' => ['required', 'in:dog,cat,other'],
            'breed' => ['nullable', 'string', 'max:100'],
            'sex' => ['nullable', 'in:male,female,unknown'],
            'birth_date_approx' => ['nullable', 'date'],
            'color' => ['nullable', 'string', 'max:100'],
            'markings' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
            'approach_instructions' => ['nullable', 'string', 'max:1000'],
        ]);

        $pet = $createPet->execute(auth()->user(), [
            ...$validated,
            'species' => PetSpecies::from($validated['species']),
            'sex' => $validated['sex'] ? PetSex::from($validated['sex']) : null,
        ]);

        Flux::toast(variant: 'success', text: __('Mascota creada correctamente'));

        $this->redirect(route('pets.edit', $pet), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl">
        <flux:heading size="xl" class="mb-6">{{ __('Agregar mascota') }}</flux:heading>

        <form wire:submit="save" class="flex flex-col gap-6">
            <flux:field>
                <flux:label>{{ __('Nombre') }}</flux:label>
                <flux:input wire:model="name" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Especie') }}</flux:label>
                <flux:select wire:model="species">
                    @foreach (PetSpecies::cases() as $option)
                        <flux:select.option value="{{ $option->value }}">{{ $option->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="species" />
            </flux:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>{{ __('Raza') }}</flux:label>
                    <flux:input wire:model="breed" />
                </flux:field>
                <flux:field>
                    <flux:label>{{ __('Sexo') }}</flux:label>
                    <flux:select wire:model="sex">
                        <flux:select.option value="">{{ __('Desconocido') }}</flux:select.option>
                        @foreach (PetSex::cases() as $option)
                            <flux:select.option value="{{ $option->value }}">{{ $option->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>
            </div>

            <flux:field>
                <flux:label>{{ __('Fecha de nacimiento aproximada') }}</flux:label>
                <flux:input type="date" wire:model="birth_date_approx" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Color') }}</flux:label>
                <flux:input wire:model="color" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Señas particulares') }}</flux:label>
                <flux:textarea wire:model="markings" rows="2" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Descripción') }}</flux:label>
                <flux:textarea wire:model="description" rows="3" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Instrucciones para acercarse') }}</flux:label>
                <flux:textarea wire:model="approach_instructions" rows="2" />
            </flux:field>

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">{{ __('Guardar') }}</flux:button>
                <flux:button :href="route('pets.index')" variant="ghost" wire:navigate>{{ __('Cancelar') }}</flux:button>
            </div>
        </form>
</div>
