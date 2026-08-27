import {passwordWithConfirmationInput} from "@/lib/helpers.js";

export default function ()
{
    passwordWithConfirmationInput(
        document.getElementById('password_input'),
        document.getElementById('password_conformation_input'),
        document.getElementById('rules-div'),
        {
            length:      document.getElementById('rule-length'),
            upper:       document.getElementById('rule-upper'),
            lower:       document.getElementById('rule-lower'),
            number:      document.getElementById('rule-number'),
            special:     document.getElementById('rule-special'),
            confirmed:   document.getElementById('rule-confirmed'),
        },
        'bg-emerald-500',
        'bg-rose-500',
    );



    const form = document.getElementById('form');
    const formButton = form.querySelector('button');

    formButton.addEventListener('animationend', () => formButton.classList.remove('animate-shake'));
    form.noValidate = true;
    form.addEventListener('submit', (event) => {

        if (event.defaultPrevented)
            formButton.classList.add('animate-shake');
    });
}
