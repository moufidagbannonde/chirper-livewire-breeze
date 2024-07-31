<?php

// mettre à jour un chirp 
use App\Models\Chirp;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    // formulaire de validation
    public Chirp $chirp;

    // validation des champs 
    #[Validate('required|string|max:255')]
    // déclaration d'une propriété $message comme un string vide
    public function mount(): void
    {
        $this->message = $this->chirp->message;
    }
    public function update(): void
    {
        // autorisation de la modification d'un chirp
        $this->authorize('update', $this->chirp);
        // validation du champ de formulaire
        $validated = $this->validate();
        // mise à jour du chirp
        $this->chirp->update($validated);
        // retourner sur le chirp édité
        $this->dispatch('chirp-updated');
    }
    // fonction de retour 
    public function cancel(): void
    {
        $this->dispatch('chirp-edit-canceled');
    }

}; ?>

<div>
    {{--
        formulaire contenant le bouton de retour si au préalable, 
    l'utilisateur avait appuyé sur le bouton edit par erreur
     et ne voulait pas faire de modification
        --}}
    <form wire:submit="update">
        <textarea wire:model="message"
            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>

        <x-input-error :messages="$errors->get('message')" class="mt-2" />
        <x-primary-button class="mt-4">{{ __('Save') }}</x-primary-button>
        <button class="mt-4" wire:click.prevent="cancel">Cancel</button>
    </form>
</div>