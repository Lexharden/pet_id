<?php

use App\Models\Pet;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Escaneos')] class extends Component {
    use AuthorizesRequests;

    #[Locked]
    public Pet $pet;

    public function mount(Pet $pet): void
    {
        $this->authorize('view', $pet);
        $this->pet = $pet;
    }

    #[Computed]
    public function scans()
    {
        return \App\Models\ScanEvent::query()
            ->whereHas('tag.assignments', fn ($q) => $q->where('pet_id', $this->pet->id))
            ->with('tag')
            ->orderByDesc('scanned_at')
            ->limit(50)
            ->get();
    }
}; ?>

<div class="mx-auto max-w-2xl">
    <flux:heading size="xl" class="mb-6">{{ __('Historial de escaneos') }}: {{ $pet->name }}</flux:heading>

    @if ($this->scans->isEmpty())
        <flux:card class="py-8 text-center">
            <flux:text>{{ __('Sin escaneos registrados') }}</flux:text>
        </flux:card>
    @else
        <div class="flex flex-col gap-2">
            @foreach ($this->scans as $scan)
                <flux:card wire:key="scan-{{ $scan->id }}" class="flex items-center justify-between gap-4">
                    <div>
                        <flux:text class="font-medium">{{ $scan->result->label() }}</flux:text>
                        <flux:text class="text-sm text-zinc-500">
                            {{ $scan->scanned_at->timezone(config('app.display_timezone'))->format('d/m/Y H:i') }}
                            · {{ $scan->channel->label() }}
                        </flux:text>
                    </div>
                </flux:card>
            @endforeach
        </div>
    @endif
</div>
