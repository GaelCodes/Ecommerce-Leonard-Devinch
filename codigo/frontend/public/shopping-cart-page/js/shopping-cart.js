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
    ShoppingCartItem,
    ShoppingCartItemView,
    ShoppingCartItemController,
    PaymentManager,
    ShoppingCartController,
} from "../../app/app.js";

$(document).ready(function() {
    let userData = UserController.getUserData();

    if (userData) {
        UserController.loadLoggedUIShoppingCart(userData);

        // Inicializa prototipos
        ShoppingCartItemView.init();
        // Transforma las cookies en items del carrito
        ShoppingCart.init();
        // Establece los EventListeners de los eventos de los usuarios
        ShoppingCartController.init();
        // Captura los eventos del usuario
        PaymentManager.init();
    } else {
        UserController.redirectHome();
    }
});