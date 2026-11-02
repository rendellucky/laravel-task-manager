import Sortable from 'sortablejs';

document.querySelectorAll('[data-auto-submit]').forEach((select) => {
    select.addEventListener('change', () => select.form.submit());
});

const taskList = document.getElementById('task-list');

if (taskList) {
    Sortable.create(taskList, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'opacity-40',
        onEnd: ({ oldIndex, newIndex }) => {
            if (oldIndex !== newIndex) {
                saveOrder(taskList);
            }
        },
    });
}

async function saveOrder(list) {
    const items = [...list.querySelectorAll('[data-task-id]')];

    const response = await fetch(list.dataset.reorderUrl, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
        },
        body: JSON.stringify({
            project_id: list.dataset.projectId || null,
            tasks: items.map((item) => Number(item.dataset.taskId)),
        }),
    });

    if (!response.ok) {
        const { message } = await response.json().catch(() => ({}));
        alert(message ?? 'Could not save the new order.');
        window.location.reload();

        return;
    }

    items.forEach((item, index) => {
        item.querySelector('[data-priority]').textContent = index + 1;
    });
}
