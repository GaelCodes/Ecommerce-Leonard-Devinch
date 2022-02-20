import {
    User,
    UserController,
    Guard,
    Artwork,
    ArtworkView,
    ArtworkController,
    Catalogue,
    FilterController,
    FilterView,
    ShoppingCart,
} from "../../app/app.js";

$(document).ready(function() {
    // Controla acceso y carga UI
    // dependiendo del estado del usuario (logged/notLogged)
    let page = "payment-succeed";
    let autorizationRequired = true;
    Guard.init(page, autorizationRequired);
});