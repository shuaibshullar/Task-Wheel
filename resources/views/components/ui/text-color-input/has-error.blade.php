@props([ 'title', 'name', 'color-name', 'msg' => null, 'input-class' => '', 'x-ref' => null ])

<div class="contents"
    @isset(${'x-ref'})
        x-ref="{{ ${'x-ref'} }}"
        x-init="$refs[@js( ${'x-ref'} )] = $el.firstElementChild"
    @endisset
>
<div {{ $attributes->class('group') }} x-id="['id']" :id="$id('id')"
    x-data="{
        defultErrorMsg: @js($msg ?? '').trim(),
        color: null,
        form: null,
        Input: null,
        pInput: null,
        valid: false,
        hasShowClass: false,

        input(value) {

            this.pInput.classList.remove('show');
            this.pInput.textContent = this.defultErrorMsg;
            this.valid              = value.trim().length !== 0;

        },
        setValue(value, color = '#000000') {
            this.Input.value = value;

            if (! color) this.color = '#000000';
            else         this.color = color;

            this.input(this.Input.value);
        },
        clear() {
            this.Input.value = null;
            this.valid       = false;
            this.color       = '#000000';
            this.input(this.Input.value);
        },
    }"
    x-init="
        $nextTick(() => {
            form   = document.querySelector(`form:has(#${$id('id')})`);
            pInput = $el.querySelector('p');

            hasShowClass = pInput.classList.contains('show');
            if (! hasShowClass)
            {
                input(Input.value);
            }

            if (defultErrorMsg.length === 0)
                defultErrorMsg = pInput.textContent.trim();
            else
                defultErrorMsg = defultErrorMsg;

            form?.addEventListener('submit', (event) => {
                if (! valid)
                {
                    event.preventDefault();
                    pInput.classList.add('show');
                }
            });
        });
    "
>

    <label class="block text-sm font-medium text-zinc-400 mb-2">{{ $title }}</label>

    <div class="relative group">


        <input {{ $attributes->except([ 'value', 'name', 'class', 'type' ]) }} x-init="Input = $el"
            type="text" name="{{ $name }}" value="{{ old($name) }}" @input="input($el.value)"
            class="w-full pl-4 pr-12 py-3 h-11 input focus:focus-input! group-focus-within:focus-input! group-has-[p.show]:error-input! {{ ${'input-class'} }}"
        >

        <label>
            <div class="absolute right-0 top-1/2 -translate-y-1/2 cursor-pointer p-4">
                <input type="color" name="{{ ${'color-name'} }}" class="sr-only"
                    x-model="color"
                    x-init="color = $el.value"
                    @if( old( ${'color-name'} , null) ) value="{{ old( ${'color-name'} ) }}" @endif
                >

                <div class="rounded-full w-5 h-5 border-2 border-white" :style="color ? `background-color: ${color}` : ''"
                    @if( old( ${'color-name'} , null) ) style="background-color: {{ old( ${'color-name'} ) }}" @endif
                ></div>

            </div>
        </label>


    </div>

    <p class="@error($name) show @enderror mt-1.5 hidden [.show]:block error-text">
        @if ($errors->has($name))
            {{ $errors->first($name) }}
        @else
            {{ $msg }}
        @endif
    </p>

</div>
</div>
