@props([ 'id' => 'entry-modal', 'x-ref' => null, 'data-id' => null ])
<div class="contents"
    @isset(${'x-ref'})
        x-ref="{{ ${'x-ref'} }}"
        x-init="$refs[@js( ${'x-ref'} )] = $el.firstElementChild"
    @endisset
>
<div
    @isset(${'data-id'}) id="{{ ${'data-id'} }}" @endisset
    {{ $attributes->class('') }}
    x-data="{
        id: null,
        title: null,
        description: null,
        category: null,
        deadline: null,
        assignees: null,
        button: null,
        head: null,

        div: $el.querySelector('& > div'),
        input: $el.querySelector('& > input'),
        modal: null,
        outside: false,
        closeEvent: false,
        change() {
            if(this.input.checked)
                disablePageScroll(/* this.div */);
            else
                // enablePageScroll(/* this.div */);
                this.closeEvent = true;



            if (! this.input.checked)
            {
                this.id.value     = null;
                this.title        .clear();
                this.description  .clear();
                this.category     .clear();
                this.deadline     .clear();
                this.assignees    .clear();
            }
        },
        checkbox(check = null) {
            if (check !== null)
                this.input.checked = check;
            else
                this.input.checked = ! this.input.checked;

            this.change();
        },
        changeValues(id = null, title = null, description = null, category = null, deadline = null, assignees = null)
        {
            this.id.value = id;
            title           &&    this.title       .setValue(title);
            description     &&    this.description .setValue(description);
            category        &&    this.category    .setValue(category);
            deadline        &&    this.deadline    .setValue(deadline);
            assignees       &&    this.assignees   .setValues(assignees);
        },
        changeModalValue(value) {
            const spanEl = this.button.querySelector(`& > :is([${value}])`);
            const headEl = this.head  .querySelector(`& > :is([${value}])`);
            if (! spanEl) return;
            if (! headEl) return;


            for (const child of this.button.children)
            {
                child.classList.add('hidden');
            }
            for (const headTitle of this.head.children)
            {
                headTitle.classList.remove('contents');
                headTitle.classList.add('hidden');
            }

            spanEl.classList.remove('hidden');
            headEl.classList.add('contents');
            headEl.classList.remove('hidden');
        },



        {{-- Public methods --}}
        open(id = null, title = null, description = null, category = null, deadline = null, assignees = null, buttonValue = null) {
            if (id === null)
            {
                this.checkbox(true);
                return;
            }

            this.changeValues(id, title, description, category, deadline, assignees);
            this.changeModalValue(buttonValue);

            this.checkbox(true);
        },
        close() {
            this.checkbox(false);
        },
    }"
    x-init="
        $nextTick(() => {
            id            = $refs.id;
            title         = Alpine.$data( $refs.title       );
            description   = Alpine.$data( $refs.description );
            category      = Alpine.$data( $refs.category    );
            deadline      = Alpine.$data( $refs.deadline    );
            assignees     = Alpine.$data( $refs.assignees   );
        });
        modal = div.querySelector('& > div');
        if (input.checked)
            change();

    "
    x-on:transitionend="if ($event.propertyName === 'translate' && closeEvent) {  enablePageScroll(/* this.div */); closeEvent = false  }"
>

    <input type="checkbox" class="hidden peer" id="{{ $id }}" @change.self="change" @checked(session('taskCreateBack', false) || session('taskUpdateBack', false))>

    <div class="fixed inset-0 p-8 flex items-center-safe justify-center-safe antialiased selection:bg-indigo-500/30 selection:text-white bg-black/70 backdrop-blur-sm z-999 opacity-0 pointer-events-none transition-all peer-checked:opacity-100 peer-checked:pointer-events-auto cursor-auto overflow-auto overscroll-contain
        duration-700 ease-out
        not-peer-checked:overflow-hidden not-peer-checked:*:-translate-y-1/6 peer-checked:*:translate-0 *:transition-all *:duration-700 *:ease-out"

        @mousedown.self="outside = ! modal.contains($event.target)"
        @mouseup.self="if ( outside && ! modal.contains($event.target) ) checkbox(false)"
        @keydown.escape.window="checkbox(false)"
    >
        <div class="w-full max-w-xl bg-zinc-900/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-8 shadow-2xl shadow-black/60 relative overflow-show z-998">

            <div class="absolute top-0 inset-x-0 h-px bg-linear-to-r from-transparent via-zinc-700/50 to-transparent"></div>
            <div class="space-y-3">


                {{-- Header --}}
                <div class="select-none">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2" x-init="head = $el">


                            <div create class="@if(session('taskUpdateBack', false)) hidden @else contents @endif">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    class="h-4 w-4"
                                >
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                <span>Add Task</span>
                            </div>


                            <div update class="@if(session('taskUpdateBack', false)) contents @else hidden @endif">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                <span>Update Task</span>
                            </div>


                        </h3>
                        {{-- زر الإغلاق: عبارة عن Label مرتبطة بنفس الـ Checkbox لإلغاء تفعيله --}}
                        <label for="{{ $id }}" class="p-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg cursor-pointer transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </label>
                    </div>
                </div>

                <hr class="select-none text-zinc-500/50">

                <div>



                    <form method="POST" action="{{ route('add_and_edit_task') }}" class="flex flex-col justify-center items-center *:w-full gap-y-6 py-5"
                        x-init="
                            $el.noValidate = true;
                            $el.parentElement.addEventListener('submit', (event) => {

                                if (event.defaultPrevented)
                                    $el.querySelector('& > button').classList.add('animate-shake');

                            });
                        "
                    >
                        @csrf

                        <input type="hidden" name="id" autocomplete="off" x-ref="id" value="{{ old('id') }}">


                        <x-ui.text-input.has-error

                            x-ref="title"
                            class="w-full"
                            input-class="bg-zinc-900! border-zinc-700! placeholder-zinc-400!"
                            title="Task designation"
                            placeholder="Enter task name ...."
                            name="title"
                            msg="Give the task a designation."

                        />


                        <x-ui.textarea

                            x-ref="description"
                            class="w-full"
                            title="Detailed description"
                            placeholder="Specify task parameters ...."
                            name="description"
                            input-class="bg-zinc-900! border-zinc-700! placeholder-zinc-400!"

                        />

                        <div class="flex gap-3 items-start justify-between w-full">

                            <x-ui.radio.search.has-error

                                x-ref="category"
                                title="Category"
                                class="z-200! flex-1"
                                msg="Set the task's category."
                                placeholder="e.g. Planning"
                                name="category"
                                input-class="bg-zinc-900! border-zinc-700! placeholder-zinc-400!"

                            >

                                @foreach ($categories as $item)
                                    <x-ui.radio.search.option :value="$item->name" :color="$item->color" />
                                @endforeach

                            </x-ui.radio.search.has-error>

                            <x-ui.date-input.has-error

                                x-ref="deadline"
                                title="Deadline node"
                                class="flex-1"
                                msg="Set a deadline node."
                                name="deadline"
                                input-class="bg-zinc-900! border-zinc-700! placeholder-zinc-400!"

                            />

                        </div>




                        <x-ui.selection.search

                            x-ref="assignees"
                            title="Assigned personnel"
                            class="w-full"
                            name="assignees"
                            input-class="bg-zinc-900! border-zinc-700! placeholder-zinc-400!"
                            placeholder="Enter assigned personnel ...."
                            :overflow="false"

                        >

                            @foreach ($assignees as $name)
                                <x-ui.selection.search.option :value="$name" />
                            @endforeach

                        </x-ui.selection.search>



                        <button type="submit" class="cursor-pointer w-full bg-rose-600 hover:bg-rose-500 text-white font-medium py-3 px-4 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-rose-600/20 hover:shadow-rose-600/30"
                            x-init="$el.addEventListener('animationend', () => $el.classList.remove('animate-shake')); button = $el"
                        >
                            <span update @if(! session('taskUpdateBack', false)) class="hidden" @endif>Update Task</span>
                            <span create @if(session('taskUpdateBack', false))   class="hidden" @endif>Create new task</span>
                        </button>

                    </form>
                </div>


            </div>

        </div>
    </div>

</div>
</div>
