import './bootstrap';

import Alpine from 'alpinejs';
import Gantt from 'frappe-gantt';
import Sortable from 'sortablejs';

// Make libraries globally available
window.Gantt = Gantt;
window.Alpine = Alpine;

Alpine.start();

// Initialize Kanban board if it exists on the page
if (document.getElementById('kanban-board')) {
    const columns = document.querySelectorAll('.kanban-column');
    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'bg-blue-100',
            onEnd: function (evt) {
                const itemEl = evt.item;
                const toColumn = evt.to;
                
                const taskId = itemEl.dataset.taskId;
                const newStatus = toColumn.dataset.status;

                fetch(`/tasks/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => {
                    if (!response.ok) {
                        // Revert the move on failure
                        evt.from.appendChild(itemEl);
                        alert('Failed to update task status.');
                    }
                })
                .catch(() => {
                    evt.from.appendChild(itemEl);
                    alert('An error occurred.');
                });
            },
        });
    });
}
