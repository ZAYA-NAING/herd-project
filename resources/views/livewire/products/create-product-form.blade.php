<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\Product;

new class extends Component {
    public string $name = '';
    public string $description = '';
    public $price = "0.00";
    public string $image = '/storage/image/1.png';
    public $stock = 1;

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required','decimal:2'],
            'image' => ['required', 'string'],
            'stock' => ['required', 'integer'],
        ]);

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'stock' => $this->stock,
        ]);

        return redirect()->to('/products');
    }

    /**
     * Change the currently item.
     */
    public function editItem(Logout $logout): void
    {
        $this->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'string'],
            'image' => ['required', 'string'],
            'stock' => ['required', 'integer'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Create a new item') }}</flux:heading>
        <flux:subheading>{{ __('Create a new your item and all of its resources') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-item-creation">
        <flux:button variant="primary" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-item-creation')">
            {{ __('Create') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-item-creation" :show="$errors->isNotEmpty()" focusable class="max-w-lg" variant="flyout" position="bottom">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Are you sure you want to edit your item?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Once your item is edits, all of its resources and data will be edited. Please enter your item to confirm you would like to edit your item.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="name" :label="__('Name')" />

            <flux:input wire:model="price" :label="__('Price')" />

            <flux:input wire:model="stock" :label="__('Stock')" type="number" />

            <flux:input wire:model="image" :label="__('Image')" value="/storage/image/1.png" />

            <flux:input wire:model="description" :label="__('Description')" />

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
