// resources/js/app.js
import './bootstrap';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Bootstrap
import 'bootstrap';

// Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;
Chart.defaults.locale = 'es';
Chart.defaults.plugins.tooltip.titleColor = '#fff';
Chart.defaults.plugins.tooltip.bodyColor = '#fff';

// QR Scanner
import QrScanner from 'qr-scanner';
window.QrScanner = QrScanner;