import './bootstrap';
import Chart from 'chart.js/auto';

window.Chart = Chart;

// Global Toast Event Helper
window.notify = function (title, message = '', type = 'success') {
    window.dispatchEvent(new CustomEvent('toast', {
        detail: { title, message, type }
    }));
};
