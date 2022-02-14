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
    ArtworkView.init();
    FilterView.init();
    Catalogue.init();
    UserController.init();

    // Load UIHome
    let userData = UserController.getUserData();
    if (userData) {
        UserController.loadLoggedUIHome(userData);
    } else {
        UserController.loadNotLoggedUIHome();
    }
});