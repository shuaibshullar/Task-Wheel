import { alpineData, longDate, compactDate, polar, angleForISO } from '@/lib/helpers.js';
import dataFromServer from '@/lib/to-js.js';

export default function ()
{
    let tasks = [];
    let categories = [];


    dataFromServer(
        'fsdf84f6sf5sf4',

        /** @param {{tasks, categories}} data */
        (data) => {

            tasks      = data.tasks ?? [];
            categories = data.categories ?? [];


            renderAll();
        }
    );

    function renderAll(fromFilter = false)
    {
        if ( (! filterOpenCheckbox.checked && ! sidebarCheckbox.checked) || fromFilter )
        {
            if (! fromFilter) renderFilter();
            renderWheel();
            renderDeadlines();
        }
    }

    ///////////////////////////////////////////////////////////////               Filters               ///////////////////////////////////////////////////////////////
    const filterOpen            = document.getElementById('filter-open-button');
    const filterOpenCheckbox    = document.getElementById('filter-checkbox').querySelector('& > input');
    const catFilter             = document.getElementById('category-filter');
    const catButtonArea         = catFilter.querySelector('& > div');
    const perFilter             = document.getElementById('personnel-filter');
    const perButtonArea         = perFilter.querySelector('& > div');
    const filterButton          = document.getElementById('filter-button');
    const getButtonFrags        = (button) => [ button.querySelector('& > span.color') , button.querySelector('& > span.text') ];
    filterButton.remove();
    filterButton.removeAttribute('id');
    filterButton.classList.remove('hidden');

    function addCategory (name, color)
    {
        /** @type {HTMLElement} */
        const button                = filterButton.cloneNode(true);
        const [spanColor, apanText] = getButtonFrags(button);
        const input                 = button.querySelector('& > input');

        spanColor.style.backgroundColor = color;
        apanText.textContent            = name;


        input.addEventListener('change', function() {

            if (this.checked)
                filter.categories.add(name);

            else
                filter.categories.delete(name);


            renderAll(true);
        });

        if (filter.categories.has(name))
            input.checked = true;

        categoryButtons.add(() => { input.checked = false });


        const id = 'filter-' + crypto.randomUUID();
        input.id = id;
        button.htmlFor = id;



        catButtonArea.appendChild(button);
    }

    function addPerson(name)
    {
        /** @type {HTMLElement} */
        const button                = filterButton.cloneNode(true);
        const [spanColor, spanText] = getButtonFrags(button);
        const input                 = button.querySelector('& > input');

        spanColor.remove();
        spanText.textContent = name;


        input.addEventListener('change', function() {

            if (this.checked)
                filter.persons.add(name);

            else
                filter.persons.delete(name);


            renderAll(true);
        });

        if (filter.persons.has(name))
            input.checked = true;

        personButtons.add(() => { input.checked = false });


        const id = 'filter-' + crypto.randomUUID();
        input.id = id;
        button.htmlFor = id;



        perButtonArea.appendChild(button);
    }

    const catShowAllButton   = document.getElementById('category-show-all-button');
    const perShowAllButton   = document.getElementById('personnel-show-all-button');
    const categoryButtons    = new Set();
    const personButtons      = new Set();
    catShowAllButton.addEventListener('click', () => { for (const method of categoryButtons)  method(); filter.categories.clear(); renderAll(true) });
    perShowAllButton.addEventListener('click', () => { for (const method of personButtons)    method(); filter.persons.clear();    renderAll(true) });



    const filter = {categories: new Set(), persons: new Set()};
    function renderFilter()
    {
        categoryButtons.clear();
        personButtons.clear();

        const category = new Set();
        const persons  = new Set();


        catButtonArea.innerHTML = "";
        perButtonArea.innerHTML = "";

        for (const task of tasks)
        {
            const categoryName   = categories[task.category]?.name;
            const assignees      = task.assignees ?? [];


            category.add(categoryName);
            for (const asg of assignees) persons.add(asg);
        }





        for (const name of category)
        {
            addCategory(name, categories[name]?.color);
        }
        for (const name of persons)
        {
            addPerson(name);
        }





        const issetCategory = ! (category.size === 0);
        const issetPerson   = ! (persons.size  === 0);

        if (! issetCategory)
            catFilter.classList.add('hidden')
        else
            catFilter.classList.remove('hidden')


        if(! issetPerson)
            perFilter.classList.add('hidden')
        else
            perFilter.classList.remove('hidden')





        if ((! issetCategory) && (! issetPerson)) {

            filterOpen.classList.add('hidden')
            filterOpenCheckbox.checked = false;

        } else {

            filterOpen.classList.remove('hidden')

        }
    }


    function filteredTasks()
    {
        return tasks.filter((task) => {

            const catOk = filter.categories.size === 0 || filter.categories.has(task.category);
            const asgOk = filter.persons.size === 0    || task.assignees.some( (person) => filter.persons.has(person) );

            return catOk && asgOk;
        });
    }

    function upcomingTasks()
    {
        const todayISO = new Date().toISOString().split('T')[0];

        return filteredTasks()
            .filter( (task) => task.deadline >= todayISO )
            .sort( (a,b) => a.deadline.localeCompare(b.deadline) );
    }



    ///////////////////////////////////////////////////////////////               Modals               ///////////////////////////////////////////////////////////////
    const entryModal       = () => alpineData('entry-modal-data');
    const detailModal      = () => alpineData('detail-modal-data');
    const addCategoryModal = () => alpineData('add-category-modal-data');
    const taskData         = {
        id: null,
        title: null,
        description: null,
        category: null,
        deadline: null,
        assignees: null,
    };

    function changeTaskData(id = null, title = null, description = null, category = null, deadline = null, assignees = null)
    {
        taskData.id             = id;
        taskData.title          = title;
        taskData.description    = description;
        taskData.category       = category;
        taskData.deadline       = deadline;
        taskData.assignees      = assignees;
    }

    function openDetail(id, title, description = null, category, deadline, assignees = null)
    {
        changeTaskData(
            id,
            title,
            description,
            category,
            deadline,
            assignees,
        );


        detailModal().open(
            id,
            title,
            description,
            category,
            longDate(deadline),
            assignees,
        );
    }

    document.getElementById('edit-task-button')?.addEventListener('click', () => {

        detailModal().close();
        entryModal().open(
            taskData.id,
            taskData.title,
            taskData.description,
            taskData.category,
            taskData.deadline,
            taskData.assignees,
            'update'
        );
        changeTaskData();
    });




    ///////////////////////////////////////////////////////////////               svg wheel               ///////////////////////////////////////////////////////////////
    const svg = document.getElementById('wheel-svg');
    const CX = 380, CY = 380;
    const R_HUB = 68, R_GRID1 = 140, R_TASK = 210, R_OUTER = 300, R_LABEL = 326;
    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];


    function svgEl(tag, attrs)
    {
        const el = document.createElementNS('http://www.w3.org/2000/svg', tag);


        for (const  [attr,value] of /** @type {[string, string][]} */ Object.entries(attrs))
        {
            el.setAttribute(attr, value);
        }

        return el;
    }

    function renderWheel()
    {
        svg.innerHTML = '';

        svg.appendChild(svgEl('circle', {  cx:CX, cy:CY, r:R_GRID1, class:'dashed'  }));
        svg.appendChild(svgEl('circle', {  cx:CX, cy:CY, r:R_TASK,  class:'dashed'  }));
        svg.appendChild(svgEl('circle', {  cx:CX, cy:CY, r:R_OUTER, class:'solid'  }));


        for (let month = 0; month < 12; month++)
        {
            const angle  = month * 30;

            const point1 = polar(CX, CY, R_HUB,   angle - 90);
            const point2 = polar(CX, CY, R_OUTER, angle - 90);

            svg.appendChild(svgEl(
                'line',
                {
                    x1: point1.x,
                    y1: point1.y,

                    x2: point2.x,
                    y2: point2.y,

                    class: 'spoke',
                }
            ));
        }

        // Month labels
        for (let month = 0; month < 12; month++)
        {
            const midAngle = ( month * 30 ) + 15;
            const point    = polar(CX, CY, R_LABEL, midAngle - 90);

            const rotate = midAngle > 90 && midAngle < 270
                ? midAngle + 180
                : midAngle;

            const label = svgEl('text', {
                x: point.x,
                y: point.y,

                transform: `rotate(${ rotate } ${ point.x } ${ point.y })`,

                class: 'month',
            });

            label.textContent = MONTHS[month].toUpperCase();

            svg.appendChild(label);
        }

        // hub
        svg.appendChild(svgEl(
            'circle',
            {
                cx: CX,
                cy: CY,
                r: R_HUB,

                class: 'hub',
            }
        ));

        const label1 = svgEl('text', {
            x:CX,
            y: CY - 18,

            class: 'label1',
        });
        label1.textContent = 'TASKS'
        svg.appendChild(label1);

        const label2 = svgEl('text', {
            x: CX,
            y: CY + 18,

            class: 'label2'
        });
        label2.textContent = filteredTasks().length.toString();
        svg.appendChild(label2);


        // Today marker
        const todayISO = new Date().toISOString().split('T')[0];
        const angle    = angleForISO(todayISO);

        const point1   = polar(CX, CY, R_HUB,   angle - 90);
        const point2   = polar(CX, CY, R_OUTER, angle - 90);

        svg.appendChild(svgEl('line', {
            x1: point1.x,
            y1: point1.y,

            x2: point2.x,
            y2: point2.y,

            class: 'today-marker'
        }));


        // buckets
        const buckets = {};
        for (const task of filteredTasks())
        {
            const key = Math.floor(angleForISO(task.deadline) / 6);
            buckets[key] ??= [];
            buckets[key].push({
                task: task,
                angle: angleForISO(task.deadline),
            });
        }

        for (const group of Object.values(buckets))
        {
            const angle = group.reduce( (angle, item) => angle + item.angle  ,  0 ) / group.length;
            let idx = 0;
            for (const {task} of group)
            {
                const spread = group.length > 1
                    ? ( idx - (group.length - 1) / 2 ) * 18
                    : 0;

                const r      = R_TASK + spread;
                const point  = polar(CX, CY, r, angle - 90);
                const dot    = svgEl('circle', {
                    cx: point.x,
                    cy: point.y,

                    r: 5,

                    fill: categories[task.category]?.color,
                    class: 'dot',
                });

                const title  = svgEl('title', {});
                title.textContent = `${task.title}  -  ${compactDate(task.deadline)}`
                    + (
                        task.assignees.length !== 0
                            ? `  -  ${task.assignees.join(' , ')}`
                            : ''
                    );

                dot.appendChild(title);
                dot.addEventListener('click', () => {

                    const assignees = task.assignees.length > 0
                        ? task.assignees
                        : null;

                    openDetail(
                        task.id,
                        task.title,
                        task.description,
                        task.category,
                        task.deadline,
                        assignees,
                    );
                });

                svg.appendChild(dot);

                idx++;
            }
        }


        svg.setAttribute('done', null);
    }




    ///////////////////////////////////////////////////////////////               Upcoming deadlines list               ///////////////////////////////////////////////////////////////
    /** @type {HTMLInputElement} */
    const sidebarCheckbox = document.getElementById('toggle-sidebar');
    const upcomingDiv     = document.getElementById('upcoming-div');
    const upcomingSpan    = document.getElementById('upcoming-nothing-span');
    const upcomingButton  = document.getElementById('upcoming-button');

    const noTasksText     = "No tasks logged yet.\nAdd one to populate the wheel.";
    const noDeadlinesText = "No upcoming deadlines match the current filters.";

    upcomingButton.remove();
    upcomingButton.removeAttribute('id');
    upcomingButton.classList.remove('hidden');

    upcomingSpan.remove();
    upcomingSpan.removeAttribute('id');
    upcomingSpan.classList.remove('hidden');
    upcomingSpan.classList.add('block');



    function getUpcomingButtonFrags(button)
    {
        return [
            button.querySelector('& > div[category] > div'),
            button.querySelector('& > div[category] > span'),
            button.querySelector('& > span[title]'),
            button.querySelector('& > div[deadline] > span'),
        ];
    }

    function addUpcomingButton(id, title, description = null, category, deadline, assignees = null, color)
    {
        const button = upcomingButton.cloneNode(true);
        const [ colorDiv, categorySpan, titleSpan, deadlineSpan ] = getUpcomingButtonFrags(button);


        colorDiv.style.backgroundColor = color;
        categorySpan.textContent       = category;
        titleSpan.textContent          = title;
        deadlineSpan.textContent       = compactDate(deadline);


        button.addEventListener('click', () => {

            const asg = assignees !== null && assignees.length > 0
                ? assignees
                : null;

            openDetail(
                id,
                title,
                description,
                category,
                deadline,
                asg,
            );
        });


        upcomingDiv.appendChild(button);
    }



    function renderDeadlines()
    {
        upcomingDiv.innerHTML = "";
        const upcomingDeadline = upcomingTasks();


        if (tasks.length === 0)
        {
            upcomingSpan.textContent = noTasksText;
            upcomingDiv.appendChild(upcomingSpan);
        }
        else if (upcomingDeadline.length === 0)
        {
            upcomingSpan.textContent = noDeadlinesText;
            upcomingDiv.appendChild(upcomingSpan);
        }


        for (const task of upcomingDeadline)
        {
            addUpcomingButton(
                task.id,
                task.title,
                task.description,
                task.category,
                task.deadline,
                task.assignees,
                categories[task.category]?.color,
            );
        }
    }
}
