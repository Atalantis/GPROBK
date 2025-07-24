import './bootstrap';

import Alpine from 'alpinejs';
import Gantt from 'frappe-gantt';

// Make Gantt globally available
window.Gantt = Gantt;

window.Alpine = Alpine;

Alpine.start();
