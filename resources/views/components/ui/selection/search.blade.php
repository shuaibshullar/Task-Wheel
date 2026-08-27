@props([ 'title', 'name', 'input-class' => '', 'msg' => null, 'overflow' => true, 'x-ref' => null ])

<div class="contents"
    @isset(${'x-ref'})
        x-ref="{{ ${'x-ref'} }}"
        x-init="$refs[@js( ${'x-ref'} )] = $el.firstElementChild"
    @endisset
>
<div {{ $attributes->class('group z-100  min-w-0') }} x-id="['id']" :id="$id('id')"
    :class="{ 'open': isOpen }"
    x-data="{
        outside: false,
        isOpen: false,
        arrowButton: null,
        choicesDid: null,
        choice: new Set(),
        choices: {},
        search(query, text) {
            const pattern = query.toLowerCase().split('').join('.*');
            return new RegExp(pattern).test(text.toLowerCase());
        },
        checkbox(value, check) {
            const checkBox = this.choices[value.toLowerCase()]?.querySelector('& input');
            if (! checkBox) return;
            const data     = Alpine.$data(checkBox);

            checkBox.checked = check;
            data.init();
        },
        clear() {

            for (const [key, choice] of Object.entries(this.choices))
            {
                this.checkbox(key, false);
            }

            {{-- For remove error --}}
                {{-- this.choice = (new Set()).add('ee'); --}}
                {{-- this.input('', true); --}}
            {{--  --}}

            this.choice         = new Set();
            this.oldInputValue  = null;
            this.InputValueJoin = null;
            this.Input.value    = null;
            // this.valid          = false;
            this.isOpenChange(false);
            this.input('', true);

        },
        setValues(values) {

            for (const value of values)
            {
                this.checkbox(value, true)
            }

            {{-- اضيفت هذه الجملة لحل مشكلة عندما يفشل الارسال من طرف السيرفر وبعدها نستخدم هذه الدالة --}}
            this.Input.value = null;
            this.isOpenChange(false);

        },


        defultErrorMsg: @js($msg ?? '').trim(),
        form: null,
        Input: null,
        // pInput: null,
        // valid: false,
        // hasShowClass: false,
        oldInputValue: null,
        InputValueJoin: null,

        input(value, skip = false) {

            // this.valid = Boolean(this.choice.size);
            // if (this.valid) {
            //     this.pInput.classList.remove('show');
            //     this.pInput.textContent = this.defultErrorMsg;
            // } else if (this.hasShowClass) {
            //     this.pInput.classList.add('show');
            // }


            if (! this.isOpen && this.choice.size !== 0 && ! skip)
            {
                this.Input.value = this.InputValueJoin;
                return;
            }


            for (const [choiseValue, el] of Object.entries(this.choices))
            {
                const search = this.search(value, choiseValue);

                if (search) {
                    this.choicesDid.appendChild(el);
                } else {
                    el.remove();
                }
            }
        },

        check() {
            this.input(this.Input.value, true);
        },

        isOpenChange(value) {
            if (! this.isOpen && this.choice.size !== 0)
            {
                this.InputValueJoin  = Array.from(this.choice).join(' , ');

                this.oldInputValue   = this.Input.value;
                this.Input.value     = this.InputValueJoin;
                this.input('', true);
            }

            if (this.isOpen &&  this.choice.size !== 0)
            {
                this.InputValueJoin  = '';
                this.Input.value     = this.oldInputValue;
                this.oldInputValue   = '';
                this.input(this.Input.value, true);
            }
        },




        animationId: null,
        end: false,
        scroll(start) {

            if (start && this.isOpen) {

                this.end = false;
                this.animationId = requestAnimationFrame(step = () => {

                    this.choicesDid.scrollIntoView({ block: 'center', inline: 'nearest' });
                    if (! this.end)
                        this.animationId = requestAnimationFrame(step);

                });

            } else {

                this.end = true;
                cancelAnimationFrame(this.animationId);

            }
        },
    }"
    x-init="
        $nextTick(() => {
            // form   = document.querySelector(`form:has(#${$id('id')})`);
            // pInput = $el.querySelector('p');


            // hasShowClass = pInput.classList.contains('show');


            // if (defultErrorMsg.length === 0)
            //     defultErrorMsg = pInput.textContent.trim();
            // else
            //     defultErrorMsg = defultErrorMsg;


            // form?.addEventListener('submit', (event) => {
            //     if (! valid)
            //     {
            //         event.preventDefault();
            //         pInput.classList.add('show');
            //     }
            // });


            isOpenChange(false);

            input('', true);

        });

        $watch('isOpen', (value) => isOpenChange(value));
    "
    @transitionstart="if ($event.propertyName === 'height') $nextTick(() => scroll(true))"
    @transitionend="if ($event.propertyName === 'height') $nextTick(() => scroll(false))"
>

    <input class="hidden peer" type="checkbox" :checked="isOpen">

    <label class="block text-sm font-medium text-zinc-400 mb-2">{{ $title }}</label>

    <div class="@if($overflow) h-11 @endif w-full overflow-visible"
        @mousedown="outside = false"
        @mousedown.outside="outside = true"
        @mouseup.outside="if(outside) isOpen = false"
        @focusin.window="if (! $el.contains($event.target)) isOpen = false"
    >
        <div class="hidden [&~div_*:where(svg,.dropdown)]:duration-500 [&~div_*:where(svg,.dropdown)]:ease-out [&~div_*:where(svg,.dropdown)]:transition-all"></div>
        <div class="contain-layout w-full h-auto overflow-hidden group input group-[&:where(div.open)]:focus-input! group-has-[p.show]:error-input! transition-all text-white {{ ${'input-class'} }}"
            x-data="{isFinish: true}"
            @transitionstart ="$nextTick( () => {   if ($event.propertyName === 'height')             isFinish = false   })"
            @transitionend   ="$nextTick( () => {   if ($event.propertyName === 'height' && ! isOpen) isFinish = true    })"
            :class="{'not-group-has-[input.peer:checked]:h-11' : isFinish && ! isOpen}"
        >
            <div class="relative pl-4 pr-12 py-3 cursor-text h-11 w-full"
                x-init="arrowButton = $el.querySelector('& > div')"
                @click="if (! arrowButton.contains($event.target)){ $el.querySelector('& > input').focus(); isOpen = true; }"
            >
                <input {{ $attributes->except([ 'value', 'name', 'class', 'type' ]) }}
                    type="text" x-init="Input = $el"
                    @input="input($el.value)"
                    class="w-full h-full antialiased text-sm outline-none"
                    autocomplete="off"
                >

                <div class="absolute right-0 top-1/2 -translate-y-1/2 cursor-pointer p-4" @click="isOpen = ! isOpen">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="-rotate-90 h-4 w-4 group-[&:where(div.open)]:rotate-0"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>

            <div class="group-[&:where(div.open)]:h-50 h-0 dropdown overflow-hidden"
                x-data="{ isFinished : false }"
                @transitionend.self="if ($event.propertyName === 'height') isFinished = true"
                @transitionstart.self="if ($event.propertyName === 'height') isFinished = false"
                :inert="! isOpen"
            >
            <div :class="isFinished ? 'overflow-auto!' : 'overflow-hidden!'" x-init="choicesDid = $el"
                class="w-full h-full px-3 py-3 overflow-auto scrollbar-thin scrollbar-thumb-zinc-600 scrollbar-track-transparent overscroll-contain border-t-2 border-zinc-500/50
                *:not-last:border-b-2 *:first:pt-0 *:last:pb-5 *:py-2 *:border-zinc-500/50"
            >


                {{ $slot }}




            </div>
            </div>
        </div>

    </div>


    {{-- <p class="@error($name) show @enderror mt-1.5 hidden [.show]:block error-text">
        @if ($errors->has($name))
            {{ $errors->first($name) }}
        @else
            {{ $msg }}
        @endif
    </p> --}}

</div>
</div>
