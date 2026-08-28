@php($mustChangePassword ??= false)

<x-template.without-navbar page="resetPassword" lang="en" :title="$mustChangePassword ? 'Change password' : 'Register page'" class="bg-zinc-950 min-h-screen flex items-center justify-center p-4 py-15 antialiased selection:bg-indigo-500/30 selection:text-indigo-200">

    <div class="w-full max-w-lg bg-zinc-900/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-8 shadow-2xl shadow-black/60 relative overflow-hidden">

        {{-- تأثير خط الإضاءة الفخم في أعلى الكارد --}}
        <div class="absolute top-0 inset-x-0 h-px bg-linear-to-r from-transparent via-zinc-700/50 to-transparent"></div>

        {{-- ==================== الواجهة الثانية: تسجيل حساب جديد (Register) ==================== --}}
        <div class="space-y-6">

            <!-- العنوان والوصف -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-white tracking-tight">
                    @if($mustChangePassword)
                        You must change your password
                    @else
                        Change your password
                    @endif
                </h2>
                <p class="text-sm text-zinc-400 mt-2">
                    @if($mustChangePassword)
                        Please change your password for security purposes.
                    @else
                        Please enter your new password below to update your account security.
                    @endif
                </p>
            </div>

            @if(collect($errors->keys())->diff(['password'])->isNotEmpty())

                <div id="error-div" class="mb-5 flex items-center gap-4 rounded-xl border border-rose-500/80 ring-rose-500/80 bg-rose-700/15 outline-none ring-1 p-4 text-sm text-white shadow-lg backdrop-blur-sm transition-all duration-300">

                    <!-- أيقونة التحذير (SVG) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>


                    <!-- نص الرسالة -->
                    <div>
                        @if ($errors->has('status'))
                            {{ $errors->first('status') }}
                        @else
                            This action is illegal.
                        @endif
                    </div>

                </div>
            @endif

            <!-- نموذج المدخلات المطور -->
            <form action="@if($mustChangePassword) {{ route('password.change.post') }} @else {{ route('password.update') }} @endif" method="POST" class="space-y-4" id="form">
                @csrf

                @if(! $mustChangePassword)
                    <input type="hidden" autocomplete="off" name="token" value="{{ $token }}" inert>
                    <input type="hidden" autocomplete="off" name="email" value="{{ $email }}" inert>
                @endif

                <!-- حقل كلمة المرور -->
                <div>
                    <label class="block text-sm font-medium text-zinc-400 mb-2">Password</label>

                    <div class="relative">
                        <input type="checkbox" id="password_checkbox" class="peer/show-pwd hidden" onchange="changePasswordFieldType(this, 'password_input')">

                        <input type="password" placeholder="••••••••" autocomplete="off" name="password" id="password_input"
                               oninput="passwordOnInput()"
                               class="w-full bg-zinc-950/50 border border-zinc-800/80 rounded-xl py-3 pl-4 pr-12 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200">




                        <label for="password_checkbox" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition-colors p-1 cursor-pointer select-none peer-checked/show-pwd:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </label>

                        <!-- الشكل الثاني للزر: عين مشطوبة ملونة (يظهر فقط عند تفعيل الرؤية) -->
                        <label for="password_checkbox" class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-indigo-400 hover:text-indigo-300 transition-colors p-1 cursor-pointer select-none peer-checked/show-pwd:block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </label>

                    </div>
                </div>

                <!-- حقل تأكيد كلمة المرور -->
                <div>
                    <label class="block text-sm font-medium text-zinc-400 mb-2">Password confirmation</label>


                    <div class="relative">
                        <input type="checkbox" id="password_checkbox_confirmation" class="peer/show-pwd hidden" onchange="changePasswordFieldType(this, 'password_conformation_input')">

                        <input type="password" placeholder="••••••••" name="password_confirmation" autocomplete="off" id="password_conformation_input"
                               oninput="passwordOnInput()"
                               class="w-full bg-zinc-950/50 border border-zinc-800/80 rounded-xl py-3 pl-4 pr-12 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200">


                        <label for="password_checkbox_confirmation" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition-colors p-1 cursor-pointer select-none peer-checked/show-pwd:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </label>

                        <!-- الشكل الثاني للزر: عين مشطوبة ملونة (يظهر فقط عند تفعيل الرؤية) -->
                        <label for="password_checkbox_confirmation" class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-indigo-400 hover:text-indigo-300 transition-colors p-1 cursor-pointer select-none peer-checked/show-pwd:block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </label>


                    </div>
                </div>

                <div class="rounded-xl border p-3.5 border-zinc-800 bg-zinc-900/60
                    has-[p.show]:not-focus:outline-none
                    has-[p.show]:not-focus:border-rose-500/80
                    has-[p.show]:not-focus:ring-1
                    has-[p.show]:not-focus:ring-rose-500/80">

                    <p class="@error('password') show @enderror mb-4 text-sm font-medium text-zinc-400 [.show]:text-rose-400" id="rules-div">
                        Password requirements:
                    </p>

                    <ul class="space-y-1.5 text-sm text-zinc-400">
                        <!-- شرط 1: عدد الحروف -->
                        <li class="flex items-center gap-2">
                            <span class="inline-block h-2 w-2 rounded-full bg-rose-500" id="rule-length"></span>
                            <span>Minimum eight characters</span>
                        </li>

                        <!-- شرط 2: حرف كبير -->
                        <li class="flex items-center gap-2">
                            <span class="inline-block h-2 w-2 rounded-full bg-rose-500 {{-- bg-emerald-500 --}}" id="rule-upper"></span>
                            <span>One uppercase letter (A-Z)</span>
                        </li>


                        <li class="flex items-center gap-2">
                            <span class="inline-block h-2 w-2 rounded-full bg-rose-500 {{-- bg-emerald-500 --}}" id="rule-lower"></span>
                            <span>One lowercase letter (a-z)</span>
                        </li>

                        <!-- شرط 3: رقم -->
                        <li class="flex items-center gap-2">
                            <span class="inline-block h-2 w-2 rounded-full bg-rose-500 {{-- bg-zinc-400 --}}" id="rule-number"></span>
                            <span>One number (0-9)</span>
                        </li>

                        <!-- شرط 4: رمز خاص -->
                        <li class="flex items-center gap-2">
                            <span class="inline-block h-2 w-2 rounded-full bg-rose-500 {{-- bg-zinc-400 --}}" id="rule-special"></span>
                            <span>One special character (@, #, $)</span>
                        </li>

                        <li class="flex items-center gap-2">
                            <span class="inline-block h-2 w-2 rounded-full bg-rose-500 {{-- bg-zinc-400 --}}" id="rule-confirmed"></span>
                            <span>Matching confirmation password</span>
                        </li>
                    </ul>
                </div>

                <!-- زر الإرسال -->
                <button type="submit" class="cursor-pointer w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 px-4 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 mt-2">
                    Change password
                </button>
            </form>

            @if($mustChangePassword)
                <form method="post" action="{{ route('logout_post') }}" class="font-mono font-medium tracking-wide text-white">
                    <div class="text-center pt-2">
                        <p class="text-sm">
                            Not you?
                            <button type="submit" class="text-rose-600 hover:text-rose-700 font-medium transition-colors cursor-pointer inline-block mr-1 outline-none">
                                Log out
                            </button>
                            to sign in with a different account.
                        </p>
                    </div>
                </form>
            @endif

        </div>

    </div>

</x-template.without-navbar>
