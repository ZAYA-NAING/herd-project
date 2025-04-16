<?php

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $perPage = 12;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'products' => Product::paginate(100),
        ];
    }
}; ?>

<section class="w-full">
    @include('partials.products-heading')
    {{-- @dd($products[0]); --}}
    <x-products.layout :heading="__('Grid')" :subheading="__('Managament and information of our products')">
        <div class="my-6 w-full space-y-6">
            <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
                <div class="grid auto-rows-min gap-4 grid-cols-1 md:grid-cols-3">
                    @foreach ($products as $item)
                        <x-item-grid :item="$item"/>
                    @endforeach
                </div>
            </div>
        </div>
        <livewire:products.create-product-form />
    </x-products.layout>
</section>
