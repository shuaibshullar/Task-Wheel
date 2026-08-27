import {emailInput, passwordInput} from "@/lib/helpers.js";

export default function()
{
    const email_input = document.getElementById('email-input')
    emailInput(
        email_input,
        email_input.parentElement.querySelector('p'),
        'This field must be email.'
    );

    passwordInput(
        document.getElementById('password_field'),
        document.getElementById('p-password-input'),
        'This field is required.'
    );

    const form       = document.getElementById('form');
    const formButton = form.querySelector('button');
    const errorDiv   = document.getElementById('error-div');

    formButton.addEventListener('animationend', () => formButton.classList.remove('animate-shake'));
    form.noValidate = true;
    form.addEventListener('submit', (event) => {
        errorDiv?.remove();

        if (event.defaultPrevented)
        {
            formButton.classList.add('animate-shake');
        }
    });
}
