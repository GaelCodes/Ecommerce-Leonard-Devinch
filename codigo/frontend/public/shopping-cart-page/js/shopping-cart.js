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
    PaymentManager,
    ShoppingCartController,
} from "../../app/app.js";

$(document).ready(function() {
    let userData = UserController.getUserData();

    if (userData) {
        UserController.loadLoggedUIShoppingCart(userData);
        ShoppingCartController.init();
        PaymentManager.init();
    } else {
        UserController.redirectHome();
    }
});