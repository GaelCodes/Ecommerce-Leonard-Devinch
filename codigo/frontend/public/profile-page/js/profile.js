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
        UserController.loadLoggedUIProfile(userData);
    } else {
        UserController.redirectHome();
    }
});