import './bootstrap';

import Alpine from 'alpinejs';
import Gantt from 'frappe-gantt';
import Sortable from 'sortablejs';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import frLocale from '@fullcalendar/core/locales/fr';

// Expose libraries globally
window.Gantt = Gantt;
window.Alpine = Alpine;

Alpine.start();

// Wait for DOM content before initialising UI widgets
document.addEventListener('DOMContentLoaded', () => {
    // Kanban board
    const board = document.getElementById('kanban-board');
    if (board) {
        document.querySelectorAll('.kanban-column').forEach(column => {
            new Sortable(column, {
                group: 'kanban',
                animation: 150,
                ghostClass: 'bg-blue-100',
                onEnd(evt) {
                    const itemEl = evt.item;
                    const toColumn = evt.to;

                    const taskId = itemEl.dataset.taskId;
                    const newStatus = toColumn.dataset.status;

                    fetch(`/tasks/${taskId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ status: newStatus }),
                    })
                        .then((response) => {
                            if (!response.ok) {
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

    // Calendar view
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin],
            initialView: 'dayGridMonth',
            locale: frLocale,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek',
            },
            events: calendarEl.dataset.eventsUrl,
            eventClick(info) {
                info.jsEvent.preventDefault();
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            },
        });
        calendar.render();
    }
});
