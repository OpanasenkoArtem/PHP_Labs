const STATUSES = ['open', 'in_progress', 'closed'];

const results = document.getElementById('results');
const search = document.getElementById('search');
const form = document.getElementById('add-form');
const message = document.getElementById('message');

// Універсальний запит: кидає помилку, якщо response.ok === false
async function api(url, options) {
    const response = await fetch(url, options);
    let data = null;
    try {
        data = await response.json();
    } catch (e) {
        // відповідь без JSON
    }
    if (!response.ok) {
        throw new Error((data && data.error) || 'Помилка сервера: ' + response.status);
    }
    return data;
}

function showMessage(text, isError) {
    message.textContent = text;
    message.className = isError ? 'error' : '';
}

// Створення рядка таблиці (textContent - безпечно щодо XSS)
function createRow(ticket) {
    const tr = document.createElement('tr');

    ['id', 'subject', 'priority'].forEach(function (field) {
        const td = document.createElement('td');
        td.textContent = ticket[field];
        tr.appendChild(td);
    });

    const statusTd = document.createElement('td');
    const select = document.createElement('select');
    STATUSES.forEach(function (s) {
        const option = document.createElement('option');
        option.value = s;
        option.textContent = s;
        option.selected = (s === ticket.status);
        select.appendChild(option);
    });
    const label = document.createElement('span');
    label.textContent = ' ' + ticket.status;

    select.addEventListener('change', async function () {
        try {
            const updated = await api('api_update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: ticket.id, status: select.value })
            });
            ticket.status = updated.status;
            label.textContent = ' ' + updated.status;
            showMessage('', false);
        } catch (err) {
            select.value = ticket.status; // повертаємо попереднє значення
            showMessage('Не вдалося змінити статус: ' + err.message, true);
        }
    });

    statusTd.appendChild(select);
    statusTd.appendChild(label);
    tr.appendChild(statusTd);
    return tr;
}

function renderList(tickets) {
    results.innerHTML = '';
    if (tickets.length === 0) {
        showRowMessage('Нічого не знайдено', false);
        return;
    }
    tickets.forEach(function (t) {
        results.appendChild(createRow(t));
    });
}

// Повідомлення в самому контейнері (крок 7)
function showRowMessage(text, isError) {
    results.innerHTML = '';
    const tr = document.createElement('tr');
    const td = document.createElement('td');
    td.colSpan = 4;
    td.textContent = text;
    if (isError) td.className = 'error';
    tr.appendChild(td);
    results.appendChild(tr);
}

// Лічильник, щоб пізня відповідь на старий запит не перезаписала новішу
let requestCounter = 0;

async function loadList(q) {
    const myRequest = ++requestCounter;
    try {
        const tickets = await api('api_list.php?q=' + encodeURIComponent(q || ''));
        if (myRequest !== requestCounter) return;
        renderList(tickets);
    } catch (err) {
        if (myRequest !== requestCounter) return;
        showRowMessage('Помилка завантаження: ' + err.message, true);
    }
}

// Живий пошук
search.addEventListener('input', function () {
    loadList(search.value);
});

// Додавання без перезавантаження
form.addEventListener('submit', async function (event) {
    event.preventDefault();
    const body = Object.fromEntries(new FormData(form));
    try {
        await api('api_add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        form.reset();
        showMessage('Тікет додано', false);
        loadList(search.value); // повторно завантажуємо список з урахуванням пошуку
    } catch (err) {
        showMessage('Не вдалося додати тікет: ' + err.message, true);
    }
});

// Початкове завантаження
loadList('');
