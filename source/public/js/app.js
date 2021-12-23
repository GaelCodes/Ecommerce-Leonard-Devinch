class User {
    constructor() {

    }

    logIn() {

    }
}

class LoggedUser {
    constructor() {
        this.username = username;
        this.userId = userId;
        // TODO: crear la cookie de carrito de la compra en el inicio
        // de session si no existe
        this.shoppingCart = new ShoppingCart(cookies.shoppingCart);
    }
}

class Artwork {
    constructor(artworkData) {
        this.title = artworkData.title;
        this.url = artworkData.url;
        this.fullNameArtist = artworkData.fullNameArtist;
        this.artistEmail = artworkData.artistEmail;
        this.startDate = artworkData.startDate;
        this.endDate = artworkData.endDate;
        this.availableStock = artworkData.availableStock;
        this.createdQuantity = artworkData.createdQuantity;
        this.dimensionX = artworkData.dimensionX;
        this.dimensionY = artworkData.dimensionY;
        this.price = artworkData.price;

        // TODO: Añadir observers
        this.observers = [];
        // TODO: Modificar setters si se va a estar suscrito
        // TODO: Añadir filtros a los setters
        // TODO: Añadir método suscribeToChanges()
    }

    registerObserver(observer) {
        this.observers.push(observer);
    }

    unRegisterObserver(observer) {
        let observerIndex = this.observers.indexOf(observer);
        this.observers.splice(observerIndex, 1);
    }

    notifyAll() {
        for (let i = 0; i < this.observers.length; i++) {
            this.observers[i].update(this.copy);
        }
    }

    copy() {
        return {
            title: this.title,
            url: this.url,
            fullNameArtist: this.fullNameArtist,
            artistEmail: this.artistEmail,
            startDate: this.startDate,
            endDate: this.endDate,
            availableStock: this.availableStock,
            createdQuantity: this.createdQuantity,
            dimensionX: this.dimensionX,
            dimensionY: this.dimensionY,
            price: this.price
        }
    }
}

class ArtworkView {
    constructor() {
        this.card = ArtworkView.cardPrototype.cloneNode(true);
    }

    static init() {
        ArtworkView.cardPrototype = document.createElement('div');
        ArtworkView.cardPrototype.classList.add('card');

        $(ArtworkView.cardPrototype).html(`
        <img src="..." class="artworkImage" alt="...">
        <div class="card-body">
          <h5 class="card-title artworkTitle">Card title</h5>
          <p class="card-text artworkFullNameArtist">Some text</p>
          <p class="card-text artworkArtistEmail">Some text</p>
          <p class="card-text artworkStartDate">Some text</p>
          <p class="card-text artworkEndDate">Some text</p>
          <p class="card-text artworkAvailableStock">Some text</p>
          <p class="card-text artworkCreatedQuantity">Some text</p>
          <p class="card-text artworkPrice">Some text</p>
          <a href="#" class="btn btn-primary artworkViewButton">View artwork</a>
        </div>     
        `);

    }

    populate(artworkData) {
        $(this.card).find('.artworkImage')[0].src = artworkData.url;
        $(this.card).find('.artworkImage')[0].width = artworkData.dimensionX;
        $(this.card).find('.artworkImage')[0].heigth = artworkData.dimensionY;
        $(this.card).find('.artworkTitle').text(artworkData.title);
        $(this.card).find('.artworkFullNameArtist').text(artworkData.fullNameArtist);
        $(this.card).find('.artworkArtistEmail').text(artworkData.artistEmail);
        $(this.card).find('.artworkStartDate').text(artworkData.startDate);
        $(this.card).find('.artworkEndDate').text(artworkData.endDate);
        $(this.card).find('.artworkAvailableStock').text(artworkData.availableStock);
        $(this.card).find('.artworkCreatedQuantity').text(artworkData.createdQuantity);
        $(this.card).find('.artworkPrice').text(artworkData.price);
    }

    update(artworkData) {
        $(this.card).find('.artworkTitle').text(artworkData.title);
        $(this.card).find('.artworkFullNameArtist').text(artworkData.fullNameArtist);
        $(this.card).find('.artworkArtistEmail').text(artworkData.artistEmail);
        $(this.card).find('.artworkStartDate').text(artworkData.startDate);
        $(this.card).find('.artworkEndDate').text(artworkData.endDate);
        $(this.card).find('.artworkAvailableStock').text(artworkData.availableStock);
        $(this.card).find('.artworkCreatedQuantity').text(artworkData.createdQuantity);
        $(this.card).find('.artworkPrice').text(artworkData.price);
    }
}

class ArtworkController {
    constructor(artwork, artworkView) {
        this.artwork = artwork;
        this.artworkView = artworkView;

        this.artwork.registerObserver(this.artworkView);
        this.artworkView.populate(this.artwork.copy());
    }
}

class Catalogue {
    constructor() {
        throw new Error("Can't instantiate abstract class!");
    }

    static init() {
        Catalogue.artworksSection = $('#artworksSection');
        Catalogue.artworks = [];
        Catalogue.filteredArtworks = [];
        Catalogue.filter = Filter;

        $.get("https://my.api.mockaroo.com/artworks.json?key=8e5d2ab0", function(artworks) {

            artworks.forEach(
                (artworkData) => {
                    let artwork = new Artwork(artworkData);
                    let artworkView = new ArtworkView();
                    let artworkController = new ArtworkController(artwork, artworkView);
                    Catalogue.artworks.push({ "model": artwork, "view": artworkView, "controller": artworkController });
                }
            );
        }).done(
            function() {
                console.log('Artworks recovered successfully');
                Catalogue.showAllArtworks();
            }
        ).fail(
            function() {
                console.log('Something were wrong while recovering artworks');
            }
        ).always(
            function() {
                console.log('Get artworks finished');
            }
        );

    }

    static showAllArtworks() {
        Catalogue.artworks.forEach(
            (artwork) => {
                Catalogue.artworksSection.append(artwork.view.card);
            }
        );
    }
}

class Filter {
    constructor() {
        throw new Error("Can't instantiate abstract class!");
    }
}

class ShoppingCart {
    constructor() {

    }
}

class ShoppingCartItem {
    constructor() {

    }
}

class PaymentManagerForStripe {
    constructor() {

    }
}




$(document).ready(function() {
    ArtworkView.init();
    Catalogue.init();
});