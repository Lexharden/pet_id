<?php

use App\Models\Pet;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Placas')] class extends Component {
    use AuthorizesRequests;

    #[Locked]
    public Pet $pet;

    public function mount(Pet $pet): void
    {
        $this->authorize('view', $pet);
        $this->pet = $pet;
    }

    #[Computed]
    public function assignments()
    {
        return $this->pet->tagAssignments()
            ->with('tag')
            ->orderByDesc('assigned_at')
            ->get();
    }
}; ?>

<div class="mx-auto max-w-2xl">
    <div class="mb-6 flex items-center justify-between">
        <flux:heading size="xl">{{ __('Placas') }}: {{ $pet->name }}</flux:heading>
        <flux:button :href="route('tags.activate')" size="sm" wire:navigate>{{ __('Activar placa') }}</flux:button>
    </div>

    @if ($this->assignments->isEmpty())
        <flux:card class="py-8 text-center">
            <flux:text>{{ __('Sin placas activas para esta mascota.') }}</flux:text>
            <flux:button :href="route('tags.activate')" class="mt-4" wire:navigate>{{ __('Activar placa') }}</flux:button>
        </flux:card>
    @else
        <div class="flex flex-col gap-3">
            @foreach ($this->assignments as $assignment)
                <flux:card wire:key="assignment-{{ $assignment->id }}">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <flux:badge color="{{ $assignment->isActive() ? 'green' : 'zinc' }}">
                                {{ $assignment->tag->status->label() }}
                            </flux:badge>
                            <flux:text class="mt-1 text-sm">
                                {{ $assignment->assigned_at->timezone(config('app.display_timezone'))->format('d/m/Y H:i') }}
                            </flux:text>
                        </div>
                        @if ($assignment->isActive())
                            <flux:text class="break-all text-xs text-zinc-500">{{ $assignment->tag->publicUrl() }}</flux:text>
                        @endif
                    </div>
                </flux:card>
            @endforeach
        </div>
    @endif
</div>
