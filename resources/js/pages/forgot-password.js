import {emailInput} from "@/lib/helpers.js";

export default function()
{
    const email_input = document.getElementById('email-input');
    emailInput(
        email_input,
        email_input.parentElement.querySelector('p'),
        'This field must be email.'
    );

    const form = document.getElementById('form');
    const formButton = form.querySelector('& > button');
    const formButtonSvg = formButton.querySelector('& > svg');
    const formButtonSpan = formButton.querySelector('& > span');

    formButton.addEventListener('animationend', () => formButton.classList.remove('animate-shake'));
    form.noValidate = true;
    form.addEventListener('submit', (event) => {


        if (event.defaultPrevented)
        {
            formButton.classList.add('animate-shake');
        }
        else
        {
            formButtonSpan.classList.add('hidden');
            formButtonSvg.classList.remove('hidden');
        }

    });
}
