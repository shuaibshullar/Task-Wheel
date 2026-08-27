<x-template.with-navbar page="home" class="min-h-screen bg-zinc-950 text-zinc-100 antialiased font-sans" title="Home page" lang="en">
    <x-slot:external >
        <livewire:to-js data="tasks" id="fsdf84f6sf5sf4" update="5" />


        @can('is-admin')
            <x-task.entry-modal                                    id="entry-modal"         data-id="entry-modal-data"        class="relative z-5000" />
            <x-task.add-category-modal                             id="add-category-modal"  data-id="add-category-modal-data" class="relative z-5000" />
        @endcan
            <x-task.detail-modal  edit-task-id="edit-task-button"  id="detail-modal"        data-id="detail-modal-data"       class="relative z-5000" />
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="flex justify-center-safe items-start mb-6">



            <svg class="min-w-0 lg:w-13/24 w-full opacity-0 [[done]]:opacity-100 transition-all duration-1000 ease-in will-change-auto" id="wheel-svg" viewBox="0 0 760 760" role="img" aria-label="Year wheel showing tasks by month and category"></svg>






            {{-- ==================== السايد منيو العائم (Floating Sidebar) ==================== --}}
            {{-- <aside class="lg:col-span-1 bg-zinc-900/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-5 shadow-xl shadow-black/40 sticky top-23"> --}}

                {{-- عنوان السايد منيو والإحصائيات  --}}

                {{-- <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    مهامي اليومية
                    </h2>
                    <span class="bg-indigo-500/10 text-indigo-400 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-indigo-500/20">4 متبقية</span>
                </div> --}}
                {{-- شريط تقدم ناعم لإنجاز المهام --}}
                {{-- <div class="mt-4">
                    <div class="flex justify-between text-xs text-zinc-400 mb-1.5">
                    <span>نسبة الإنجاز</span>
                    <span>60%</span>
                    </div>
                    <div class="w-full bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-indigo-500 h-1.5 rounded-full transition-all duration-500" style="width: 60%"></div>
                    </div>
                </div>
                </div> --}}

                {{-- فلاتر سريعة  --}}
                {{-- <div class="flex gap-2 mb-6">
                <button class="flex-1 py-1.5 text-xs font-medium rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-600/10 cursor-pointer">الكل</button>
                <button class="flex-1 py-1.5 text-xs font-medium rounded-lg bg-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-700/50 transition-colors cursor-pointer">المعلقة</button>
                <button class="flex-1 py-1.5 text-xs font-medium rounded-lg bg-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-700/50 transition-colors cursor-pointer">المنتهية</button>
                </div> --}}

                {{-- قائمة المهام المرتبة (Task Items) --}}
                {{-- <div class="space-y-3"> --}}

                {{-- مهمة 1: أولوية قصوى (هام وعاجل) --}}
                {{-- <div class="group relative flex items-center justify-between p-3.5 rounded-xl bg-zinc-950/40 border-r-4 <!-- border-rose-500 --> border border-zinc-800/60 hover:bg-zinc-800/40 transition-all duration-200">
                    <div class="flex items-center gap-3">
                    <input type="checkbox" class="w-4 h-4 rounded text-indigo-600 bg-zinc-800 border-zinc-700 focus:ring-indigo-500 focus:ring-offset-zinc-900 cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-zinc-200 group-hover:text-white transition-colors">مراجعة كود الـ Database</p>
                        <span class="text-[10px] text-rose-400 bg-rose-500/10 px-1.5 py-0.5 rounded border border-rose-500/10">عاجل</span>
                    </div>
                    </div>
                </div> --}}

                {{-- مهمة 2: أولوية متوسطة --}}
                {{-- <div class="group relative flex items-center justify-between p-3.5 rounded-xl bg-zinc-950/40 border-r-4 <!-- border-amber-500 --> border border-zinc-800/60 hover:bg-zinc-800/40 transition-all duration-200">
                    <div class="flex items-center gap-3">
                    <input type="checkbox" class="w-4 h-4 rounded text-indigo-600 bg-zinc-800 border-zinc-700 focus:ring-indigo-500 focus:ring-offset-zinc-900 cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-zinc-200 group-hover:text-white transition-colors">تصميم واجهة المستخدم الجديدة</p>
                        <span class="text-[10px] text-amber-400 bg-amber-500/10 px-1.5 py-0.5 rounded border border-amber-500/10">متوسط</span>
                    </div>
                    </div>
                </div> --}}

                {{-- مهمة 3: أولوية عادية  --}}
                {{-- <div class="group relative flex items-center justify-between p-3.5 rounded-xl bg-zinc-950/40 border-r-4 <!-- border-indigo-500 --> border border-zinc-800/60 hover:bg-zinc-800/40 transition-all duration-200">
                    <div class="flex items-center gap-3">
                    <input type="checkbox" class="w-4 h-4 rounded text-indigo-600 bg-zinc-800 border-zinc-700 focus:ring-indigo-500 focus:ring-offset-zinc-900 cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-zinc-200 group-hover:text-white transition-colors">تجهيز تقرير الأداء الأسبوعي</p>
                        <span class="text-[10px] text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 rounded border border-indigo-500/10">اعتيادي</span>
                    </div>
                    </div>
                </div> --}}

                {{-- مهمة 4: مكتملة (تم إنجازها)  --}}
                {{-- <div class="group relative flex items-center justify-between p-3.5 rounded-xl bg-zinc-900/20 border-r-4 <!-- border-emerald-500/30 --> border border-zinc-800/30 opacity-60">
                    <div class="flex items-center gap-3">
                    <input type="checkbox" checked class="w-4 h-4 rounded text-emerald-500 bg-zinc-800 border-zinc-700 focus:ring-emerald-500 focus:ring-offset-zinc-900 cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 line-through">تحديث مكتبات الـ CSS</p>
                        <span class="text-[10px] text-emerald-400/70 bg-emerald-500/5 px-1.5 py-0.5 rounded border border-emerald-500/5">مكتمل</span>
                    </div>
                    </div>
                </div>

                </div> --}}

                {{-- زر إضافة مهمة جديدة في نهاية المنيو  --}}
                {{-- <button class="cursor-pointer w-full mt-6 flex items-center justify-center gap-2 py-2.5 rounded-xl border border-dashed border-zinc-700 text-sm text-zinc-400 hover:text-indigo-400 hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                إضافة مهمة جديدة
                </button>

            </aside> --}}
            {{-- ==================== نهاية السايد منيو ==================== --}}

            {{-- 3. مساحة المحتوى الرئيسية (Main Content Area) --}}
            {{-- <main class="lg:col-span-3 min-h-screen bg-zinc-900/30 border border-zinc-800/50 rounded-2xl p-6 mb-8">
                <h1 class="text-2xl font-bold text-white mb-4">مرحباً بك في لوحة تحكم مهامك</h1>
                <p class="text-zinc-400 leading-relaxed">
                هنا تظهر التفاصيل والبيانات التحليلية لمهامك اليومية. لقد صممنا السايد منيو ليكون عائماً ومزوداً بحواف دائرية أنيقة `rounded-2xl` تبتعد بشكل مريح عن أطراف الصفحة لتتطابق تماماً مع خلفية الـ `zinc-950` التي قمت باختيارها.
                </p>
            </main> --}}
        </div>
    </div>
</x-template.with-navbar>
