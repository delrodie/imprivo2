import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
// Gestion du basculement de la barre latérale en mode desktop
document.getElementById('sidebar-toggle-desktop').addEventListener('click', function () {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('collapsed');
});

import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
