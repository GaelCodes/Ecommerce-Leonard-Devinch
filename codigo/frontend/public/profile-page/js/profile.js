import {
    Guard
} from "../../app/app.js";

$(document).ready(function() {
    // Controla acceso y carga UI
    // dependiendo del estado del usuario (logged/notLogged)
    let page = "profile";
    let autorizationRequired = true;
    Guard.init(page, autorizationRequired);
});