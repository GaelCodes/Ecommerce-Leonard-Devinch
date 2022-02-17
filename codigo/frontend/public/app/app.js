export class User {
    constructor() {}
}

export class UserController {
    constructor() {}

    static init() {}

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

        // LOGIN CON FETCH

        fetch(
                "https://backend.ecommerce-leonard-devinch.abigaelheredia.es:443/apis/clients-api/v1/login/", {
                    method: "POST",
                    headers: {
                        // Type of data send to the server
                        "Content-Type": "application/json; charset=UTF-8",
                    },

                    body: JSON.stringify(userData),

                    // Expected type of data received from server response
                    dataType: "json",
                    credentials: "include",
                    redirect: "follow",
                }
            )
            .then((res) => res.json())
            .then((res) => {
                console.log(res);
                //console.log(response.headers.get("Set-Cookie")); // undefined

                // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Logueado en verde
                // TODO: Después de ~1s de cambiar a logueado, Cerrar login modal
                // Animation -> when end -> $("#closeLoginModalButton").click();

                let messageSucceed = res.message;
                $("#loginSucceedMessage").text("Succeed" + " : " + messageSucceed);
                $("#closeLoginModalButton").click();

                // Save userData

                let userData = res.userData;
                UserController.saveUserData(userData);

                // LoadLoggedUIHome

                UserController.loadLoggedUIHome(userData);
            })
            .catch((error) => {
                // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Incorrecto en rojo
                console.log(error);
                let messageError = error;
                $("#loginErrorMessage").text(textStatus + " : " + messageError);
            });

        // LOGIN CON AJAX

        // var request = $.ajax({
        //     url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es:443/apis/clients-api/v1/login/",
        //     method: "POST",
        //     // Type of data send to the server
        //     contentType: "application/json; charset=UTF-8",
        //     data: JSON.stringify(userData),
        //     // Expected type of data received from server response
        //     dataType: "json",

        //     // Uncomment this for securized requests
        //     xhrFields: {
        //         withCredentials: true,
        //         credentials: "include",
        //     },
        // });

        // request.done((data, textStatus) => {
        //     // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Logueado en verde
        //     // TODO: Después de ~1s de cambiar a logueado, Cerrar login modal
        //     // Animation -> when end -> $("#closeLoginModalButton").click();
        //     let messageSucceed = data.message;
        //     $("#loginSucceedMessage").text(textStatus + " : " + messageSucceed);
        //     $("#closeLoginModalButton").click();

        //     // Save userData
        //     let userData = data.userData;
        //     UserController.saveUserData(userData);

        //     // LoadLoggedUIHome
        //     UserController.loadLoggedUIHome(userData);
        // });

        // request.fail((data, textStatus) => {
        //     // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Incorrecto en rojo
        //     let messageError = data.responseJSON.message;
        //     $("#loginErrorMessage").text(textStatus + " : " + messageError);
        // });
    }

    static sendLogout() {
        // Solicitar borrado de cookie al backend
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
            UserController.redirectHome();
        });
        request.fail((data, textStatus) => {
            alert("No se ha podido cerrar sesión correctamente");
        });
    }

    static saveUserData(userData) {
        localStorage.setItem("userData", JSON.stringify(userData));
    }

    static loadNotLoggedUIHome() {
        // Patterns
        UserController.emailPattern = /[\w-]*@[\w-]*\.[\w]{2,6}/;
        UserController.passwordPattern = /.{5,16}/;

        // Mostrar boton inicio de sesión
        $("#loginButton").removeClass("d-none");

        // Resetear botón user email dropdown
        $("#userDropdownMenu").text("example@hotmail.com");

        // Ocultar boton user email dropdown
        $("#userDropdownMenu").parent().addClass("d-none");

        // EventListener Register and login modals controls link
        $("#registerButtonLink").click((e) => {
            $("#closeLoginModalButton").click();
            $("#registerButton").click();
        });

        $("#loginButtonLink").click(() => {
            $("#closeRegisterModalButton").click();
            $("#loginButton").click();
        });

        // EventListener Send login form
        $("#loginForm").submit((e) => {
            e.preventDefault();
            let email = $("#loginInputEmail").val();
            let password = $("#loginInputPassword").val();
            if (UserController.validateLoginForm(email, password)) {
                UserController.sendLoginForm(email, password);
            }
        });

        // EventListener Send register form
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
    }

    static loadLoggedUIHome(userData) {
        // Ocultar boton inicio de sesión
        $("#loginButton").addClass("d-none");

        // Rellenar botón user email dropdown
        $("#userDropdownMenu").text(userData.email);

        // Mostrar boton user email dropdown
        $("#userDropdownMenu").parent().removeClass("d-none");

        // EventListener Send logout request
        $("#logoutButton").click(() => {
            UserController.sendLogout();
        });
    }

    static loadLoggedUIProfile(userData) {
        // Rellenar botón user email dropdown
        $("#userDropdownMenu").text(userData.email);

        // EventListener Send logout request
        $("#logoutButton").click(() => {
            UserController.sendLogout();
        });

        // Mostrar datos del usuario
        $("#inputUserEmail").val(userData.email);
        $("#inputFullName").val(userData.fullName);
        $("#inputTelephoneNumber").val(userData.telephoneNumber);
        $("#inputShippingAddress").val(userData.shippingAddress);

        // EventListener actualizar datos de usuario

        $("#updateProfileButton").click(() => {
            let profileData = {
                fullName: $("#inputFullName").val(),
                telephoneNumber: $("#inputTelephoneNumber").val(),
                shippingAddress: $("#inputShippingAddress").val(),
            };
            UserController.validateProfileForm(profileData);
            UserController.sendUpdateProfileForm(profileData);
        });
    }

    static loadLoggedUIOrders(userData) {
        // Rellenar botón user email dropdown
        $("#userDropdownMenu").text(userData.email);

        // EventListener Send logout request
        $("#logoutButton").click(() => {
            UserController.sendLogout();
        });

        // TODO: Recuperar lista de pedidos
        //let orders = OrdersController.getOrders();
        // TODO: Mostrar Lista de pedidos
        //OrdersController.showOrders(orders);
    }

    static loadLoggedUIShoppingCart(userData) {
        // Rellenar botón user email dropdown
        $("#userDropdownMenu").text(userData.email);

        // EventListener Send logout request
        $("#logoutButton").click(() => {
            UserController.sendLogout();
        });
    }

    static getUserData() {
        let userData = localStorage.getItem("userData");
        userData = JSON.parse(userData);
        return userData;
    }

    static sendUpdateProfileForm(profileData) {
        var request = $.ajax({
            url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/clients-api/v1/update_profile/",
            method: "POST",
            // Type of data send to the server
            contentType: "application/json; charset=UTF-8",
            data: JSON.stringify(profileData),
            // Expected type of data received from server response
            dataType: "json",

            // Uncomment this for securized requests
            xhrFields: {
                withCredentials: true,
            },
        });

        request.beforeSend(() => {
            $("#updateSuccedMessage").val("");
            $("#updateErrorMessage").val("");
        });

        request.done((data, textStatus) => {
            // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Actualizado en verde
            //

            let messageSucceed = data.message;
            $("#updateSuccedMessage").text(textStatus + " : " + messageSucceed);

            // Save userData
            let userData = data.userData;
            UserController.saveUserData(userData);
        });

        request.fail((data, textStatus) => {
            // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a No actualizado en rojo
            let messageError = data.responseJSON.message;
            $("#updateErrorMessage").text(textStatus + " : " + messageError);
        });
    }

    static validateProfileForm(profileData) {}

    static redirectHome() {
        location = "../home-page/home.html";
        //location = "https://ecommerce-leonard-devinch.web.app/home-page/home.html";
    }
}

export class Artwork {
    constructor(artworkData) {
        this.title = artworkData.title;
        this.url = artworkData.url;
        this.artist = artworkData.artist;
        this.starting_date = artworkData.starting_date;
        this.ending_date = artworkData.ending_date;
        this.available_quantity = artworkData.available_quantity;
        this.created_quantity = artworkData.created_quantity;
        this.dimension_x = artworkData.dimension_x;
        this.dimension_y = artworkData.dimension_y;
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
            artist: this.artist,
            starting_date: this.starting_date,
            ending_date: this.ending_date,
            available_quantity: this.available_quantity,
            created_quantity: this.created_quantity,
            dimension_x: this.dimension_x,
            dimension_y: this.dimension_y,
            price: this.price,
        };
    }
}
export class ArtworkView {
    constructor() {
        this.card = ArtworkView.cardPrototype.cloneNode(true);
        this.addCartButton = $(this.card).find(".artworkCartButton");
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
        $(this.card).find(".artworkImage")[0].width = artworkData.dimension_x;
        $(this.card).find(".artworkImage")[0].heigth = artworkData.dimension_y;
        $(this.card)
            .find(".dimensionX")
            .text(artworkData.dimension_x + "cm");
        $(this.card)
            .find(".dimensionY")
            .text(artworkData.dimension_y + "cm");
        $(this.card).find(".artworkTitle").text(artworkData.title);
        $(this.card).find(".artworkArtist").text(artworkData.artist.full_name);
        $(this.card)
            .find(".artworkArtistEmail")
            .text(artworkData.artist.artist_email);
        $(this.card).find(".artworkStartDate").text(artworkData.starting_date);
        $(this.card).find(".date").text(artworkData.endDate);
        $(this.card).find(".availableStock").text(artworkData.available_quantity);
        $(this.card).find(".createdQuantity").text(artworkData.created_quantity);
        $(this.card).find(".price").text(artworkData.price);
    }

    update(artworkData) {
        $(this.card).find(".artworkTitle").text(artworkData.title);
        $(this.card).find(".artworkArtist").text(artworkData.artist.full_name);
        $(this.card).find(".date").text(artworkData.ending_date);
        $(this.card).find(".availableStock").text(artworkData.available_quantity);
        $(this.card).find(".createdQuantity").text(artworkData.created_quantity);
        $(this.card).find(".artworkImage")[0].src = artworkData.url;
        $(this.card).find(".artworkImage")[0].width = artworkData.dimension_x;
        $(this.card).find(".artworkImage")[0].heigth = artworkData.dimension_y;
        $(this.card)
            .find(".dimensionX")
            .text(artworkData.dimension_x + "cm");
        $(this.card)
            .find(".dimensionY")
            .text(artworkData.dimension_y + "cm");
        $(this.card).find(".price").text(artworkData.price);
    }
}
export class ArtworkController {
    constructor(artwork, artworkView) {
        this.artwork = artwork;
        this.artworkView = artworkView;

        this.artwork.registerObserver(this.artworkView);
        this.artworkView.populate(this.artwork.copy());

        // EventListeners

        $(this.artworkView.addCartButton).click(() => {
            this.addToShoppingCart();
        });
    }

    addToShoppingCart() {
        // TODO: ¿Seria más conveniente usar cookies o localstorage?
        // Creo que sería mejor usar localStorage, más capacidad, y no se envían con cada petición

        // Convertir la cookie actual a objeto

        let shoppingCartStorage = localStorage.getItem("shoppingCart");
        console.log("Carrito sin iniciar:", shoppingCartStorage);
        shoppingCartStorage =
            shoppingCartStorage === null ? [] : JSON.parse(shoppingCartStorage);

        // shoppingCartStorage is an array with data like this =
        //
        // [
        //     { artistEmail: "hola@asds.com", title: "Hola mundo", units: 21 },
        //     { artistEmail: "hola@asds.com", title: "Hola mundo", units: 21 },
        // ];

        // Comprobar si ya estaba añadida
        let artworkIndex = shoppingCartStorage.findIndex((selectedArtwork) => {
            return (
                selectedArtwork.title == this.artwork.title &&
                selectedArtwork.artistEmail == this.artwork.artistEmail
            );
        });

        if (artworkIndex === -1) {
            shoppingCartStorage.push({
                artistEmail: this.artwork.artist.artist_email,
                title: this.artwork.title,
                units: 1,
            });

            localStorage.setItem("shoppingCart", JSON.stringify(shoppingCartStorage));
        }
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

        $.post(
                "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/artworks-api/v1/select_artworks/",
                function(artworks) {
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
                }
            )
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

export class Filter {
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
export class FilterController {
    constructor() {
        throw new Error("Can't instantiate abstract class!");
    }
}

export class ShoppingCart {
    constructor() {}

    static async init() {
        ShoppingCart.items = [];

        let shoppingCartStorage = localStorage.getItem("shoppingCart");
        shoppingCartStorage = JSON.parse(shoppingCartStorage);

        // ShoppingCartCookie is an array with data like this =
        //
        // [
        //     { artist_email: "hola@asds.com", title: "Hola mundo", units: 21 },
        //     { artist_email: "hola@asds.com", title: "Hola mundo", units: 21 },
        // ];

        // Obtener la info de las obras de arte desde el backend
        let selection = shoppingCartStorage;

        let artworks = await ShoppingCartController.retrieveShoppingCartArtworks(
            selection
        );

        // Resetear el container
        $("#shoppingCartItemsContainer").html("");

        // Crear los shoppingCartItems
        artworks.forEach((artworkData) => {
            // Aquí busco y asocio obras de arte con unidades
            let selectionIndex = selection.findIndex((selectedArtwork) => {
                return (
                    selectedArtwork.title == artworkData.title &&
                    selectedArtwork.artistEmail == artworkData.artist.artist_email
                );
            });

            artworkData.units = selection[selectionIndex].units;

            let shoppingCartItem = new ShoppingCartItem(
                artworkData.title,
                artworkData.artist,
                artworkData.price,
                artworkData.units,
                artworkData.available_quantity,
                artworkData.url,
                artworkData.dimension_x,
                artworkData.dimension_y
            );

            let shoppingCartItemView = new ShoppingCartItemView();

            // TODO: Mostrar shoppingCartItem en el populate del view
            let shoppingCartItemController = new ShoppingCartItemController(
                shoppingCartItem,
                shoppingCartItemView
            );

            ShoppingCart.items.push({
                model: shoppingCartItem,
                view: shoppingCartItemView,
                controller: shoppingCartItemController,
            });
        });
    }
}
export class ShoppingCartController {
    constructor() {}

    static init() {
        // TODO: Recuperar carrito
        //let shoppingCartItems = ShoppingCart.items;
        // TODO: Mostrar Items del carrito
        //ShoppingCartController.showItems(shoppingCartItems);

        // Events Listeners
        $("#confirmOrderButton").click(() => {
            // TODO: Retrieve cookie shopping-cart
            // TODO: Sale carrito hacia la izquierda
            $("#shoppingCartCard").toggle("slide");
            // TODO: Entra Payment manager desde la derecha
            $("#PaymentManagerCard").toggle("slide", { direction: "right" });
            // TODO: Send form -> if succeed enable payment inputs

            ShoppingCartController.sendOrder();
            //ShoppingCart.sendOrder(orderData);
        });
    }

    static sendOrder() {
        let orderData = localStorage.getItem("shoppingCart");

        var request = $.ajax({
            url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/clients-api/v1/make_order/",
            method: "POST",
            // Type of data send to the server
            contentType: "application/json; charset=UTF-8",
            data: orderData,
            // Expected type of data received from server response
            dataType: "json",

            // Uncomment this for securized requests
            xhrFields: {
                withCredentials: true,
            },
        });

        request.done((data, textStatus) => {
            // TODO: if succeed enable payment inputs
            let messageSucceed = data.message;
            console.log(messageSucceed, textStatus);

            console.log("Secreto del client: ", data.client_secret);
            PaymentManager.createPaymentElements(data.client_secret);
            //$("#updateSuccedMessage").text(textStatus + " : " + messageSucceed);
        });

        request.fail((data, textStatus) => {
            // TODO: if not succeed inform error
            let messageError = data.responseJSON.message;

            // if (messageError === "Invalid credentials") {
            //     UserController.redirectHome();
            // }
            console.log(messageError, textStatus);
            //$("#updateErrorMessage").text(textStatus + " : " + messageError);
        });
    }

    static async retrieveShoppingCartArtworks(selection) {
        try {
            var result = await $.ajax({
                url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/artworks-api/v1/retrieve_selected_artworks/",
                method: "POST",
                // Type of data send to the server
                contentType: "application/json; charset=UTF-8",
                data: JSON.stringify(selection),
                // Expected type of data received from server response
                dataType: "json",
                // Uncomment this for securized requests
                xhrFields: {
                    withCredentials: true,
                },
            });

            return result;
        } catch (error) {
            console.error(error);
        }
    }
}

export class ShoppingCartItem {
    constructor(
        title,
        artist,
        price,
        units,
        available_quantity,
        url,
        dimension_x,
        dimension_y
    ) {
        this.observers = [];
        this.title = title;
        this.artist = artist;
        this.price = price;
        this.units = units;
        this.available_quantity = available_quantity;
        this.url = url;
        this.dimension_x = dimension_x;
        this.dimension_y = dimension_y;
    }

    set units(units) {
        // TODO: Update corresponding cookie units
        this._units = units;
        this.notifyAll();
    }

    get units() {
        return this._units;
    }

    copy() {
        return {
            title: this.title,
            artist: this.artist,
            price: this.price,
            units: this.units,
            url: this.url,
            dimension_x: this.dimension_x,
            dimension_y: this.dimension_y,
        };
    }

    registerObserver(observer) {
        this.observers.push(observer);
    }

    unRegisterObserver(observer) {
        let observerIndex = this.observers.indexOf(observer);

        this.observers.splice(observerIndex, 1);
    }

    notifyAll() {
        for (let index = 0; index < this.observers.length; index++) {
            const observer = this.observers[index];

            observer.update(this.copy());
        }
    }
}

export class ShoppingCartItemView {
    constructor() {
        this.card = ShoppingCartItemView.cardPrototype.cloneNode(true);
        this.card.classList.remove("d-none");
    }

    static init() {
        // TODO: Esta forma de crear los prototipos no me gusta
        // es dificil de interpretar, refactorizar
        ShoppingCartItemView.cardPrototype = document.getElementById(
            "shoppingCartItem-CardPrototype"
        );
    }

    populate(shoppingCartItem) {
        this.card.querySelector(".artworkImage").src = shoppingCartItem.url;
        this.card.querySelector(".artworkImage").width =
            shoppingCartItem.dimension_x;
        this.card.querySelector(".artworkImage").heigth =
            shoppingCartItem.dimension_y;

        this.card.querySelector(".artworkTitle").innerText = shoppingCartItem.title;
        this.card.querySelector(".artworkArtist").innerText =
            shoppingCartItem.artist.full_name;

        // TODO: Crear un hueco en el diseño para las cantidades disponibles
        // $(this.card).find(".availableStock").text(artworkData.availableStock);

        this.card.querySelector(".artworkPrice").innerText =
            shoppingCartItem.price + " €";
        this.card.querySelector(".artworkUnits").innerText = shoppingCartItem.units;

        let total = shoppingCartItem.price * shoppingCartItem.units;

        this.card.querySelector(".artworkTotal").innerText = total + " €";

        // TODO: Añadir al container
        let shoppingCartItemsContainer = document.getElementById(
            "shoppingCartItemsContainer"
        );
        shoppingCartItemsContainer.append(this.card);
    }

    update(shoppingCartItem) {
        $(this.card).find(".artworkUnits").text(shoppingCartItem.units);

        let total = shoppingCartItem.price * shoppingCartItem.units;
        total = total.toFixed(2);
        $(this.card)
            .find(".artworkTotal")
            .text(total + " €");
    }
}

export class ShoppingCartItemController {
    constructor(shoppingCartItem, shoppingCartItemView) {
        this.shoppingCartItem = shoppingCartItem;
        this.shoppingCartItemView = shoppingCartItemView;

        this.shoppingCartItemView.populate(this.shoppingCartItem.copy());
        this.shoppingCartItem.registerObserver(this.shoppingCartItemView);

        // EventListeners

        $(this.shoppingCartItemView.card)
            .find(".deleteButton")
            .click(() => {
                this.deleteShoppingCartItem();
            });

        $(this.shoppingCartItemView.card)
            .find(".modifyUnitsButton")
            .click((event) => {
                this.modifyUnits(event);
            });
    }

    deleteShoppingCartItem() {
        let shoppingCartStorage = localStorage.getItem("shoppingCart");
        shoppingCartStorage = JSON.parse(shoppingCartStorage);

        // ShoppingCartCookie is an array with data like this =
        //
        // [
        //     { artistEmail: "hola@asds.com", title: "Hola mundo", units: 21 },
        //     { artistEmail: "hola@asds.com", title: "Hola mundo", units: 21 },
        // ];

        // Comprobar si ya estaba añadida
        let artworkIndex = shoppingCartStorage.findIndex((selectedArtwork) => {
            return (
                selectedArtwork.title == this.shoppingCartItem.title &&
                selectedArtwork.artistEmail == this.shoppingCartItem.artist.artist_email
            );
        });

        shoppingCartStorage.splice(artworkIndex, 1);
        localStorage.setItem("shoppingCart", JSON.stringify(shoppingCartStorage));

        delete this.shoppingCartItem;
        this.shoppingCartItemView.card.remove();
        delete this;
    }

    modifyUnits(event) {
        let buttonValue = event.currentTarget.value;

        let updatedUnits = eval(this.shoppingCartItem.units + buttonValue + 1);

        if (
            updatedUnits > 0 &&
            updatedUnits <= this.shoppingCartItem.available_quantity
        ) {
            console.log("Nuevo valor: ", updatedUnits);
            this.shoppingCartItem.units = updatedUnits;
        } else {
            console.log("Quieres : ", updatedUnits);
            console.log("Hay : ", this.shoppingCartItem.available_quantity);
        }
    }
}

export class PaymentManager {
    constructor() {}

    static init() {
        // Set your publishable key: remember to change this to your live publishable key in production
        // See your keys here: https://dashboard.stripe.com/apikeys
        PaymentManager.stripe = Stripe(
            "pk_test_51Jz75mCaANM1wgcDUugV3UxjJ8q2uwoygyEiV2eMZ357KYWytnXd6Pat0CrI2nbGYDyw7H5rPMy3it84kGC2Q3Op00H1iIkGfC"
        );

        $("#backToShoppingCartButton").click(() => {
            // TODO: Entra carrito desde la derecha
            $("#shoppingCartCard").toggle("slide");
            // TODO: Sale Payment manager hacia la derecha
            $("#PaymentManagerCard").toggle("slide", { direction: "right" });
            // TODO: Send form -> if succeed enable payment inputs
        });

        $("#payment-form").submit((event) => {
            event.preventDefault();
            PaymentManager.sendConfirmPayment();
        });
    }

    static createPaymentElements(clientSecret) {
        // Set up Stripe.js and Elements to use in checkout form
        PaymentManager.elements = PaymentManager.stripe.elements({
            clientSecret: clientSecret,
        });

        var style = {
            base: {
                iconColor: "#c4f0ff",
                color: "#0d6efd",
                fontWeight: "500",
                fontFamily: "Roboto, Open Sans, Segoe UI, sans-serif",
                fontSize: "16px",
                fontSmoothing: "antialiased",
                ":-webkit-autofill": {
                    color: "#fce883",
                },
                "::placeholder": {
                    color: "#87BBFD",
                },
            },
            invalid: {
                iconColor: "#FFC7EE",
                color: "#FFC7EE",
            },
        };

        PaymentManager.paymentElement = PaymentManager.elements.create("payment");
        PaymentManager.paymentElement.mount("#payment-element");

        $("#submit").prop("disabled", false);
    }

    static async sendConfirmPayment() {
        const { error } = await PaymentManager.stripe.confirmPayment({
            elements: PaymentManager.elements,
            confirmParams: {
                return_url: "https://ecommerce-leonard-devinch.abigaelheredia.es/payment-succeed-page/payment-succeed.html",
            },
        });

        if (error) {
            // This point will only be reached if there is an immediate error when
            // confirming the payment. Show error to your customer (for example, payment
            // details incomplete)
            const messageContainer = document.querySelector("#error-message");
            messageContainer.textContent = error.message;
        } else {
            // Your customer will be redirected to your `return_url`. For some payment
            // methods like iDEAL, your customer will be redirected to an intermediate
            // site first to authorize the payment, then redirected to the `return_url`.
        }
    }
}

export class Order {}

export class OrderView {}

export class OrderController {}

export function getCookie(cname) {
    // Function from https://www.w3schools.com/js/js_cookies.asp
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(";");
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

export function setCookie(cname, cvalue, exdays) {
    // Function from https://www.w3schools.com/js/js_cookies.asp
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}