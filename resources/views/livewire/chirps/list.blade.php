<?php

use App\Models\Chirp;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public Collection $chirps;
    // mise à jour du composant pour la modification d'un chirp
    public ?Chirp $editing = null;
    // fonction permettant de supprimer un chirp
    public function delete(Chirp $chirp): void
    {
        // autorisation de la suppression d'un chirp
        $this->authorize('delete', $chirp);
        // suppression d'un chirp
        $chirp->delete();
        // retour sur l'ensemble des chirps
        $this->getChirps();
    }
    public function mount(): void
    {
        $this->getChirps();
    }
    #[On("chirp-created")]

    // ensemble des chirps
    public function getChirps(): void
    {
        $this->chirps = Chirp::with('user')->latest()->get();
    }
    // mise à jour d'un chirp
    public function edit(Chirp $chirp): void
    {
        // l"élément en cours à éditer prendra la valeur modifiée 
        $this->editing = $chirp;
        // reprise de l'ensemble des chirps
        $this->getChirps();
    }
    //  écouter les événements chirp-updated(de modification) et chirp-edit-canceled(annulation de la modification)
    #[On('chirp-edit-canceled')]
    #[On('chirp-updated')]
    // fonction permettant de ne pas afficher le formulaire d'édition
    public function disableEditing(): void
    {
        // mettre la propriété editing sur null pour ne plus afficher le formulaire d'édition
        $this->editing = null;
        // retour sur l'ensemble des chirps
        $this->getChirps();
    }
}; ?>

<div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
    @foreach ($chirps as $chirp)
        <div class="p-6 flex space-x-2" wire:key="{{ $chirp->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-800">{{ $chirp->user->name }}</span>
                        <small class="ml-2 text-sm text-gray-600">{{ $chirp->created_at->format('j M Y, g:i a') }}</small>
                        {{--
                        modification d'un chirp
                        --}}
                        @unless ($chirp->created_at->eq($chirp->updated_at))
                            <small class="text-sm text-gray-600">&middot; {{ __('edited') }}</small>
                        @endunless
                    </div>
                    {{--
                    modification d'un chirp
                    --}}
                    @if ($chirp->user->is(auth()->user()))
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                {{--
                                button d'editation
                                 --}}
                                <x-dropdown-link wire:click="edit({{ $chirp->id}})">
                                    {{ __('Modifier')}}
                                </x-dropdown-link>
                                {{--
                                    button de suppression
                                --}}
                                <x-dropdown-link wire:click="delete({{ $chirp->id}})" wire:confirm="Voulez-vous vraiment supprimer ce chirp ?">
                                    {{ __('Supprimer')}}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>

                    @endif
                </div>
                @if ($chirp->is($editing))
                    <livewire:chirps.edit :chirp="$chirp" :key="$chirp->id" />
                @else
                    <p class="mt-4 text-lg text-gray-900">
                        {{ $chirp->message }}
                    </p>
                @endif
            </div>
        </div>
    @endforeach 


