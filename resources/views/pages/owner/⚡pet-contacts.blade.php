<?php

use App\Enums\ContactType;
use App\Models\Pet;
use App\Models\PetContact;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Contactos')] class extends Component {
    use AuthorizesRequests;

    #[Locked]
    public Pet $pet;

    public string $name = '';
    public string $type = 'primary';
    public string $phone = '';
    public string $whatsapp = '';
    public string $email = '';
    public bool $show_phone = false;
    public bool $show_whatsapp = false;
    public bool $show_email = false;

    public function mount(Pet $pet): void
    {
        $this->authorize('update', $pet);
        $this->pet = $pet;
    }

    #[Computed]
    public function contacts()
    {
        return $this->pet->contacts()->orderBy('sort_order')->get();
    }

    public function addContact(): void
    {
        $this->authorize('update', $this->pet);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:primary,alternate,family,veterinarian'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'show_phone' => ['boolean'],
            'show_whatsapp' => ['boolean'],
            'show_email' => ['boolean'],
        ]);

        $this->pet->contacts()->create([
            ...$validated,
            'type' => ContactType::from($validated['type']),
            'sort_order' => $this->pet->contacts()->count(),
        ]);

        $this->reset(['name', 'phone', 'whatsapp', 'email', 'show_phone', 'show_whatsapp', 'show_email']);
        $this->type = 'primary';

        Flux::toast(variant: 'success', text: __('Contacto agregado'));
        unset($this->contacts);
    }

    public function removeContact(int $contactId): void
    {
        $contact = PetContact::query()->where('pet_id', $this->pet->id)->findOrFail($contactId);
        $this->authorize('update', $this->pet);
        $contact->delete();

        Flux::toast(variant: 'success', text: __('Contacto eliminado'));
        unset($this->contacts);
    }
}; ?>

<div class="mx-auto max-w-2xl">
        <flux:heading size="xl" class="mb-6">{{ __('Contactos') }}: {{ $pet->name }}</flux:heading>

        @if ($this->contacts->isNotEmpty())
            <div class="mb-8 flex flex-col gap-3">
                @foreach ($this->contacts as $contact)
                    <flux:card wire:key="contact-{{ $contact->id }}" class="flex items-start justify-between gap-4">
                        <div>
                            <flux:heading size="sm">{{ $contact->name }}</flux:heading>
                            <flux:text>{{ $contact->type->label() }}</flux:text>
                            @if ($contact->phone)
                                <flux:text class="text-sm">📞 {{ $contact->phone }} @if($contact->show_phone) (público) @endif</flux:text>
                            @endif
                            @if ($contact->whatsapp)
                                <flux:text class="text-sm">WA: {{ $contact->whatsapp }} @if($contact->show_whatsapp) (público) @endif</flux:text>
                            @endif
                        </div>
                        <flux:button size="sm" variant="danger" wire:click="removeContact({{ $contact->id }})" wire:confirm="{{ __('¿Eliminar contacto?') }}">
                            {{ __('Eliminar') }}
                        </flux:button>
                    </flux:card>
                @endforeach
            </div>
        @endif

        <flux:card>
            <flux:heading size="lg" class="mb-4">{{ __('Agregar contacto') }}</flux:heading>
            <form wire:submit="addContact" class="flex flex-col gap-4">
                <flux:field>
                    <flux:label>{{ __('Nombre') }}</flux:label>
                    <flux:input wire:model="name" required />
                </flux:field>
                <flux:field>
                    <flux:label>{{ __('Tipo') }}</flux:label>
                    <flux:select wire:model="type">
                        @foreach (ContactType::cases() as $option)
                            <flux:select.option value="{{ $option->value }}">{{ $option->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>
                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>{{ __('Teléfono') }}</flux:label>
                        <flux:input wire:model="phone" />
                        <flux:switch wire:model="show_phone" label="{{ __('Mostrar públicamente') }}" class="mt-2" />
                    </flux:field>
                    <flux:field>
                        <flux:label>{{ __('WhatsApp') }}</flux:label>
                        <flux:input wire:model="whatsapp" />
                        <flux:switch wire:model="show_whatsapp" label="{{ __('Mostrar públicamente') }}" class="mt-2" />
                    </flux:field>
                </div>
                <flux:field>
                    <flux:label>{{ __('Correo') }}</flux:label>
                    <flux:input type="email" wire:model="email" />
                    <flux:switch wire:model="show_email" label="{{ __('Mostrar públicamente') }}" class="mt-2" />
                </flux:field>
                <flux:button type="submit" variant="primary">{{ __('Agregar contacto') }}</flux:button>
            </form>
        </flux:card>
</div>
