// -------- detect user disconnection --------
// import './js/ajax/handleDeadSession.js'
import 'bootstrap';
import '@popperjs/core';
import 'sweetalert2/dist/sweetalert2.min.css';
import '@mdi/font/css/materialdesignicons.min.css';

//-------- SCSS --------
import './styles/app.scss';

const routeHandlers = {
    'homePage': () => import('./js/pages/homePage').then(module => module.loadHomePageModules()),
    'storePage': () => import('./js/pages/storePage').then(module => module.loadStorePageModules()),
    'productTemplate': () => import('./js/pages/productTemplate').then(module => module.loadProductTemplateModules()),
    'registrationPage': () => import('./js/pages/registrationPage').then(module => module.loadRegistrationPageModules()),
    'loginPage': () => import('./js/pages/loginPage').then(module => module.loadLoginPageModules()),
    'additionalRegistrationForm': () => import('./js/pages/additionalRegistrationForm').then(module => module.loadAdditionalRegistrationFormModules()),
    'userDashboard': () => import('./js/pages/userDashboard').then(module => module.loadUserDashboardModules()),
};

function initializePageScripts() {
    const currentRoute = document.body.getAttribute('data-route');
    console.log(currentRoute, 'modules loaded');
    const routeHandler = routeHandlers[currentRoute];
    
    if (routeHandler) {
        routeHandler().catch(error => {
            console.error(`Failed to load modules for route ${currentRoute}:`, error);
        });
    } else {
        console.warn('No specific scripts for this route.');
    }
}

document.addEventListener('DOMContentLoaded', initializePageScripts);
