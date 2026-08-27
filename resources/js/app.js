import loginPage from '@/pages/login.js';
import registerPage from '@/pages/register.js';
import forgotPage from '@/pages/forgot-password.js';
import resetPage from '@/pages/reset-password.js';
import homePage from '@/pages/home.js';
import '@/lib/window-vars.js';


({

    login           :  loginPage,
    home            :  homePage,
    register        :  registerPage,
    forgotPassword  :  forgotPage,
    resetPassword   :  resetPage,

})[document.body.dataset?.page]?.();
