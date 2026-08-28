<div class="contents" x-data="{sidebarCheckbox: null}">



    <input type="checkbox" id="toggle-sidebar" class="peer hidden"
           x-init="sidebarCheckbox = $el"
           @change="if($el.checked) disablePageScroll()">

    <label for="toggle-sidebar" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-999 opacity-0 pointer-events-none transition-opacity duration-900 will-change-auto ease-in-out peer-checked:opacity-100 peer-checked:pointer-events-auto cursor-auto"></label>

    <aside class="flex flex-col fixed top-0 right-0 z-1000 h-screen w-120 bg-zinc-900 border-l border-zinc-800/80 shadow-2xl transform translate-x-full transition-all will-change-transform duration-900 ease-in-out peer-checked:translate-x-0"
           @transitionend="if(! sidebarCheckbox.checked && $event.propertyName === 'translate') enablePageScroll()"
    >

        {{-- رأس السايد بار وزر الإغلاق --}}
        {{-- <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                قائمة المهام
            </h3> --}}
            {{-- زر الإغلاق: عبارة عن Label مرتبطة بنفس الـ Checkbox لإلغاء تفعيله --}}
            {{-- <label for="toggle-sidebar" class="p-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg cursor-pointer transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </label>
        </div> --}}

        {{-- محتوى السايد بار (المهام) --}}
        {{-- <div class="space-y-3">
        <div class="p-3.5 rounded-xl bg-zinc-950/50 border border-zinc-800 text-sm text-zinc-300">
            📌 مراجعة قاعدة البيانات اليوم الساعة 4
        </div>
        <div class="p-3.5 rounded-xl bg-zinc-950/50 border border-zinc-800 text-sm text-zinc-300">
            🎨 إنهاء تعديلات ألوان الـ Navbar
        </div>
        <div class="p-3.5 rounded-xl bg-zinc-950/50 border border-zinc-800 text-sm text-zinc-300">
            🚀 رفع التحديثات الجديدة إلى السيرفر
        </div>
        </div> --}}






        @php
            /**
             * use same number in margin and padding.
             * This values with affect mainly on the padding of sidebar from edge to the content.
             * you can set custom value like "bottom".
             * Make sure to apply it to the element bellow.
             *
             * @var object{margin:string, padding:string, bottom:string} $sidebar_padding
             */
            $sidebar_padding = (object) [
                'margin'  => 'm-6',
                'padding' => 'p-6',

                'bottom'  => 'pb-15',
            ]
        @endphp
        {{-- عنوان السايد منيو والإحصائيات  --}}
        <div class="flex items-center justify-between gap-2 {{ $sidebar_padding->margin }} mb-4 border-b-2 border-zinc-700 pb-3 select-none shrink-0">

                <h2 class="text-base font-bold text-white flex items-center gap-2 h-9">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Upcoming deadlines
                </h2>
                <label for="toggle-sidebar" class="p-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg cursor-pointer transition-colors h-9 flex justify-center items-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </label>

        </div>


        {{-- فلاتر سريعة  --}}
        {{-- <div class="flex gap-2 mb-6">
        <button class="flex-1 py-1.5 text-xs font-medium rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-600/10 cursor-pointer">الكل</button>
        <button class="flex-1 py-1.5 text-xs font-medium rounded-lg bg-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-700/50 transition-colors cursor-pointer">المعلقة</button>
        <button class="flex-1 py-1.5 text-xs font-medium rounded-lg bg-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-700/50 transition-colors cursor-pointer">المنتهية</button>
        </div> --}}

        {{-- قائمة المهام المرتبة (Task Items) --}}
        <div id="upcoming-div" class="space-y-4 overflow-auto {{ $sidebar_padding->padding }} {{ $sidebar_padding->bottom }} pt-2 h-full min-h-0 scrollbar-thin scrollbar-thumb-zinc-700 scrollbar-track-transparent overscroll-contain">

            <span id="upcoming-nothing-span" class="{{-- block --}} hidden w-full select-none cursor-default text-white text-lg font-medium font-serif text-center tracking-widest text-wrap whitespace-pre-line"></span>

            <div id="upcoming-button" class="group input px-4 py-3 hover:focus-input hover:ring-white! hover:border-white! border-zinc-500! space-y-1 text-zinc-300 cursor-pointer select-none hidden">

                <div category class="flex items-center gap-2">
                    <div class="rounded-full w-3 h-3 border-2 border-white shrink-0"></div>
                    <span class="text-xs font-mono min-w-0 truncate align-middle"></span>
                </div>

                <span title class="block text-white text-lg font-medium font-serif min-w-0 truncate align-middle tracking-widest"></span>

                <div deadline class="flex items-center gap-2">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span class="text-xs font-sans min-w-0 truncate align-middle tracking-wider"></span>
                </div>

            </div>



        </div>



    </aside>


    <div class="select-none sticky top-0 z-30 *:bg-zinc-900/80 *:backdrop-blur-md *:border-b *:border-zinc-800 *:text-white" id="filter-checkbox"
        x-id="['filter-checkbox']"
        x-data="{
            outside: false,
            input: $el.querySelector('& > input'),
            checkbox: null,
            label: $refs.filterLabel,
        }"
        x-init="
            checkbox = (bool) => { input.checked = bool }
        "
    >
        <input class="hidden peer/filter" :id="$id('filter-checkbox')" type="checkbox">
        <div class="hidden peer-checked/filter:[&~nav_svg.filter]:rotate-0 peer-checked/filter:[&~nav_label.lfilter]:text-white peer-checked/filter:[&~nav_label.lfilter]:bg-slate-800 [&~*_.filter,&~*.filter]:duration-700 [&~*_.filter,&~*.filter]:ease-out"></div>
        <nav class="relative z-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    {{-- الجانب الأيمن: الشعار والروابط الرئيسية --}}
                    <div class="flex items-center space-x-8 rtl:space-x-reverse">
                        {{-- الشعار (Logo) --}}
                        <div class="shrink-0">
                            <a href="{{ route('home') }}" class="text-xl font-bold tracking-wider text-indigo-400 hover:text-indigo-300 transition-colors duration-200">
                                TASK WHEEL
                            </a>
                        </div>

                        {{-- روابط التنقل (تظهر في الشاشات المتوسطة فما فوق) --}}
                        <div class="hidden md:flex items-center space-x-1 rtl:space-x-reverse">

                            @can('is-admin')
                                <label class="flex justify-center items-center text-center gap-2 text-slate-300 tracking-wide font-mono hover:text-white hover:bg-slate-800 px-2 py-2 rounded-lg text-sm font-medium cursor-pointer transition-all duration-200"
                                    for="entry-modal"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        class="h-4 w-4"
                                    >
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Add Task
                                </label>

                                <label class="flex justify-center items-center text-center gap-2 text-slate-300 tracking-wide font-mono hover:text-white hover:bg-slate-800 px-2 py-2 rounded-lg text-sm font-medium cursor-pointer transition-all duration-200"
                                    for="add-category-modal"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        class="h-4 w-4"
                                    >
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Add Category
                                </label>

                                <a class="flex justify-center items-center text-center gap-2 text-slate-300 hover:text-white tracking-wide font-mono hover:bg-slate-800 px-2 py-2 rounded-lg text-sm font-medium cursor-pointer transition-all duration-200"
                                       href="{{ route('register.admin') }}"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                         class="h-4 w-4"
                                    >
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Add Admin
                                </a>
                            @endcan

                            <label x-ref="filterLabel" :for="$id('filter-checkbox')" id="filter-open-button" class="lfilter @if($tasks === []) hidden @else flex @endif justify-center items-center text-center gap-2 text-slate-300 tracking-wide font-mono hover:text-white hover:bg-slate-800 ms-2 px-3 py-2 rounded-lg text-sm font-medium cursor-pointer transition-all duration-200">
                                Filters
                                <div>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="-rotate-90 filter h-4 w-4 transition-transform will-change-transform"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </div>
                            </label>
                            {{-- <a href="#" class="text-slate-300 hover:text-white hover:bg-slate-800 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200">الخدمات</a> --}}
                            {{-- <a href="#" class="text-slate-300 hover:text-white hover:bg-slate-800 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200">اتصل بنا</a> --}}
                        </div>
                    </div>

                    {{-- الجانب الأيسر: أزرار التحكم (حسب حالة المستخدم) --}}
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">

                        @auth
                            <div class="relative" x-data="{outside: false}" x-id="['input-checkbox']"
                                @mousedown="outside = false"
                                @mousedown.outside="outside = true"
                                @mouseup.outside="if(outside) $el.querySelector('& > input').checked = false"
                                @keydown.escape.window="$el.querySelector('& > input').checked = false"
                            >

                                <input :id="$id('input-checkbox')" class="hidden peer" type="checkbox">

                                <label :for="$id('input-checkbox')" class="flex items-center justify-center text-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800 peer-checked:text-white peer-checked:bg-slate-800 px-3 py-2 rounded-lg text-sm font-medium cursor-pointer select-none transition-all">


                                    <svg class="w-6 h-6 flex items-center justify-center" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <span>My account</span>
                                </label>

                                <div class="space-y-5 min-w-55 w-max max-w-65 select-text absolute right-0 top-full overflow-hidden mt-2 bg-zinc-900 border border-zinc-700 text-zinc-100 rounded-xl shadow-2xl p-4 grid grid-rows-[0fr] not-peer-checked:py-0 not-peer-checked:m-0 not-peer-checked:pointer-events-none not-peer-checked:border-0 peer-checked:grid-rows-[1fr] origin-top-right scale-0 peer-checked:scale-100 transition-all duration-500 ease-out z-100">

                                    <div class="min-w-0 w-full flex justify-between items-center gap-4">
                                        <div class="min-w-0">
                                            <p class="select-all text-sm font-semibold text-white truncate min-w-0">{{ auth()->user()->name }}</p>
                                            <p class="select-all text-xs text-zinc-400 mt-0.5 truncate min-w-0">{{ auth()->user()->email }}</p>
                                        </div>

    {{--                                <a href="#"--}}
    {{--                                    class="shrink-0 flex items-center justify-center text-slate-300 hover:text-white hover:bg-slate-800 peer-checked:text-white peer-checked:bg-slate-800 px-3 py-2 rounded-lg text-sm font-medium cursor-pointer select-none transition-all">--}}
    {{--                                    <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
    {{--                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
    {{--                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />--}}
    {{--                                    </svg>--}}
    {{--                                </a>--}}
                                    </div>

                                    <hr class="select-none text-zinc-500/50">

                                    <form method="post" action="{{ route('logout_post') }}">
                                        @csrf
                                        <button class="select-none flex justify-center items-center gap-2 text-center bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg shadow-rose-600/20 hover:shadow-rose-600/30 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                            </svg>
                                            Logout
                                        </button>
                                    </form>



                                </div>

                            </div>
                        @endauth

                        @guest
                            {{-- حالة 1: زائر غير مسجل الدخول (Guest) --}}
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <a href="{{ route('login_get') }}" class=" text-center text-slate-300 hover:text-white px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg hover:bg-slate-800">
                                    login
                                </a>
                                <a href="{{ route('register_get') }}" class="text-center bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg shadow-rose-600/20 hover:shadow-rose-600/30">
                                    register
                                </a>
                            </div>
                        @endguest


                        <label for="toggle-sidebar" class="ms-5 flex items-center gap-2 justify-center hover:bg-slate-800 text-slate-300 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer">
                            {{-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg> --}}


                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>

                            <span>Tasks</span>

                        </label>


                    </div>
                </div>
            </div>
        </nav>
        <div x-data="{ isFinished : false }" :class="isFinished ? 'overflow-auto' : 'overflow-hidden'"
            @transitionend.self="if ($event.propertyName === 'height') isFinished = true"
            @transitionstart.self="if ($event.propertyName === 'height') isFinished = false"
            class="h-75 not-peer-checked/filter:h-0 flex flex-row justify-center items-start not-peer-checked/filter:overflow-hidden scrollbar-thin scrollbar-thumb-slate-400 scrollbar-track-transparent scrollbar-gutter-stable overscroll-contain filter not-peer-checked/filter:border-0 transition-all"

            @mousedown="outside = false"
            @mousedown.outside="outside = true"
            @mouseup.outside="
                if(outside && ! label.contains($event.target))
                    checkbox(false)
            "
            @keydown.escape.window="checkbox(false)"
        >

            <div class="overflow-hidden py-5 space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full transition-all ease-in font-sans text-zinc-100 selection:bg-indigo-500/30 select-text">
                <div class="flex items-center gap-x-3" id="category-filter">
                    <span class="select-none w-38 shrink-0 text-zinc-400 text-[0.75rem] font-mono uppercase">[FILTER::CATEGORY]</span>
                    <div class="flex items-center flex-wrap gap-3 flex-1">


                        <label class="filter-button group hidden" id="filter-button">
                            <input class="hidden" type="checkbox">
                            <span class="color"></span>
                            <span class="text"></span>
                        </label>


                    </div>
                    <button class="show-all-button" id="category-show-all-button">show all</button>
                </div>

                <div class="flex items-center gap-x-3" id="personnel-filter">
                    <span class="select-none w-38 shrink-0 text-zinc-400 text-sm font-mono uppercase">[FILTER::PERSONNEL]</span>

                    <div class="flex items-center flex-wrap gap-3 flex-1"></div>

                    <button class="show-all-button" id="personnel-show-all-button">show all</button>
                </div>
            </div>
        </div>
    </div>



    {{-- 1. شريط التنقل العلوي المتناسق مع خلفية الـ Zinc --}}
    {{-- <nav class="bg-zinc-900/80 backdrop-blur-md border-b border-zinc-800 text-white sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-8 rtl:space-x-reverse">
            <div class="shrink-0">
                <a href="#" class="text-xl font-bold tracking-wider text-indigo-400 hover:text-indigo-300 transition-colors duration-200">
                TASK_FLOW
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-4 rtl:space-x-reverse">
                <a href="#" class="bg-zinc-800 text-white px-3 py-2 rounded-lg text-sm font-medium">لوحة التحكم</a>
                <a href="#" class="text-zinc-400 hover:text-white hover:bg-zinc-800 px-3 py-2 rounded-lg text-sm font-medium transition-colors">المشاريع</a>
            </div>
            </div>
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="text-sm text-zinc-400">مرحباً، أحمد 👋</span>
            </div>
        </div>
        </div>
    </nav> --}}


</div>
