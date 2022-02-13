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
} from "../../app/app.js";

$(document).ready(function() {
    ShoppingCart.init();
    PaymentManager.init();
});