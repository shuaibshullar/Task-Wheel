@props(['value', 'color' => null])
@aware(['name'])
@use('\Illuminate\Support\HtmlString')

<div x-init="choices[$el.querySelector('& input').value.toLowerCase()] = $el">
    <label class="flex items-center gap-3 p-2 rounded-md cursor-pointer select-text transition-colors group/radio hover:bg-slate-700/30">
        <div class="relative flex items-center justify-center">
            <input type="radio" name="{{ $name }}" value="{{ $value }}" class="peer sr-only"
                @change="if ($el.checked) choice = $el.value"
                x-data="{
                    init() {
                        if ($el.checked) choice = $el.value
                    }
                }"
                @checked(old($name) === $value)
            >
            <div class="w-4 h-4 rounded-full border-white border-2 bg-transparent transition-all duration-200 ease-out peer-checked:bg-indigo-600 peer-checked:border-indigo-600"></div>
            <div class="absolute w-1.5 h-1.5 rounded-full bg-white transform scale-0 peer-checked:scale-100 peer-focus-visible:scale-100 peer-focus-visible:peer-checked:scale-50 transition-transform duration-200 ease-out"></div>
        </div>

        @php(ob_start())

            <span class="text-sm text-zinc-200 group-hover/radio:text-white truncate font-medium">
                {{ $value }}
            </span>

        @php($text = app( HtmlString::class, [ 'html' => ob_get_clean() ] ))

        @isset($color)

            <div class="flex items-center justify-between gap-2 flex-1 min-w-0">
                {{ $text }}

                <div class="rounded-full border-white border-2 h-3.5 w-3.5 shrink-0" style="background-color: {{ $color }}"></div>
            </div>

        @else

            {{ $text  }}

        @endisset

    </label>
</div>
