<?php

use App\Models\Pet;
use App\Support\PublicPetProfile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Vista previa pública')] class extends Component {
    use AuthorizesRequests;

    #[Locked]
    public Pet $pet;

    public function mount(Pet $pet): void
    {
        $this->authorize('view', $pet);
        $this->pet = $pet;
    }

    #[Computed]
    public function profile(): array
    {
        return PublicPetProfile::build($this->pet);
    }
}; ?>

<div class="mx-auto max-w-lg">
        <flux:heading size="xl" class="mb-2">{{ __('Vista previa pública') }}</flux:heading>
        <flux:text class="mb-6">{{ __('Así verá tu mascota quien escanee la placa, según tu configuración de privacidad.') }}</flux:text>

        @include('partials.public-pet-profile', ['profile' => $this->profile])
</div>
