export function alpineData(id)
{
    const dataEl = document.getElementById(id);
    if (! dataEl) return null;

    return Alpine.$data(dataEl);
}

export function getISO(iso = null, year = null, month = null, day = null)
{
    const [Year, Month, Day] = ( year === null && month === null && day === null && iso !== null ) // if
        ? iso.split('-').map(Number)                                           // if block
        : (

            year !== null && month !== null && day !== null && iso === null    // else if
                ? [year, month, day]                                               // else if block


                : [null, null, null]                                               // else

        );


    return [Year, Month, Day];
}

export function longDate(iso = null, year = null, month = null, day = null)
{
    const [Year, Month, Day] = getISO(iso, year, month, day);

    if (Year === null && Month === null && Day === null) return;



    const Iso  = `${  Year  }-${  String(Month).padStart(2,'0')  }-${  String(Day).padStart(2,'0')  }`;
    const date = new Date(Iso + 'T00:00:00Z');
    return date.toLocaleDateString('en-US',{
        weekday:'long',
        year:'numeric',
        month:'long',
        day:'numeric',
        timeZone: 'UTC',
    });
}

export function compactDate(iso = null, year = null, month = null, day = null)
{
    const [Year, Month, Day] = getISO(iso, year, month, day);

    if (Year === null && Month === null && Day === null) return;

    return `${ Day }.${ Month }.${ Year }`;
}

export function polar(cx, cy, r, angleDeg)
{
    const rad = angleDeg * ( Math.PI / 180 );

    return {
        x: cx + ( r * Math.cos(rad) ),
        y: cy + ( r * Math.sin(rad) ),
    };
}

export function daysInYear(year)
{
    if ( (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0 )
        return 366;

    else
        return 365;
}

export function dayOfYear(iso)
{
    const date  = new Date(iso + 'T00:00:00.0Z');
    const start = new Date(Date.UTC(date.getUTCFullYear(), 0, 0));

    return Math.floor(
        (date - start) / ( 24 * 60 * 60 * 1000 )
    );
}

export function angleForISO(iso)
{
    const date = new Date(iso + 'T00:00:00.0Z');

    return ( dayOfYear(iso) / daysInYear(date.getUTCFullYear()) ) * 360;
}


/**
 * @param input {HTMLInputElement | HTMLElement}
 * @param p {HTMLInputElement | HTMLElement}
 * @param defaultValue {string}
 */
export function nameInput(input, p, defaultValue)
{
    const form = input.closest('form');

    input.addEventListener('input', () => {

        p.classList.remove('show');
        p.textContent = defaultValue;
    });

    form.addEventListener('submit', (event) => {

        if (input.value.trim().length === 0)
        {
            event.preventDefault();
            p.classList.add('show');
        }
    });
}


/**
 * @param input {HTMLInputElement | HTMLElement}
 * @param p {HTMLInputElement | HTMLElement}
 * @param defaultValue {string}
 */
export function emailInput(input, p, defaultValue)
{
    const form = input.closest('form');

    input.addEventListener('input', () => {

        p.classList.remove('show', 'error');
        p.textContent = defaultValue;
    });

    form.addEventListener('submit', (event) => {
        const isNotEmpty = input.value.trim().length !== 0;
        const InputValidation = input.checkValidity() && isNotEmpty;

        if (! InputValidation)
        {
            event.preventDefault();
            p.classList.add('show');
            if (! isNotEmpty)
                p.classList.add('error');
        }
    });
}


/**
 * @param input {HTMLInputElement | HTMLElement}
 * @param p {HTMLInputElement | HTMLElement}
 * @param defaultValue {string}
 */
export function passwordInput(input, p, defaultValue)
{
    const form = input.closest('form');

    input.addEventListener('input', () => {

        p.classList.remove('show');
        p.textContent = defaultValue;
    });

    form.addEventListener('submit', (event) => {
        const inputValidation = input.value.trim().length !== 0;

        if (! inputValidation)
        {
            event.preventDefault();
            p.classList.add('show');
        }
    });
}


/**
 * @param input {HTMLInputElement | HTMLElement}
 * @param confirmationInput {HTMLInputElement | HTMLElement}
 * @param rulesDiv {HTMLInputElement | HTMLElement}
 * @param rulesElements {{
 *     length:HTMLElement,
 *     upper:HTMLElement,
 *     lower:HTMLElement,
 *     number:HTMLElement,
 *     special:HTMLElement,
 *     confirmed:HTMLElement,
 * }}
 * @param validClass {string}
 * @param invalidClass {string}
 */
export function passwordWithConfirmationInput(input, confirmationInput, rulesDiv, rulesElements, validClass, invalidClass)
{
    const form = input.closest('form');
    const rules = {
        length:    {     el: rulesElements.length.classList,          test: (value)                 => value.trim().length >= 8                 },
        upper:     {     el: rulesElements.upper.classList,           test: (value)                 => /[A-Z]/.test(value)                      },
        lower:     {     el: rulesElements.lower.classList,           test: (value)                 => /[a-z]/.test(value)                      },
        number:    {     el: rulesElements.number.classList,          test: (value)                 => /[0-9]/.test(value)                      },
        special:   {     el: rulesElements.special.classList,         test: (value)                 => /[!@#$%^&*(),.?":{}|<>_]/.test(value)    },
        confirmed: {     el: rulesElements.confirmed.classList,       test: (value, confirmedValue) => value === confirmedValue                 },
    };

    let passwordValidation = false;
    window.passwordOnInput = () => {

        rulesDiv.classList.remove('show');

        passwordValidation = true;
        const value = input.value.trim();
        const confirmedValue = confirmationInput.value.trim();

        for (const {el, test} of Object.values(rules))
        {
            const isValid = test(value, confirmedValue);

            if (value.length === 0)
            {
                el.remove(validClass);
                el.add(invalidClass);
                passwordValidation = false;
            }
            else if (isValid)
            {
                el.remove(invalidClass);
                el.add(validClass);
            }
            else
            {
                el.remove(validClass);
                el.add(invalidClass);
                passwordValidation = false;
            }
        }
    }

    form.addEventListener('submit', (event) => {

        if (! passwordValidation)
        {
            event.preventDefault();
            rulesDiv.classList.add('show');
        }
    });
}
