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
    ShoppingCartItem,
    ShoppingCartItemView,
    ShoppingCartItemController,
    PaymentManager,
    ShoppingCartController,
} from "../../app/app.js";

$(document).ready(function() {

    // Controla acceso y carga UI
    // dependiendo del estado del usuario (logged/notLogged)
    let page = "shopping-cart";
    let autorizationRequired = true;
    Guard.init(page, autorizationRequired);

    // Inicializa prototipos
    ShoppingCartItemView.init();
    // Transforma el item shoppingCart del localStorage en ShoppingCartItems(MVC)
    ShoppingCart.init();
    // Establece los EventListeners de los eventos de los usuarios
    ShoppingCartController.init();
    // Inicializa Stripe, establece los EventListeners de los eventos de los usuarios
    PaymentManager.init();
});