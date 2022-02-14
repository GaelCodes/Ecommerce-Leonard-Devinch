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
} from "../../app/app.js";

$(document).ready(function() {
    // Load UI - Profile
    let userData = UserController.getUserData();
    if (userData) {
        UserController.loadLoggedUIOrders(userData);
    } else {
        UserController.redirectHome();
    }
});