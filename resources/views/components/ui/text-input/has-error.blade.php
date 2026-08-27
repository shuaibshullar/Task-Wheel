@props([ 'title', 'name', 'msg' => null, 'input-class' => '', 'x-ref' => null ])

<div class="contents"
    @isset(${'x-ref'})
        x-ref="{{ ${'x-ref'} }}"
        x-init="$refs[@js( ${'x-ref'} )] = $el.firstElementChild"
    @endisset
>
<div {{ $attributes->class('group') }} x-id="['id']" :id="$id('id')"
    x-data="{
        defultErrorMsg: @js($msg ?? '').trim(),
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
        setValue(value) {
            this.Input.value = value;
            this.input(this.Input.value);
        },
        clear() {
            this.Input.value = null;
            this.valid       = false;
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

    <input {{ $attributes->except([ 'value', 'name', 'class', 'type' ]) }} x-init="Input = $el"
        type="text" name="{{ $name }}" value="{{ old($name) }}" @input="input($el.value)"
        class="w-full px-4 py-3 h-11 input focus:focus-input! group-has-[p.show]:error-input! {{ ${'input-class'} }}"
    >

    <p class="@error($name) show @enderror mt-1.5 hidden [.show]:block error-text">
        @if ($errors->has($name))
            {{ $errors->first($name) }}
        @else
            {{ $msg }}
        @endif
    </p>

</div>
</div>
