<?php

use App\Actions\Pets\UploadPetPhoto;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use App\Models\Pet;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Editar mascota')] class extends Component {
    use AuthorizesRequests;
    use WithFileUploads;

    #[Locked]
    public Pet $pet;

    public string $name = '';
    public string $species = 'dog';
    public string $breed = '';
    public string $sex = '';
    public string $birth_date_approx = '';
    public string $color = '';
    public string $markings = '';
    public string $description = '';
    public string $approach_instructions = '';

    public $photo;

    public function mount(Pet $pet): void
    {
        $this->authorize('update', $pet);
        $this->pet = $pet;

        $this->name = $pet->name;
        $this->species = $pet->species->value;
        $this->breed = $pet->breed ?? '';
        $this->sex = $pet->sex?->value ?? '';
        $this->birth_date_approx = $pet->birth_date_approx?->format('Y-m-d') ?? '';
        $this->color = $pet->color ?? '';
        $this->markings = $pet->markings ?? '';
        $this->description = $pet->description ?? '';
        $this->approach_instructions = $pet->approach_instructions ?? '';
    }

    public function save(): void
    {
        $this->authorize('update', $this->pet);

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
            'photo' => ['nullable', 'image', 'max:5120', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $this->pet->update([
            'name' => $validated['name'],
            'species' => PetSpecies::from($validated['species']),
            'breed' => $validated['breed'] ?: null,
            'sex' => $validated['sex'] ? PetSex::from($validated['sex']) : null,
            'birth_date_approx' => $validated['birth_date_approx'] ?: null,
            'color' => $validated['color'] ?: null,
            'markings' => $validated['markings'] ?: null,
            'description' => $validated['description'] ?: null,
            'approach_instructions' => $validated['approach_instructions'] ?: null,
        ]);

        if ($this->photo) {
            $showPhoto = $this->pet->publicSettings?->show_photo ?? false;
            app(UploadPetPhoto::class)->execute($this->pet, $this->photo, $showPhoto);
            $this->photo = null;
        }

        Flux::toast(variant: 'success', text: __('Cambios guardados'));

        $this->pet->refresh();
    }

    public function deletePet(): void
    {
        $this->authorize('delete', $this->pet);
        $this->pet->delete();

        Flux::toast(variant: 'success', text: __('Mascota eliminada'));

        $this->redirect(route('pets.index'), navigate: true);
    }
}; ?>

<div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <flux:heading size="xl">{{ __('Editar') }}: {{ $pet->name }}</flux:heading>
            <flux:button :href="route('pets.index')" variant="ghost" size="sm" wire:navigate>
                {{ __('Mis mascotas') }}
            </flux:button>
        </div>

        <div class="mb-6 flex flex-wrap gap-2">
            <flux:button size="sm" :href="route('pets.privacy', $pet)" wire:navigate>{{ __('Privacidad') }}</flux:button>
            <flux:button size="sm" :href="route('pets.contacts', $pet)" wire:navigate>{{ __('Contactos') }}</flux:button>
            <flux:button size="sm" :href="route('pets.tags', $pet)" wire:navigate>{{ __('Placas') }}</flux:button>
            <flux:button size="sm" :href="route('pets.status', $pet)" wire:navigate>{{ __('Estado') }}</flux:button>
            <flux:button size="sm" :href="route('pets.scans', $pet)" wire:navigate>{{ __('Escaneos') }}</flux:button>
            <flux:button size="sm" :href="route('pets.preview', $pet)" wire:navigate>{{ __('Vista previa pública') }}</flux:button>
        </div>

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

            <flux:field>
                <flux:label>{{ __('Fotografía') }}</flux:label>
                <flux:input type="file" wire:model="photo" accept="image/jpeg,image/png,image/webp" />
                <flux:error name="photo" />
                @if ($pet->primary_photo_path && $pet->primary_photo_disk)
                    <flux:text class="mt-2 text-sm">{{ __('Ya hay una foto principal cargada.') }}</flux:text>
                @endif
            </flux:field>

            <div class="flex flex-wrap gap-3">
                <flux:button type="submit" variant="primary">{{ __('Guardar') }}</flux:button>
                <flux:button type="button" variant="danger" wire:click="deletePet" wire:confirm="{{ __('¿Eliminar esta mascota?') }}">
                    {{ __('Eliminar') }}
                </flux:button>
            </div>
        </form>
</div>
