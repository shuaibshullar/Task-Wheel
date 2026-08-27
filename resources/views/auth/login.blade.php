<x-template.without-navbar page="login" lang="en" title="Login page" class="bg-zinc-950 min-h-screen flex items-center justify-center p-4 py-15 antialiased selection:bg-indigo-500/30 selection:text-indigo-200">

    <div class="w-full max-w-lg bg-zinc-900/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-8 shadow-2xl shadow-black/60 relative overflow-hidden">
    <!-- تأثير خط الإضاءة الفخم في أعلى الكارد -->
    <div class="absolute top-0 inset-x-0 h-px bg-linear-to-r from-transparent via-zinc-700/50 to-transparent"></div>

    <!-- ==================== الواجهة الأولى: تسجيل الدخول (Login) ==================== -->
    <div class="space-y-6">

      <!-- العنوان والوصف -->
      <div class="text-center">
        <h2 class="text-2xl font-bold text-white tracking-tight">Log in</h2>
        <p class="text-sm text-zinc-400 mt-2">Welcome back! Log in to continue your tasks.</p>
      </div>

        @session('error')
            <div id="error-div" class="mb-5 flex items-center gap-4 rounded-xl border border-rose-500/80 ring-rose-500/80 bg-rose-700/15 outline-none ring-1 p-4 text-sm text-white shadow-lg backdrop-blur-sm transition-all duration-300">

                <!-- أيقونة التحذير (SVG) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>

                <!-- نص الرسالة -->
                <div>
                    <span class="font-bold text-white me-1">Login error:</span>
                    Incorrect username or password.
                </div>

            </div>
        @endsession

      <!-- نموذج المدخلات -->
      <form action="{{ route('login_post') }}" method="POST" class="space-y-4" id="form">
        @csrf
        <!-- حقل اسم المستخدم -->
        <div class="group/email">
          <label class="block text-sm font-medium text-zinc-400 mb-2">Email</label>
          <input type="email" placeholder="your@email.com" name="email" value="{{ old('email') }}" id="email-input"
                 class="peer w-full bg-zinc-950/50 border border-zinc-800/80 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200
                 group-has-[p.show]/email:[&:invalid,:has(p.error)_&]:outline-none
                 group-has-[p.show]/email:[&:invalid,:has(p.error)_&]:border-rose-500/80
                 group-has-[p.show]/email:[&:invalid,:has(p.error)_&]:ring-1
                 group-has-[p.show]/email:[&:invalid,:has(p.error)_&]:ring-rose-500/80">


          <p class="@error('email') show error @enderror hidden peer-invalid:[.show]:block [.error]:block text-xs text-rose-400 mt-1.5 font-medium">
              This field must be email.
          </p>

        </div>

        <!-- حقل كلمة المرور -->
        <div class="group/password">
          <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-zinc-400">Password</label>
            <a href="{{ route('password-forgot') }}" class="text-xs text-rose-600 hover:text-rose-500 tracking-wide transition-colors">Forgot your password?</a>
          </div>
          <div class="relative">
            <input type="checkbox" id="show-password" class="peer/show-pwd hidden" onchange="changePasswordFieldType(this, 'password_field')">

            <input type="password" required placeholder="••••••••" name="password" autocomplete="off" id="password_field"
                class="peer w-full bg-zinc-950/50 border border-zinc-800/80 rounded-xl pl-4 pr-12 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200
                group-has-[p.show]/password:invalid:outline-none
                group-has-[p.show]/password:invalid:border-rose-500/80
                group-has-[p.show]/password:invalid:ring-1
                group-has-[p.show]/password:invalid:ring-rose-500/80">


            <label for="show-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition-colors p-1 cursor-pointer select-none peer-checked/show-pwd:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </label>

            <!-- الشكل الثاني للزر: عين مشطوبة ملونة (يظهر فقط عند تفعيل الرؤية) -->
            <label for="show-password" class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-indigo-400 hover:text-indigo-300 transition-colors p-1 cursor-pointer select-none peer-checked/show-pwd:block">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </label>




          </div>
          <p id="p-password-input" class="{{-- show --}}hidden group-has-[input.peer:invalid]/password:[.show]:block text-xs text-rose-400 mt-1.5 font-medium">
            This field is required.
          </p>
        </div>

        <label class="flex items-center cursor-pointer select-none group">
            <!-- التشيك بوكس الحقيقي مخفي تماماً -->
            <input type="checkbox" name="remember" class="peer hidden" >

            <!-- المربع المصمم يدوياً: يتغير لونه وتظهر علامة الصح الفخمة عند التفعيل -->
            <div class="w-5 h-5 bg-zinc-950/60 border border-zinc-800/80 rounded-md flex items-center justify-center text-transparent peer-checked:text-white peer-checked:bg-indigo-600 peer-checked:border-indigo-500 group-hover:border-zinc-700 transition-all duration-200">
                <!-- أيقونة علامة الصح (تظهر فقط عند التشيك) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </div>
            <!-- النص التوضيحي -->
            <span class="text-sm text-zinc-400 ml-2.5 group-hover:text-zinc-300 transition-colors">Remeber me</span>
        </label>

        <!-- زر الإرسال -->
        <button type="submit" class="cursor-pointer w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 px-4 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 mt-2">
            Login
        </button>
      </form>

      <!-- الجملة التوجيهية بالأسفل المرتبطة بـ Label لفتح صفحة الـ Register تلقائياً -->
      <div class="text-center pt-2 font-mono font-medium tracking-wide text-white">
        <p class="text-sm">
            Don`t have an account?
          <a href="{{ route('register_get') }}" class="text-rose-600 hover:text-rose-500 font-medium transition-colors cursor-pointer inline-block mr-1">
            Create new account
          </a>
        </p>
      </div>

    </div>

  </div>

</x-template.without-navbar>
