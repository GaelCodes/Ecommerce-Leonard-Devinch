import {
    Guard,
    ArtworkView,
    Catalogue,
    FilterView
} from "../../app/app.js";

$(document).ready(function() {
    // Inicializa prototipos
    ArtworkView.init();
    // Inicializa prototipos
    FilterView.init();
    // Solicita datos, presenta datos
    Catalogue.init();

    // Controla acceso y carga UI
    // dependiendo del estado del usuario (logged/notLogged)
    let page = "home";
    let autorizationRequired = false;
    Guard.init(page, autorizationRequired);
});