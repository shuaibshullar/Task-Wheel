@props([ 'title', 'name', 'input-class' => '', 'x-ref' => null ])

<div class="contents"
    @isset(${'x-ref'})
        x-ref="{{ ${'x-ref'} }}"
        x-init="$refs[@js( ${'x-ref'} )] = $el.firstElementChild"
    @endisset
>
<div {{ $attributes->class('group') }} x-id="['id']" :id="$id('id')"
    x-data="{
        textarea: null,
        setValue(value) {
            this.textarea.textContent = value;
        },
        clear() {
            this.textarea.textContent = null;
        },
    }"
>

    <label class="block text-sm font-medium text-zinc-400 mb-2">{{ $title }}</label>

    <textarea type="text" name="{{ $name }}" x-init="textarea = $el"
        {{ $attributes->except([ 'name', 'type', 'class' ]) }}
        class="w-full resize-none field-sizing-content min-h-31 contain-layout px-4 py-3 input focus:focus-input! {{ ${'input-class'} }}"

    >{{ old($name, $slot) }}</textarea>

</div>
</div>
