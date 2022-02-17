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
        // Transforma el item shoppingCart del localStorage en ShoppingCartItems(MVC)
        ShoppingCart.init();
        // Establece los EventListeners de los eventos de los usuarios
        ShoppingCartController.init();
        // Inicializa Stripe, establece los EventListeners de los eventos de los usuarios
        PaymentManager.init();
    } else {
        UserController.redirectHome();
    }
});