<?php

use App\Models\TaskManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $taskManagers;
    public $currentAction = 'index'; // ['index', 'create', 'edit']
    public $editingTaskManager = null;
    public $form = [
        'name' => '',
        'description' => '',
    ];

    protected $rules = [
        'form.name' => 'required|min:3',
        'form.description' => 'required|min:5',
    ];

    public function mount()
    {
        $this->loadTaskManagers();
    }

    public function loadTaskManagers()
    {
        $this->taskManagers = TaskManager::all();
    }

    public function create()
    {
        $this->currentAction = 'create';
        $this->form = ['name' => '', 'description' => ''];
    }

    public function edit($id)
    {
        $this->editingTaskManager = TaskManager::find($id);
        $this->form = $this->editingTaskManager->toArray();
        $this->currentAction = 'edit';
    }

    public function save()
    {
        $this->validate();

        if ($this->currentAction === 'create') {
            TaskManager::create($this->form);
            session()->flash('message', 'Task Manager created!');
        } else {
            $this->editingTaskManager->update($this->form);
            session()->flash('message', 'Task Manager updated!');
        }

        $this->dispatch('task-saved');

        $this->currentAction = 'index';
        $this->loadTaskManagers();
    }

    public function cancel()
    {
        $this->currentAction = 'index';
        $this->form = ['name' => '', 'description' => ''];
    }

    public function delete($id)
    {
        TaskManager::destroy($id);
        $this->loadTaskManagers();
    }

    public function with(): array
    {
        return [
            'taskmanagers' => TaskManager::paginate(100),
        ];
    }
}; ?>

<section class="w-full">
    @include('partials.taskmanagers-heading')

    <x-taskmanagers.layout :heading="__('Index')" :subheading="__('Managament and information of our tasks')">
        @if (session('message'))
            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                {{ __(session('message')) }}
            </flux:text>
        @endif

        @if ($currentAction === 'index')
            <div class="index-view">
                <flux:button icon="plus" variant="filled" wire:click="create">
                    {{ __('New Task') }}
                </flux:button>
                <div class="my-6 w-full space-y-6">
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
                                            <flux:button variant="primary" icon="pencil-square" wire:click="edit({{ $taskManager->id }})"></flux:button>
                                            <flux:button variant="danger" icon="trash" wire:click="delete({{ $taskManager->id }})"></flux:button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="form-view">
                <form wire:submit="save" class="mt-6 space-y-6">
                    <flux:input
                        wire:model="form.name"
                        :label="__('Name')"
                        type="text"
                        required
                    />
                    <flux:input
                        wire:model="form.description"
                        :label="__('Description')"
                        type="text"
                        required
                    />

                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-end gap-2">
                            <flux:button variant="primary" type="submit" class="w-full">{{ $currentAction === 'create' ? __('Create') :  __('Update') }}</flux:button>
                            <flux:button variant="filled" type="button" class="w-full" wire:click="cancel">{{ __('Cancel') }}</flux:button>
                        </div>

                        <x-action-message class="me-3" on="task-saved">
                            {{ __('Saved.') }}
                        </x-action-message>
                    </div>
                </form>

                <div class="mx-auto max-w-md overflow-hidden rounded-xl bg-white shadow-md lg:max-w-2xl">
                    <div class="md:flex">
                      <div class="md:shrink-0">
                        <img
                          class="h-48 w-full object-cover md:h-full md:w-48"
                          src="https://unavatar.io/x/calebporzio"
                          alt="Modern building architecture"
                        />
                      </div>
                      <div class="p-8">
                        <div class="text-sm font-semibold tracking-wide text-indigo-500 uppercase">Company retreats</div>
                        <a href="#" class="mt-1 block text-lg leading-tight font-medium text-black hover:underline">
                          Incredible accommodation for your team
                        </a>
                        <p class="mt-2 text-gray-500">
                          Looking to take your team away on a retreat to enjoy awesome food and take in some sunshine? We have a list of
                          places to do just that.
                        </p>
                      </div>
                    </div>
                </div>


            </div>
        @endif
    </x-taskmanagers.layout>
</section>
