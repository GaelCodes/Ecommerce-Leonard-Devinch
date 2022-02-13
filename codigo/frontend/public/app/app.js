export class User {
    constructor() {}

    logIn() {}
}

export class UserController {
    constructor() {}

    static init() {
        // Patterns
        UserController.emailPattern = /[\w-]*@[\w-]*\.[\w]{2,6}/;
        UserController.passwordPattern = /.{5,16}/;

        // Register and login modals controls
        $("#registerButtonLink").click((e) => {
            $("#closeLoginModalButton").click();
            $("#registerButton").click();
        });

        $("#loginButtonLink").click(() => {
            $("#closeRegisterModalButton").click();
            $("#loginButton").click();
        });

        // Send login form
        $("#loginForm").submit((e) => {
            e.preventDefault();
            let email = $("#loginInputEmail").val();
            let password = $("#loginInputPassword").val();
            if (UserController.validateLoginForm(email, password)) {
                UserController.sendLoginForm(email, password);
            }
        });

        // Send register form
        $("#registerForm").submit((e) => {
            e.preventDefault();
            // Retrieve inputs data
            let email = $("#registerInputEmail").val();
            let password1 = $("#registerInputPassword1").val();
            let password2 = $("#registerInputPassword2").val();

            if (UserController.validateRegisterForm(email, password1, password2)) {
                // Send data
                UserController.sendRegisterForm(email, password1);
            }
        });

        // Send logout request
        $("#logoutButton").click(() => {
            UserController.sendLogout();
        });
    }

    static validateRegisterForm(email, password1, password2) {
        if (
            UserController.emailPattern.test(email) &&
            UserController.passwordPattern.test(password1) &&
            password1 === password2
        ) {
            // Valid input data

            $("#registerErrorMessage").text("");
            return true;
        } else {
            // Not valid input data

            $("#registerErrorMessage").text(
                "El email y la contraseña no cumplen con el patrón requerido."
            );
            return false;
        }
    }

    static sendRegisterForm(email, password1, fullName = null) {
        let userData = {
            client_email: email,
            password: password1,
            full_name: fullName,
        };

        var request = $.ajax({
            url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/clients-api/v1/register/",
            method: "POST",
            // Type of data send to the server
            contentType: "application/json; charset=UTF-8",
            data: JSON.stringify(userData),
            // Expected type of data received from server response
            dataType: "json",
        });

        request.done((data, textStatus) => {
            // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Registrado en verde
            let messageSucceed = data.message;
            $("#registerSucceedMessage").text(textStatus + " : " + messageSucceed);
        });

        request.fail((data, textStatus) => {
            // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Registrado en verde
            let messageError = data.responseJSON.message;
            $("#registerErrorMessage").text(textStatus + " : " + messageError);
        });
    }

    static validateLoginForm(email, password) {
        if (
            UserController.emailPattern.test(email) &&
            UserController.passwordPattern.test(password)
        ) {
            // Valid input data

            $("#loginErrorMessage").text("");
            return true;
        } else {
            // Not Valid input data

            $("#loginErrorMessage").text(
                "El email y la contraseña no cumplen con el patrón requerido."
            );
            return false;
        }
    }

    static sendLoginForm(email, password) {
        let userData = {
            client_email: email,
            password: password,
        };

        var request = $.ajax({
            url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/clients-api/v1/login/",
            method: "POST",
            // Type of data send to the server
            contentType: "application/json; charset=UTF-8",
            data: JSON.stringify(userData),
            // Expected type of data received from server response
            dataType: "json",

            // Uncomment this for securized requests
            // xhrFields: {
            //     withCredentials: true,
            // },
        });

        request.done((data, textStatus) => {
            // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Logueado en verde
            // TODO: Después de ~1s de cambiar a logueado, Cerrar login modal
            // Animation -> when end -> $("#closeLoginModalButton").click();
            let messageSucceed = data.message;
            $("#loginSucceedMessage").text(textStatus + " : " + messageSucceed);
            $("#closeLoginModalButton").click();

            // Save userData
            let userData = data.userData;
            UserController.saveUserData(userData);

            // LoadLoggedUIHome
            UserController.loadLoggedUIHome();
        });

        request.fail((data, textStatus) => {
            // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Incorrecto en rojo
            let messageError = data.responseJSON.message;
            $("#loginErrorMessage").text(textStatus + " : " + messageError);
        });
    }

    static sendLogout() {
        // TODO: Solicitar borrado de cookie al backend
        var request = $.ajax({
            url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/clients-api/v1/logout/",
            method: "POST",
            // Expected type of data received from server response
            dataType: "json",

            // Uncomment this for securized requests
            xhrFields: {
                withCredentials: true,
            },
        });

        request.done((data, textStatus) => {
            // TODO: Borrar los datos del localStorage
            localStorage.removeItem("userData");
            alert("Sesión cerrada correctamente");

            // LoadNotLoggedUIHome
            UserController.loadNotLoggedUIHome();
        });

        request.fail((data, textStatus) => {
            alert("No se ha podido cerrar sesión correctamente");
        });
    }

    static saveUserData(userData) {
        localStorage.setItem("userData", JSON.stringify(userData));
    }

    static loadLoggedUIHome() {
        let userData = UserController.getUserData();

        // Ocultar boton inicio de sesión
        $("#loginButton").addClass("d-none");

        // Rellenar botón user email dropdown
        $("#userDropdownMenu").text(userData.email);

        // Mostrar boton user email dropdown
        $("#userDropdownMenu").parent().removeClass("d-none");
    }

    static loadNotLoggedUIHome() {
        // Mostrar boton inicio de sesión
        $("#loginButton").removeClass("d-none");

        // Resetear botón user email dropdown
        $("#userDropdownMenu").text("example@hotmail.com");

        // Ocultar boton user email dropdown
        $("#userDropdownMenu").parent().addClass("d-none");
    }

    static getUserData() {
        let userData = localStorage.getItem("userData");
        userData = JSON.parse(userData);
        return userData;
    }
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

        <img src="https://dummyimage.com/176x100.png/dddddd/000000" class="artworkImage" alt="Cuadro no encontrado">

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
                Catalogue.showAllArtworks();
            })
            .fail(function(failError) {
                console.log("Something were wrong while recovering artworks");
                console.log(failError);
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