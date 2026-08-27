@props([ 'input-class' => '', 'title', 'name', 'msg' => null, 'x-ref' => null ])

<div {{ $attributes->class('min-w-46! group') }}
    @isset(${'x-ref'})
        x-ref="{{ ${'x-ref'} }}"
        x-init="$refs[@js( ${'x-ref'} )] = $el.querySelector('& > div')"
    @endisset
>


<label class="block text-sm font-medium text-zinc-400 mb-2">{{ $title }}</label>
<div class="w-full flex justify-around items-center px-3 py-2.5 h-11 overflow-hidden input focus-within:focus-input! group-has-[p.show]:error-input! {{ ${'input-class'} }}"
    {{ $attributes->except(['name', 'type', 'class', 'value']) }}
    x-id="['id']" :id="$id('id')"
    x-data="{
        _day: '',
        set day(val)   { this._day = String(val)    },
        get day()      { return this._day           },

        _month: '',
        set month(val) { this._month = String(val)  },
        get month()    { return this._month         },

        _year: '',
        set year(val)  { this._year = String(val)   },
        get year()     { return this._year          },


        oldDate: @js(old($name)),
        getDateValue() {
            if (this.oldDate)
            {
                $nextTick(() => {
                    this.syncFromPicker(this.oldDate);
                });

                return this.oldDate;
            }


            if (this.day   === '' || this.month === '' || this.year  === '')
            {
                return '';
            }

            return `${ this.year }-${ this.month.padStart(2, '0') }-${ this.day.padStart(2, '0') }`;
        },
        setValue(value) {
            this.syncFromPicker(value);
        },
        clear() {
            {{-- For remove error --}}
                this.input(true);
            {{--  --}}
            this.syncFromPicker(null);
            this.valid = false;
            this.input();
        },


        date: new Date(),

        syncToPicker() {
            let d = this.day.padStart(2, '0');
            let m = this.month.padStart(2, '0');
            let y = this.year;
            if (y.length === 4 && m > 0 && m <= 12 && d > 0 && d <= 31)
            {
                if (this.isValidDate(y, m, d)) this.$refs.picker.value = `${y}-${m}-${d}`;
                else this.syncFromPicker(this.$refs.picker.value);
            }

            this.input();
        },

        syncFromPicker(val) {
            if (!val)
            {
                this.day   = '';
                this.month = '';
                this.year  = '';


                this.$refs.pickerButton.blur();
                return;
            }
            const [y, m, d] = val.split('-');
            this.year = y;
            this.month = m;
            this.day = d;

            this.handleDayInput(false);
            this.handleMonthInput(false);
            this.handleYearInput(false);


            this.$refs.pickerButton.blur();
        },

        handleDayInput(fromInput = true, blur = false) {
            this.day = this.day.replace(/\D/g, '');

            if ( this.day.length >= 2 || ( blur && this.day != '' ) )
            {
                if (this.day <= 0) this.day = '01';
                if (this.day > 31) this.day = '31';

                if (blur) this.day = this.day.padStart(2, '0');

                if (fromInput)
                {
                    this.$refs.monthInput.focus();
                    this.$refs.monthInput.select();
                }
            }

            this.syncToPicker();
        },

        handleMonthInput(fromInput = true, blur = false) {
            this.month = this.month.replace(/\D/g, '');

            if ( this.month.length >= 2 || ( blur && this.month != '' ) )
            {
                if (this.month <= 0) this.month = '01';
                if (this.month > 12) this.month = '12';

                if (blur) this.month = this.month.padStart(2, '0');

                if (fromInput)
                {
                    this.$refs.yearInput.focus();
                    this.$refs.yearInput.select();
                }
            }

            this.syncToPicker();
        },

        handleYearInput(fromInput = true, blur = false) {
            this.year = this.year.replace(/\D/g, '');

            const maxYear = this.date.getFullYear() + 1;
            const minYear = this.date.getFullYear();

            if (this.year > maxYear) this.year = maxYear;
            if ( this.year.length >= 4 || ( blur && this.year != '' ) )
            {
                if (this.year < minYear) this.year = minYear;
                if (fromInput) this.$refs.yearInput.blur();
            }

            this.syncToPicker();
        },

        isValidDate(year, month, day) {
            const y = parseInt(year, 10);
            const m = parseInt(month, 10) - 1;
            const d = parseInt(day, 10);

            const date = new Date(y, m, d);

            return date.getFullYear() === y
                    && date.getMonth() === m
                    && date.getDate() === d;
        },



        {{-- Error logic --}}
        defultErrorMsg: @js($msg ?? '').trim(),
        form: null,
        pInput: null,
        valid: false,

        input(removeError = false) {

            this.valid =  ! ( this.day   == ''  ||  this.day   == 0 )
                       && ! ( this.month == ''  ||  this.month == 0 )
                       && ! ( this.year  == ''  ||  this.year  == 0 )

                       && this.isValidDate( this.year, this.month, this.day )
                       && this.$refs.picker.checkValidity();


            if (this.valid || removeError)
            {
                this.pInput.classList.remove('show');
                this.pInput.textContent = this.defultErrorMsg;
            }
        },
    }"
    x-init="
        $nextTick(() => {
            form   = document.querySelector(`form:has(#${$id('id')})`);
            pInput = $el.parentElement.querySelector('p');

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

    {{-- 1. حقل اليوم (قابل للكتابة والتعديل) --}}
    <input type="text" inputmode="numeric"
            x-model="day"
            x-ref="dayInput"
            @input="handleDayInput()"
            @blur="handleDayInput(false, true)"
            @focus="$el.select()"
            placeholder="DD"
            maxlength="2"
            class="w-7 bg-transparent text-center font-mono text-sm text-white focus:outline-none selection:bg-indigo-500">

    <span class="text-zinc-600 font-bold select-none">/</span>

    {{-- 2. حقل الشهر (قابل للكتابة والتعديل) --}}
    <input type="text" inputmode="numeric"
            x-model="month"
            x-ref="monthInput"
            @input="handleMonthInput()"
            @blur="handleMonthInput(false, true)"
            @focus="$el.select()"
            placeholder="MM"
            maxlength="2"
            class="w-7 bg-transparent text-center font-mono text-sm text-white focus:outline-none selection:bg-indigo-500">

    <span class="text-zinc-600 font-bold select-none">/</span>

    {{-- 3. حقل السنة (قابل للكتابة والتعديل) --}}
    <input type="text" inputmode="numeric"
            x-model="year"
            x-ref="yearInput"
            @input="handleYearInput()"
            @blur="handleYearInput(false, true)"
            @focus="$el.select()"
            placeholder="YYYY"
            maxlength="4"
            class="w-12 bg-transparent text-center font-mono text-sm text-white focus:outline-none selection:bg-indigo-500">


    {{-- 4. فاصل وأيقونة فتح التقويم المنسدل --}}
    <div class="flex justify-center items-center pl-2 ml-2 border-l border-zinc-800">
        <button type="button"
                x-ref="pickerButton"
                @click="$refs.picker.showPicker()"
                class="text-zinc-400 hover:text-indigo-400 focus:text-indigo-400 transition p-1 rounded-md hover:bg-zinc-800 focus:bg-zinc-800 focus:outline-none cursor-pointer select-none"
                title="افتح التقويم">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
            </svg>
        </button>

        {{-- تقويم مخفي للمزامنة والاستدعاء فقط --}}
        <input type="date"
            inert
            x-ref="picker"
            value=""
            @change="syncFromPicker($event.target.value)"
            class="sr-only">
    </div>

    {{-- حقل مخفي مجهز لإرسال النتيجة مع الفورم بالصيغة القياسية YYYY-MM-DD --}}
    <input name="{{ $name }}" type="hidden" :value="getDateValue">

</div>
<p class="@error($name) show @enderror mt-1.5 hidden [.show]:block error-text">
    @if ($errors->has($name))
        {{ $errors->first($name) }}
    @else
        {{ $msg }}
    @endif
</p>


</div>
