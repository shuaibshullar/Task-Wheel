<x-template.without-navbar page="forgotPassword" lang="en" title="Forgot password" class="bg-zinc-950 min-h-screen flex items-center justify-center p-4 py-15 antialiased selection:bg-indigo-500/30 selection:text-indigo-200">
@php($makeAdmin = false)
    <div class="w-full max-w-lg bg-zinc-900/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-8 shadow-2xl shadow-black/60 relative overflow-hidden">

    {{-- تأثير خط الإضاءة الفخم في أعلى الكارد --}}
    <div class="absolute top-0 inset-x-0 h-px bg-linear-to-r from-transparent via-zinc-700/50 to-transparent"></div>

    {{-- ==================== الواجهة الثانية: تسجيل حساب جديد (Register) ==================== --}}
    <div class="space-y-6">

        <!-- العنوان والوصف -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-white tracking-tight">Reset your password</h2>
            <p class="text-sm text-zinc-400 mt-3">
                Enter your registered email address below.
                We'll send a secure link to help you create a new password and regain access to your account.
                If you don't receive an email within a few minutes, please check your spam folder.
            </p>
        </div>

        @if($errors->has('status') || session()->has('status'))
            @php($isError = $errors->has('status'))

            <div id="error-div" class="mb-5 flex items-center gap-4 rounded-xl border @if($isError) border-rose-500/80 ring-rose-500/80 bg-rose-700/15 @else border-emerald-700 ring-emerald-700 bg-emerald-900/20 @endif outline-none ring-1 p-4 text-sm text-white shadow-lg backdrop-blur-sm transition-all duration-300">

                <!-- أيقونة التحذير (SVG) -->
                @if($isError)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h9"></path>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        <path d="m16 19 2 2 4-4"></path>
                    </svg>
                @endif


                <!-- نص الرسالة -->
                <div>
                    @if ($isError)
                        {{ $errors->first('status') }}
                    @else
                        {{ session('status') }}
                    @endif
                </div>

            </div>
        @endif


        <!-- نموذج المدخلات المطور -->
        <form action="{{ route('password-forgot.post') }}" method="POST" class="space-y-4" id="form">
            @csrf

            <!-- حقل الإيميل -->
            <div class="group/email">
              <label class="block text-sm font-medium text-zinc-400 mb-2">Email</label>
              <input type="email" placeholder="name@example.com" name="email" id="email-input" value="{{ old('email') }}"
                     class="peer w-full bg-zinc-950/50 border border-zinc-800/80 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200
                     group-has-[p.show]/email:[&:invalid,:has(p.error)_&]:outline-none
                     group-has-[p.show]/email:[&:invalid,:has(p.error)_&]:border-rose-500/80
                     group-has-[p.show]/email:[&:invalid,:has(p.error)_&]:ring-1
                     group-has-[p.show]/email:[&:invalid,:has(p.error)_&]:ring-rose-500/80">

              <p class="@error('email') show error @enderror text-xs text-rose-400 mt-1.5 font-medium hidden peer-invalid:[.show]:block [.error]:block">
                @if ($errors->has('email'))
                    {{ $errors->first('email') }}
                @else
                    This field must be email.
                @endif
              </p>
            </div>



            <!-- زر الإرسال -->
            <button type="submit" class="flex justify-center items-center cursor-pointer w-full tracking-wider bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 px-4 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 mt-2">
                <span class="shrink-0">Send reset link</span>
                <svg class="animate-spin h-5 w-5 shrink-0 hidden" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
        </form>

        <div class="text-center pt-2 select-none font-mono font-medium tracking-wide text-white">
            <p class="text-sm">
                You have an account?
                <a href="{{ route('login_get') }}"
                   class="text-rose-600 hover:text-rose-700 font-medium transition-colors cursor-pointer inline-block mr-1">
                    Log in page
                </a>
            </p>
        </div>

    </div>

  </div>

</x-template.without-navbar>
