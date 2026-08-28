@props([ 'id' => 'detail-modal', 'edit-task-id' ,'x-ref' => null, 'data-id' => null ])
@php($isAdmin = Gate::allows('is-admin'))
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
    @if($isAdmin)
        id: null,
    @endif
        title: null,
        category: null,
        description: null,
        deadline: null,
        assignees: null,
        assigneesButton: null,
    @if($isAdmin)
        delSpan: null,
    @endif


    @if($isAdmin)
        form: $el.querySelector('& form'),
    @endif
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
                this.clearEvent = true;

        },
        clearEvent: false,
        clearModal()
        {
        @if($isAdmin)
            this.isConfirmed = false;
        @endif
            this.changeValues(null, null, null, null, null, null);
        },
        checkbox(check = null) {
            if (check !== null)
                this.input.checked = check;
            else
                this.input.checked = ! this.input.checked;

            this.change();
        },
        changeValues(id, title, description = null, category, deadline, assignees = null)
        {
        @if($isAdmin)
            this.id.value                    = id;
        @endif
            this.title.textContent           = title;
            this.category.textContent        = category;

            if (description !== null)
            {
                this.description.classList.remove('hidden');
                this.description.textContent = description;

            } else {

                this.description.classList.add('hidden');
                this.description.textContent = null;
            }

            this.deadline.textContent        = deadline;
            this.makeAssigneesDiv(assignees);
        },
        makeAssigneesDiv(assignees = null)
        {
            this.assignees.innerHTML = '';

            if (assignees === null)
            {
                const button = this.assigneesButton.cloneNode(true);
                button.textContent = 'Unassigned';

                this.assignees.appendChild(button);
            }
            else
            {
                for (const name of assignees)
                {
                    const button = this.assigneesButton.cloneNode(true);
                    button.textContent = name;

                    this.assignees.appendChild(button);
                }
            }
        },
    @if($isAdmin)
        isConfirmed: false,
        confirmDelSpan(isConfirmed)
        {
            if (isConfirmed === null) return;

            for (const child of this.delSpan.children)
            {
                child.classList.add('hidden');
            }

            if (isConfirmed)
                this.delSpan.querySelector('& > :is([confirm])').classList.remove('hidden');
            else
                this.delSpan.querySelector('& > :is([delete])' ).classList.remove('hidden');
        },
    @endif



        {{-- Public methods --}}
        open(id , title, description = null, category, deadline, assignees = null)
        {
            this.changeValues(id, title, description, category, deadline, assignees);
        @if($isAdmin)
            this.isConfirmed = false;
        @endif
            this.checkbox(true);
        },
        close() {
            this.checkbox(false);
        },
    }"
    x-init="
        $nextTick(() => {
        @if($isAdmin)
            id                 =   $refs.id;
        @endif
            title              =   $refs.title;
            category           =   $refs.category;
            description        =   $refs.description;
            deadline           =   $refs.deadline;
            assignees          =   $refs.assignees;
        @if($isAdmin)
            delSpan            =   $refs.delSpan;
        @endif

            assigneesButton.remove();
            assigneesButton.removeAttribute('x-init');
            assigneesButton.classList.remove('hidden');
        });

    @if($isAdmin)
        $watch('isConfirmed', (value) => confirmDelSpan(value));
    @endif

        modal = div.querySelector('& > div');
        if (input.checked)
            change();

    @if($isAdmin)
        form?.addEventListener('submit', (event) => {
            if (! isConfirmed)
            {
                event.preventDefault();
                isConfirmed = true;
            }
        });
    @endif

        //
    "
    x-on:transitionend="
        if ($event.propertyName === 'translate' && clearEvent) {  clearModal(); clearEvent = false  }
        if ($event.propertyName === 'translate' && closeEvent) {  enablePageScroll(/* this.div */); closeEvent = false  }
    "
>

    <input type="checkbox" class="hidden peer" id="{{ $id }}" @change.self="change">

    <div class="fixed inset-0 p-8 flex items-center-safe justify-center-safe antialiased selection:bg-indigo-500/30 selection:text-white bg-black/70 backdrop-blur-sm z-999 opacity-0 pointer-events-none transition-all peer-checked:opacity-100 peer-checked:pointer-events-auto cursor-auto overflow-auto overscroll-contain
        duration-600 ease-out
        not-peer-checked:*:-translate-y-1/6 peer-checked:*:translate-0 *:transition-all *:duration-600 *:ease-out"

        @mousedown.self="outside = ! modal.contains($event.target)"
        @mouseup.self="if ( outside && ! modal.contains($event.target) ) checkbox(false)"
        @keydown.escape.window="checkbox(false)"
    >
        <div class="w-full max-w-xl bg-zinc-900/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-8 shadow-2xl shadow-black/60 relative overflow-show z-998">

            <div class="absolute top-0 inset-x-0 h-px bg-linear-to-r from-transparent via-zinc-700/50 to-transparent"></div>
            <div class="space-y-3">


                {{-- Header --}}
                <div class="select-none">
                    <div class="flex items-start justify-between gap-2">
                        <div class="text-white flex items-center gap-2 flex-1 shrink-0 select-text">


                            <div class="min-w-0 w-full">
                                <span x-ref="category" class="rounded-md bg-rose-600 px-3 pt-1 pb-1.5 min-w-0 max-w-1/2 truncate text-sm tracking-wider font-medium selection:bg-rose-400 selection:text-black!">
                                    Category Name
                                </span>
                                <p x-ref="title" class="text-xl font-bold mt-2 px-2 py-1 min-w-0 max-w-1/2 truncate tracking-wider">
                                    Task Name
                                </p>
                            </div>


                        </div>
                        {{-- زر الإغلاق: عبارة عن Label مرتبطة بنفس الـ Checkbox لإلغاء تفعيله --}}
                        <label for="{{ $id }}" class="p-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg cursor-pointer transition-colors shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </label>
                    </div>
                </div>

                <hr class="select-none text-zinc-500/50">

                <div class="space-y-4 [&>hr]:text-zinc-500/50 [&>hr]:select-none mt-5 [&>div[description]:has(p.hidden)]:[&,&~hr[description]]:hidden">





                    <div description class="w-full min-w-0">
                        <label class="block text-sm font-medium text-zinc-400 mb-2">Description</label>
                        <p x-ref="description" class="border-s-2 border-zinc-500 ps-2 min-w-0 overflow-hidden text-wrap text-ellipsis">
                            Hello my name is .....
                            Welcome to may chanellddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd
                            dddddddddddddddddddddddddddddddddddddddd
                        </p>
                    </div>

                    <hr description>




                    <div class="grid grid-cols-[max-content_1fr] grid-rows-[max-content_max-content] items-center gap-2 min-w-0 max-w-full">

                        <svg class="w-4 h-4 col-start-1 row-start-1 text-sm font-medium text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <label class="block text-sm font-medium text-zinc-400 col-start-2 row-start-1">Deadline</label>


                        <span x-ref="deadline" class="text-base col-start-2 row-start-2 font-mono text-white min-w-0 truncate">Hello 09</span>

                    </div>


                    <hr>


                    <div class="grid grid-cols-[max-content_1fr] grid-rows-[max-content_max-content] items-center gap-2 min-w-0 max-w-full">


                        <svg class="w-4 h-4 col-start-1 row-start-1 text-sm font-medium text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="7" r="4"/>
                            <path d="M5 21c0-4 3-7 7-7s7 3 7 7"/>
                        </svg>
                        <label class="block text-sm font-medium text-zinc-400 col-start-2 row-start-1">Assigned personnel</label>

                        <div x-ref="assignees" class="col-start-2 row-start-2 flex flex-row flex-wrap items-center gap-3 min-w-0">


                            <div x-init="assigneesButton = $el" class="bg-rose-900/70 shadow-sm shadow-rose-900/20 px-4 pt-1 pb-1.5 rounded-lg text-sm tracking-wider font-medium text-white min-w-0 truncate max-w-30 selection:bg-rose-400 selection:text-black! text-center align-middle hidden"></div>


                        </div>

                    </div>


                    @if($isAdmin)



                        <hr>


                        <div class="flex items-center justify-between">

                            <button id="{{ ${'edit-task-id'} }}" class="flex items-center gap-2 text-slate-300 hover:text-white tracking-wide hover:bg-slate-800 px-3 py-2 rounded-xl text-base font-medium cursor-pointer transition-all duration-200 select-none">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                </svg>
                                <span>Edit task</span>
                            </button>

                            <form method="POST" action="{{ route('delete_task') }}">
                                @csrf
                                <input x-ref="id" type="hidden" name="id" autocomplete="off">
                                <button type="submit"
                                    class="flex items-center gap-2 text-rose-500 hover:text-rose-600 tracking-wide hover:bg-slate-800 px-3 py-2 rounded-xl text-base font-medium cursor-pointer transition-all duration-200 select-none"
                                >
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="4" y1="7" x2="20" y2="7"/>
                                        <path d="M6 7l1 13c0 1 1 2 2 2h6c1 0 2-1 2-2l1-13"/>
                                        <path d="M9 7V4c0-1 1-2 2-2h2c1 0 2 1 2 2v3"/>
                                    </svg>

                                    <div x-ref="delSpan" class="contents italic">
                                        <span delete>Decommission task</span>
                                        <span confirm class="hidden">Confirm decommission</span>
                                    </div>

                                </button>
                            </form>
                        </div>



                    @endif

                </div>

            </div>


        </div>

    </div>

</div>
</div>
