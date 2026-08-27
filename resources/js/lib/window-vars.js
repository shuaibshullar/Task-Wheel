let disableScrollElements = 0;
const scrollbarWidth   = window.innerWidth - document.documentElement.clientWidth;


window.disablePageScroll = (element = null) => {
    if (scrollbarWidth > 0)
    {
        document.documentElement.style.paddingInlineEnd = `${scrollbarWidth}px`;
        if (element) element.style.paddingInlineEnd     = `${ parseFloat(getComputedStyle(element).paddingInlineEnd) + scrollbarWidth }px`;
    }

    document.documentElement.classList.add('overflow-hidden');
    disableScrollElements ++;
}

window.enablePageScroll = (element = null) => {
    document.documentElement.style.paddingInlineEnd = '0px';
    if (element) element.style.paddingInlineEnd     = "";

    disableScrollElements --;
    if (disableScrollElements === 0)
        document.documentElement.classList.remove('overflow-hidden');
}

window.changePasswordFieldType = (checkboxElement, passwordFieldId) => {

    const passwordField = document.getElementById(passwordFieldId);

    if (checkboxElement.checked)     passwordField.type = 'text';
    else                             passwordField.type = 'password';

};
