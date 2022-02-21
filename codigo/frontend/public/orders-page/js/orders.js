import {
    Guard,
    OrderView,
    OrderController,
} from "../../app/app.js";

$(document).ready(function() {
    // Controla acceso y carga UI
    // dependiendo del estado del usuario (logged/notLogged)
    let page = "orders";
    let autorizationRequired = true;
    Guard.init(page, autorizationRequired);

    // Inicializa variables
    OrderView.init();
    // Solicita las orders y las muestra
    OrderController.init();
});