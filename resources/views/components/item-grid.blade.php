@props(['title', 'description', 'label1', 'label2', 'item'])

<div class="rounded-xl border border-neutral-200 dark:border-neutral-700">
    <div class="p-4 text-sm font-normal">
        <div class="flex items-center gap-2 text-left text-sm">
            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                <span
                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                    {{ $item->initials() }}
                </span>
            </span>
        </div>
        <div class="flex justify-between mt-2">
            <div class="flex items-center gap-1 text-left text-sm">
                <span class="relative flex h-4 w-4 flex-1  overflow-hidden rounded-lg">
                    <span class="flex h-full w-full items-center justify-center text-black dark:text-white">
                        <flux:icon.archive-box class="size-5" />
                    </span>
                </span>

                <div class="grid shrink-0 text-left text-sm leading-tight">
                    @isset($label1)
                        <flux:tooltip :content="$label1">
                            <span
                                class="font-semibold w-10 block overflow-hidden truncate">{{ $label1 ?? __('0') }}</span>
                        </flux:tooltip>
                    @endisset
                    <flux:tooltip :content="$item->stock">
                        <span
                            class="font-semibold w-10 block overflow-hidden truncate">{{ __($item->stock) ?? __('0') }}</span>
                    </flux:tooltip>
                </div>
            </div>
            <div class="flex items-center gap-1 text-left text-sm">
                <span class="relative flex h-4 w-4 shrink-0 overflow-hidden rounded-lg">
                    <span class="flex h-full w-full items-center justify-center text-black dark:text-white">
                        <flux:icon.currency-dollar class="size-5" />
                    </span>
                </span>

                <div class="grid flex-1 text-left text-sm leading-tight">
                    @isset($label2)
                        <flux:tooltip :content="$label2">
                            <span
                                class="font-semibold w-10 block overflow-hidden truncate">{{ $label2 ?? __('0.00') }}</span>
                        </flux:tooltip>
                    @endisset
                    <flux:tooltip :content="$item->price">
                        <span
                            class="font-semibold w-10 block overflow-hidden truncate">{{ __($item->price) ?? __('0.00') }}</span>
                    </flux:tooltip>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 text-left text-sm mt-2">
            <div class="grid flex-1 text-left text-sm leading-tight">
                @isset($title)
                    <span class="truncate font-semibold">{{ $title ?? __('Title') }}</span>
                @endisset
                @isset($description)
                    <span class="truncate text-xs">{{ $description ?? __('Description') }}</span>
                @endisset
                <span class="truncate font-semibold">{{ $item->name ?? __('Title') }}</span>
                <span class="truncate text-xs">{{ $item->description ?? __('Description') }}</span>
            </div>
        </div>
    </div>
</div>
