import {testModule} from "../main/testModule";
import '../../styles/_base.scss';
export function loadLoginPageModules() {
    testModule();
    const container = document.getElementById('container');
    const isRegistered = container.dataset.registrationSuccess;
    if (isRegistered === "1" || isRegistered === 1) {
        Swal.fire({
            icon: 'success',
            title: 'Inscription réussie !',
            text: 'Votre compte a bien été créé. Pour continuer, veuillez vous connecter.',
            confirmButtonText: 'Se connecter'
        });
    }}
