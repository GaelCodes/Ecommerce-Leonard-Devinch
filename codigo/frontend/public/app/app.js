export class User {
    constructor() {}

    logIn() {}
}

export class Artwork {
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
            price: this.price,
        };
    }
}

export class ArtworkView {
    constructor() {
        this.card = ArtworkView.cardPrototype.cloneNode(true);
    }

    static init() {
        ArtworkView.cardPrototype = document.createElement("div");
        ArtworkView.cardPrototype.classList.add(
            "card",
            "d-inline-flex",
            "col-12",
            "col-md-6",
            "col-lg-4",
            "my-4",
            "border-0",
            "p-3"
        );

        $(ArtworkView.cardPrototype).html(`

        <img src="..." class="artworkImage" alt="Cuadro no encontrado">

        <div class="card-body artworkBody">

            <div class="artworkDimensions">
                <p>ancho: <span class="dimensionX"></span> </p>
                <p>alto: <span class="dimensionY"></span> </p>
            </div>

            <div class="artworkDate">
                <p> Fecha: <span class="date"></span></p>
            </div>

            <div class="artworkStockAndCartButton">
                <button type="button" class="artworkCartButton btn btn-outline-primary">
                    <p> <span class="price"></span>€</p>
                    <i class="bi bi-cart"></i>
                </button>
                <div class="artworkStock">
                    <p>stock: <span class="availableStock"></span> / <span class="createdQuantity"></span></p>
                </div>      
            </div>

            <div class="artworkTitle"></div>

            <div class="artworkArtist"></div>

        </div>     

        `);
    }

    populate(artworkData) {
        $(this.card).find(".artworkImage")[0].src = artworkData.url;
        $(this.card).find(".artworkImage")[0].width = artworkData.dimensionX;
        $(this.card).find(".artworkImage")[0].heigth = artworkData.dimensionY;
        $(this.card)
            .find(".dimensionX")
            .text(artworkData.dimensionX + "cm");
        $(this.card)
            .find(".dimensionY")
            .text(artworkData.dimensionY + "cm");
        $(this.card).find(".artworkTitle").text(artworkData.title);
        $(this.card).find(".artworkArtist").text(artworkData.fullNameArtist);
        $(this.card).find(".artworkArtistEmail").text(artworkData.artistEmail);
        $(this.card).find(".artworkStartDate").text(artworkData.startDate);
        $(this.card).find(".date").text(artworkData.endDate);
        $(this.card).find(".availableStock").text(artworkData.availableStock);
        $(this.card).find(".createdQuantity").text(artworkData.createdQuantity);
        $(this.card).find(".price").text(artworkData.price);
    }

    update(artworkData) {
        $(this.card).find(".artworkTitle").text(artworkData.title);
        $(this.card).find(".artworkArtist").text(artworkData.fullNameArtist);
        $(this.card).find(".date").text(artworkData.endDate);
        $(this.card).find(".availableStock").text(artworkData.availableStock);
        $(this.card).find(".createdQuantity").text(artworkData.createdQuantity);
        $(this.card).find(".artworkImage")[0].src = artworkData.url;
        $(this.card).find(".artworkImage")[0].width = artworkData.dimensionX;
        $(this.card).find(".artworkImage")[0].heigth = artworkData.dimensionY;
        $(this.card)
            .find(".dimensionX")
            .text(artworkData.dimensionX + "cm");
        $(this.card)
            .find(".dimensionY")
            .text(artworkData.dimensionY + "cm");
        $(this.card).find(".price").text(artworkData.price);
    }
}

export class ArtworkController {
    constructor(artwork, artworkView) {
        this.artwork = artwork;
        this.artworkView = artworkView;

        this.artwork.registerObserver(this.artworkView);
        this.artworkView.populate(this.artwork.copy());
    }
}

export class Catalogue {
    constructor() {
        throw new Error("Can't instantiate abstract class!");
    }

    static init() {
        Catalogue.artworksSection = $("section.artworks");
        Catalogue.artworks = [];
        Catalogue.filteredArtworks = [];
        Catalogue.filter = FilterController;

        let falseBackend = "../falseBackend/artworks.json";
        let realBackend =
            "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/artworks-api/v1/";
        $.get(falseBackend, function(artworks) {
                // artworks = JSON.parse(artworks);
                console.log(artworks);
                artworks.forEach((artworkData) => {
                    let artwork = new Artwork(artworkData);
                    let artworkView = new ArtworkView();
                    let artworkController = new ArtworkController(artwork, artworkView);
                    Catalogue.artworks.push({
                        model: artwork,
                        view: artworkView,
                        controller: artworkController,
                    });
                });
            })
            .done(function() {
                console.log("Artworks recovered successfully");
                Catalogue.showAllArtworks();
            })
            .fail(function(failError) {
                console.log("Something were wrong while recovering artworks");
                console.log(failError);
            })
            .always(function() {
                console.log("Get artworks finished");
            });
    }

    static showAllArtworks() {
        Catalogue.artworks.forEach((artwork) => {
            Catalogue.artworksSection.append(artwork.view.card);
        });
    }
}

export class FilterController {
    constructor() {
        throw new Error("Can't instantiate abstract class!");
    }
}

export class FilterView {
    constructor() {
        throw new Error("Can't instantiate abstract class!");
    }

    static toggleFilterSidebar() {
        $("#filterSidebar").slideToggle("slow");
    }

    static init() {
        $("#expandFilterButton").click(FilterView.toggleFilterSidebar);
    }
}

export class ShoppingCart {
    constructor() {}
}

export class ShoppingCartItem {
    constructor() {}
}

export class PaymentManagerForStripe {
    constructor() {}
}