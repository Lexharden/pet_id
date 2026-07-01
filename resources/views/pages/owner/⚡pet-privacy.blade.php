<?php

use App\Models\Pet;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Privacidad')] class extends Component {
    use AuthorizesRequests;

    #[Locked]
    public Pet $pet;

    public bool $show_name = true;
    public bool $show_photo = false;
    public bool $show_species = true;
    public bool $show_breed = false;
    public bool $show_sex = false;
    public bool $show_color = false;
    public bool $show_markings = false;
    public bool $show_description = false;
    public bool $show_approach_instructions = false;
    public bool $show_contacts = false;
    public bool $show_emergency = false;

    public string $allergies = '';
    public string $urgent_medication = '';
    public string $condition = '';
    public string $immediate_instructions = '';
    public string $vet_name = '';
    public string $vet_phone = '';
    public bool $show_allergies = false;
    public bool $show_urgent_medication = false;
    public bool $show_condition = false;
    public bool $show_immediate_instructions = false;
    public bool $show_vet = false;

    public function mount(Pet $pet): void
    {
        $this->authorize('update', $pet);
        $this->pet = $pet->load(['publicSettings', 'emergencySummary']);

        $settings = $this->pet->publicSettings;
        $emergency = $this->pet->emergencySummary;

        if ($settings) {
            $this->fill($settings->only([
                'show_name', 'show_photo', 'show_species', 'show_breed', 'show_sex',
                'show_color', 'show_markings', 'show_description', 'show_approach_instructions',
                'show_contacts', 'show_emergency',
            ]));
        }

        if ($emergency) {
            $this->allergies = $emergency->allergies ?? '';
            $this->urgent_medication = $emergency->urgent_medication ?? '';
            $this->condition = $emergency->condition ?? '';
            $this->immediate_instructions = $emergency->immediate_instructions ?? '';
            $this->vet_name = $emergency->vet_name ?? '';
            $this->vet_phone = $emergency->vet_phone ?? '';
            $this->show_allergies = $emergency->show_allergies;
            $this->show_urgent_medication = $emergency->show_urgent_medication;
            $this->show_condition = $emergency->show_condition;
            $this->show_immediate_instructions = $emergency->show_immediate_instructions;
            $this->show_vet = $emergency->show_vet;
        }
    }

    public function save(): void
    {
        $this->authorize('update', $this->pet);

        $this->validate([
            'allergies' => ['nullable', 'string', 'max:1000'],
            'urgent_medication' => ['nullable', 'string', 'max:1000'],
            'condition' => ['nullable', 'string', 'max:1000'],
            'immediate_instructions' => ['nullable', 'string', 'max:1000'],
            'vet_name' => ['nullable', 'string', 'max:100'],
            'vet_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $this->pet->publicSettings()->updateOrCreate(
            ['pet_id' => $this->pet->id],
            [
                'show_name' => $this->show_name,
                'show_photo' => $this->show_photo,
                'show_species' => $this->show_species,
                'show_breed' => $this->show_breed,
                'show_sex' => $this->show_sex,
                'show_color' => $this->show_color,
                'show_markings' => $this->show_markings,
                'show_description' => $this->show_description,
                'show_approach_instructions' => $this->show_approach_instructions,
                'show_contacts' => $this->show_contacts,
                'show_emergency' => $this->show_emergency,
            ],
        );

        $this->pet->emergencySummary()->updateOrCreate(
            ['pet_id' => $this->pet->id],
            [
                'allergies' => $this->allergies ?: null,
                'urgent_medication' => $this->urgent_medication ?: null,
                'condition' => $this->condition ?: null,
                'immediate_instructions' => $this->immediate_instructions ?: null,
                'vet_name' => $this->vet_name ?: null,
                'vet_phone' => $this->vet_phone ?: null,
                'show_allergies' => $this->show_allergies,
                'show_urgent_medication' => $this->show_urgent_medication,
                'show_condition' => $this->show_condition,
                'show_immediate_instructions' => $this->show_immediate_instructions,
                'show_vet' => $this->show_vet,
            ],
        );

        Flux::toast(variant: 'success', text: __('Privacidad actualizada'));
    }
}; ?>

<div class="mx-auto max-w-2xl">
        <flux:heading size="xl" class="mb-6">{{ __('Privacidad') }}: {{ $pet->name }}</flux:heading>

        <form wire:submit="save" class="flex flex-col gap-8">
            <flux:card class="flex flex-col gap-4">
                <flux:heading size="lg">{{ __('Perfil público') }}</flux:heading>
                <flux:text>{{ __('Elige qué información puede ver quien escanee la placa.') }}</flux:text>

                <flux:switch wire:model="show_name" label="{{ __('Nombre') }}" />
                <flux:switch wire:model="show_photo" label="{{ __('Fotografía') }}" />
                <flux:switch wire:model="show_species" label="{{ __('Especie') }}" />
                <flux:switch wire:model="show_breed" label="{{ __('Raza') }}" />
                <flux:switch wire:model="show_sex" label="{{ __('Sexo') }}" />
                <flux:switch wire:model="show_color" label="{{ __('Color') }}" />
                <flux:switch wire:model="show_markings" label="{{ __('Señas particulares') }}" />
                <flux:switch wire:model="show_description" label="{{ __('Descripción') }}" />
                <flux:switch wire:model="show_approach_instructions" label="{{ __('Instrucciones para acercarse') }}" />
                <flux:switch wire:model="show_contacts" label="{{ __('Contactos') }}" />
                <flux:switch wire:model="show_emergency" label="{{ __('Información de emergencia') }}" />
            </flux:card>

            <flux:card class="flex flex-col gap-4">
                <flux:heading size="lg">{{ __('Información de emergencia') }}</flux:heading>

                <flux:field>
                    <flux:label>{{ __('Alergias') }}</flux:label>
                    <flux:textarea wire:model="allergies" rows="2" />
                    <flux:switch wire:model="show_allergies" label="{{ __('Mostrar públicamente') }}" class="mt-2" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Medicamento urgente') }}</flux:label>
                    <flux:textarea wire:model="urgent_medication" rows="2" />
                    <flux:switch wire:model="show_urgent_medication" label="{{ __('Mostrar públicamente') }}" class="mt-2" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Condición relevante') }}</flux:label>
                    <flux:textarea wire:model="condition" rows="2" />
                    <flux:switch wire:model="show_condition" label="{{ __('Mostrar públicamente') }}" class="mt-2" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Instrucciones inmediatas') }}</flux:label>
                    <flux:textarea wire:model="immediate_instructions" rows="2" />
                    <flux:switch wire:model="show_immediate_instructions" label="{{ __('Mostrar públicamente') }}" class="mt-2" />
                </flux:field>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>{{ __('Veterinario') }}</flux:label>
                        <flux:input wire:model="vet_name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>{{ __('Teléfono veterinario') }}</flux:label>
                        <flux:input wire:model="vet_phone" />
                    </flux:field>
                </div>
                <flux:switch wire:model="show_vet" label="{{ __('Mostrar veterinario públicamente') }}" />
            </flux:card>

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">{{ __('Guardar') }}</flux:button>
                <flux:button :href="route('pets.preview', $pet)" wire:navigate>{{ __('Vista previa pública') }}</flux:button>
            </div>
        </form>
</div>
