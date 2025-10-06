import './bootstrap';

// Alpine.js (ya lo tienes)
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Bootstrap para componentes interactivos
import 'bootstrap';

// Chart.js para gráficos
import Chart from 'chart.js/auto';
window.Chart = Chart;

// QR Scanner para leer códigos QR
import QrScanner from 'qr-scanner';
window.QrScanner = QrScanner;

// Configuración global de Chart.js en español
Chart.defaults.locale = 'es';
Chart.defaults.plugins.tooltip.titleColor = '#fff';
Chart.defaults.plugins.tooltip.bodyColor = '#fff';