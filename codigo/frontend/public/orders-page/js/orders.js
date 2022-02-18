import {
    User,
    UserController,
    Artwork,
    ArtworkView,
    ArtworkController,
    Catalogue,
    FilterController,
    FilterView,
    ShoppingCart,
    OrderView,
    OrderController,
} from "../../app/app.js";

$(document).ready(function() {
    // Load UI - Profile
    let userData = UserController.getUserData();
    if (userData) {
        // Carga la barra de navegación
        UserController.loadLoggedUIOrders(userData);
        // Inicializa variables
        OrderView.init();
        // Solicita las orders y las muestra
        OrderController.init();
    } else {
        UserController.redirectHome();
    }
});