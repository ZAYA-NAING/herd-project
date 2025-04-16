@extends('layouts.app')

@section('content')

    <h1>You are currently not connected to any networks.</h1>

@endsection

{{-- <x-layouts.app title="Offline">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="absolute top-[43%] lg:top-[50%] xl:top-[45%] w-full px-4">
                <div class="flex justify-center">
                    <flux:icon.link-slash class="size-8" />
                </div>
                
                <h1 class="text-center text-lg font-semibold">Offline</h1>
                <span class="text-center text-sm md:text-base block">You are currently not connected to any networks.</span>
            </div>
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>

<script>
    window.addEventListener('load', function() {
        fetch('http://127.0.0.1:8000/offline').then((res) => {
            console.log(res);
        });
    });
</script> --}}
