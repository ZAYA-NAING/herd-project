<?php

use App\Models\TaskManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // public $taskManagers;
    public $currentModalName = 'task-creation'; // ['task-creation', 'task-edition-[id]'
    public $showConfirmModal = false;
    public $editingTaskManager = null;
    public $search = '';
    protected $queryString = ['search']; // Preserve search in URL
    public $form = [
        'name' => '',
        'description' => '',
    ];

    protected $rules = [
        'form.name' => 'required|min:3',
        'form.description' => 'required|min:5',
    ];

    public function createModal()
    {
        $this->showConfirmModal = true;
        $this->currentModalName = 'task-creation';
        $this->form = ['name' => '', 'description' => ''];
    }

    public function editModal($id)
    {
        $this->editingTaskManager = TaskManager::find($id);
        $this->form = $this->editingTaskManager->toArray();
        $this->showConfirmModal = true;
        $this->currentModalName = 'task-edition-'.$id;
    }

    public function saveModal()
    {
        $this->validate();

        if ($this->currentModalName === 'task-creation') {
            TaskManager::create($this->form);
            session()->flash('message', 'Task Manager created!');
        } else {
            $this->editingTaskManager->update($this->form);
            session()->flash('message', 'Task Manager updated!');
        }

        $this->showConfirmModal = false;

        $this->with();

        $this->dispatch('task-saved')->self();
    }

    public function delete($id)
    {
        TaskManager::destroy($id);
        $this->with();
    }

    public function with(): array
    {
        return [
            'taskManagers' => TaskManager::when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('description', 'like', '%'.$this->search.'%');
            })->paginate(6),
        ];
    }
}; ?>

<section class="w-full">
    @include('partials.taskmanagers-heading')
    <x-taskmanagers.layout :heading="__('Index with modal')" :subheading="__('Managament and information of our tasks with modal')">
        <div x-data="{ shown: false, timeout: null }"
            x-init="@this.on('{{ 'task-saved' }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 5000); })"
            x-show.transition.out.opacity.duration.1500ms="shown"
            x-transition:leave.opacity.duration.1500ms
            style="display: none"
            class="text-sm absolute top-0 right-0 z-50"
        >
            @session('message')
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-zinc-800/5 dark:bg-white/5 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] p-4">
                    <flux:heading class="text-green-600 dark:text-green-400">Successed!!</flux:heading>
                    <flux:text class="mt-2">  {{ __(session('message')) }}</flux:text>
                </div>
            @endsession
        </div>

        <div class="index-view">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." icon="magnifying-glass" class="mb-6" clearable />

            <flux:button icon="plus" variant="filled" wire:click="createModal">
                {{ __('New Task') }}
            </flux:button>

            <div class="my-4 w-full space-y-4">
                <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
                    <div class="grid auto-rows-min gap-4 grid-cols-1 md:grid-cols-3">
                        @foreach ($taskManagers as $taskManager)
                            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700">
                                <div class="p-4 text-sm font-normal">
                                    <div class="flex items-center gap-2 text-left text-sm">
                                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                            <span
                                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                                {{ $taskManager->initials() }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-left text-sm mt-2">
                                        <div class="grid flex-1 text-left text-sm leading-tight">
                                            <span class="truncate font-semibold">{{ $taskManager->name ?? __('Title') }}</span>
                                            <span
                                                class="truncate text-xs">{{ $taskManager->description ?? __('Description') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 mt-2">
                                        <flux:button variant="primary" icon="pencil-square" wire:click="editModal({{ $taskManager->id }})"></flux:button>
                                        <flux:button variant="danger" icon="trash" wire:click="delete({{ $taskManager->id }})"></flux:button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{  $taskManagers->links('vendor.livewire.custom-pagination-links') }}
                </div>
            </div>
            <flux:modal wire:model.self="showConfirmModal" :show="$errors->isNotEmpty()" focusable class="max-w-lg" variant="flyout" position="bottom">
                <form wire:submit="saveModal" class="space-y-6">
                    <div>
                        <flux:heading size="lg">{{ $currentModalName === 'task-creation' ? __('Add task') :  __('Edit task') }}</flux:heading>

                        <flux:subheading>
                            {{ $currentModalName === 'task-creation'
                                ? __('Please fill your task information.')
                                : __('Please confirm that you want to edit your task by filling it out.')
                                }}
                        </flux:subheading>
                    </div>

                    <flux:input
                        wire:model="form.name"
                        :label="__('Name')"
                        type="text"
                    />

                    <flux:textarea wire:model="form.description" rows="2" :label="__('Descrioption')" />

                    <div class="flex justify-end space-x-2">
                        <flux:modal.close>
                            <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                        </flux:modal.close>

                        <flux:button variant="primary" type="submit">{{  $currentModalName === 'task-creation' ? __('Create') :  __('Update') }}</flux:button>
                    </div>
                </form>
            </flux:modal>
        </div>
    </x-taskmanagers.layout>
</section>
