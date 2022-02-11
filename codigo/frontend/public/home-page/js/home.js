import {
    User,
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
});