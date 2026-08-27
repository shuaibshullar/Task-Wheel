@props([ 'id' => 'add-category-modal', 'x-ref' => null, 'data-id' => null ])
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
        category: null,
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
                this.category     .clear();
            }
        },
        checkbox(check = null) {
            if (check !== null)
                this.input.checked = check;
            else
                this.input.checked = ! this.input.checked;

            this.change();
        },
        changeValues(id = null, category = null, color = null)
        {
            this.id.value = id;
            category  &&  this.category.setValue(category, color);
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
        open(id = null, category = null, color = null, buttonValue = null) {
            if (id === null)
            {
                this.checkbox(true);
                return;
            }

            this.changeValues(id, category, color);
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
            category      = Alpine.$data( $refs.category );
        });
        modal = div.querySelector('& > div');
        if (input.checked)
            change();

    "
    x-on:transitionend="if ($event.propertyName === 'translate' && closeEvent) {  enablePageScroll(/* this.div */); closeEvent = false  }"
>

    <input type="checkbox" class="hidden peer" id="{{ $id }}" @change.self="change" @checked(session('categoryCreateBack', false) || session('categoryUpdateBack', false))>

    <div class="fixed inset-0 p-8 flex items-center-safe justify-center-safe antialiased selection:bg-indigo-500/30 selection:text-white bg-black/70 backdrop-blur-sm z-999 opacity-0 pointer-events-none transition-all peer-checked:opacity-100 peer-checked:pointer-events-auto cursor-auto overflow-auto overscroll-contain
        duration-600 ease-out
        not-peer-checked:overflow-hidden not-peer-checked:*:-translate-y-1/6 peer-checked:*:translate-0 *:transition-all *:duration-600 *:ease-out"

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


                            <div create class="@if(session('categoryUpdateBack', false)) hidden @else contents @endif">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    class="h-4 w-4"
                                >
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                <span>Add Category</span>
                            </div>


                            <div update class="@if(session('categoryUpdateBack', false)) contents @else hidden @endif">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                <span>Update Category</span>
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



                    <form method="POST" action="{{ route('add_and_edit_cat') }}" class="flex flex-col justify-center items-center *:w-full gap-y-6 py-5"
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


                        <x-ui.text-color-input.has-error

                            x-ref="category"
                            class="w-full"
                            input-class="bg-zinc-900! border-zinc-700! placeholder-zinc-400!"
                            title="Category Name"
                            placeholder="Enter category name ...."
                            name="category"
                            color-name="color"
                            msg="Give the category a name."

                        />


                        <button type="submit" class="cursor-pointer w-full bg-rose-600 hover:bg-rose-500 text-white font-medium py-3 px-4 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-rose-600/20 hover:shadow-rose-600/30"
                            x-init="$el.addEventListener('animationend', () => $el.classList.remove('animate-shake')); button = $el"
                        >
                            <span update @if(! session('categoryUpdateBack', false)) class="hidden" @endif>Update category</span>
                            <span create @if(session('categoryUpdateBack', false))   class="hidden" @endif>Create new category</span>
                        </button>

                    </form>
                </div>


            </div>

        </div>






    </div>


</div>
</div>
