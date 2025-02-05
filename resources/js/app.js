import './bootstrap';
import './notifications';

// Make user ID available globally (add this in your layout)
window.userId = document.querySelector('meta[name="user-id"]').content;


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
