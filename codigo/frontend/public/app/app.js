export class User {
    constructor() {}
}

export class UserController {
    constructor() {}

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

            // Borrar los datos del localStorage
            localStorage.removeItem("userData");
            alert("Sesión cerrada correctamente");

            // LoadNotLoggedUIHome
            Guard.redirectHome();
        });
        request.fail((data, textStatus) => {
            alert("No se ha podido cerrar sesión correctamente");
        });
    }

    static saveUserData(userData) {
        localStorage.setItem("userData", JSON.stringify(userData));
    }

    static getUserData() {
        let userData = localStorage.getItem("userData");
        userData = JSON.parse(userData);
        return userData;
    }

    static deleteUserData() {
        localStorage.removeItem("userData");
    }

    static loadNotLoggedUIHome() {
        // Patterns
        UserController.emailPattern = /[\w-]*@[\w-]*\.[\w]{2,6}/;
        UserController.passwordPattern = /.{5,16}/;

        // Mostrar boton inicio de sesión
        $("#notLogged-user-widget").removeClass("d-none");

        // Resetear botón user email dropdown
        $("#userDropdownMenuButton").text("example@hotmail.com");

        // notLogged-user-widget
        // logged-user-widget
        // Ocultar boton user email dropdown
        $("#logged-user-widget").addClass("d-none");

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

    static preloadLoggedUIHome(userData) {
        // Preload and load functions make the same thing
        // but in different time
        UserController.loadLoggedUIHome(userData);
    }

    static loadLoggedUIHome(userData) {
        // Ocultar boton inicio de sesión
        $("#notLogged-user-widget").addClass("d-none");
        UserController.loadLoggedUINavbar(userData);
    }

    static loadLoggedUINavbar(userData) {

        // Rellenar botón user email dropdown
        $("#userDropdownMenuButton").text(userData.email);

        // Mostrar boton user email dropdown
        $("#logged-user-widget").removeClass("d-none");

        // Establecer el número de articles en el carrito
        ShoppingCartController.updateShoppingCartBadgeNumber();

        // EventListener Send logout request
        $("#logoutButton").click(() => {
            UserController.sendLogout();
        });
    }

    static preloadLoggedUIProfile(userData) {
        UserController.loadLoggedUIProfile(userData);
    }

    static loadLoggedUIProfile(userData) {

        UserController.loadLoggedUINavbar(userData);

        // Mostrar datos del usuario
        $("#inputUserEmail").val(userData.email);
        $("#inputFullName").val(userData.fullName);
        $("#inputTelephoneNumber").val(userData.telephoneNumber);
        $("#inputShippingAddress").val(userData.shippingAddress);

        // EventListener actualizar datos de usuario

        $("#updateProfileButton").click((e) => {
            let profileData = {
                full_name: $("#inputFullName").val(),
                telephone_number: $("#inputTelephoneNumber").val(),
                shipping_address: $("#inputShippingAddress").val(),
            };

            if (UserController.validateProfileForm(profileData)) {
                UserController.sendUpdateProfileForm(profileData);
            } else {
                // TODO: Alert user that input data is not valid
            }
        });
    }

    static preloadLoggedUIPaymentSucceed(userData) {
        UserController.loadLoggedUIPaymentSucceed(userData);
    }

    static loadLoggedUIPaymentSucceed(userData) {
        // Rellenar botón user email dropdown
        $("#userDropdownMenu").text(userData.email);

        // EventListener Send logout request
        $("#logoutButton").click(() => {
            UserController.sendLogout();
        });

        // Reset shoppingCart
        localStorage.removeItem("shoppingCart");
    }

    static preloadLoggedUIOrders(userData) {

        UserController.loadLoggedUIOrders(userData);
    }

    static loadLoggedUIOrders(userData) {
        UserController.loadLoggedUINavbar(userData);
    }

    static preloadLoggedUIShoppingCart(userData) {
        UserController.loadLoggedUIShoppingCart(userData);
    }

    static loadLoggedUIShoppingCart(userData) {
        UserController.loadLoggedUINavbar(userData);
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

        request.beforeSend = () => {
            console.log("Hello ...");
            // Reset possible existing updates messages
            $("#updateSuccedMessage").val("");
            $("#updateErrorMessage").val("");
        };

        request.done((data, textStatus) => {
            // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a Actualizado en verde
            //

            let messageSucceed = data.message;
            $("#updateSuccedMessage").text(textStatus + " : " + messageSucceed);

            // Save userData
            let userData = profileData;
            UserController.saveUserData(profileData);
        });

        request.fail((data, textStatus) => {
            // TODO: Crear animación de carga (Spinner en botón de enviar) -> cambia a No actualizado en rojo
            let messageError = data.responseJSON.message;
            $("#updateErrorMessage").text(textStatus + " : " + messageError);
        });
    }

    static validateProfileForm(profileData) {
        return true;
    }

    static showAlertMessage(message, alertType) {
        $("#alertsContainer").addClass(alertType);
        $("#alertsContainer").text(message);

        $("#alertsContainer").show(250, () => {
            setTimeout(
                () => {
                    $("#alertsContainer").hide(250);
                },
                1000
            );
        });


    }
}

export class Guard {

    static async init(page, autorizationRequired) {
        // This algorithm have a explanation diagram in
        // \documentación\documentación del producto\documentación del sistema\documentación diseño\Diagrama de flujo - Carga de Páginas.drawio

        // LocalStorage have userData?
        let userData = UserController.getUserData();
        if (userData) {
            // localStorage HAVE userData

            // Preload logged UI
            switch (page) {
                case "home":
                    UserController.preloadLoggedUIHome(userData);

                    break;
                case "shopping-cart":
                    UserController.preloadLoggedUIShoppingCart(userData);

                    break;
                case "profile":
                    UserController.preloadLoggedUIProfile(userData);

                    break;
                case "orders":
                    UserController.preloadLoggedUIOrders(userData);

                    break;
                case "payment-succeed":
                    UserController.preloadLoggedUIProfile(userData);

                    break;
                default:
                    break;
            }

            // Verify JWT
            let verifyResult = await Guard.verifyJWT();


            // Need autorization?
            if (autorizationRequired) {
                // Autorization requeired


                if (verifyResult.status === "correct") {
                    Guard.userStatus = "logged";

                    // JWT valid

                    // Set localStorage userData
                    UserController.saveUserData(verifyResult.userData);

                    // Load loggedUI
                    switch (page) {
                        case "home":
                            UserController.loadLoggedUIHome(verifyResult.userData);

                            break;
                        case "shopping-cart":
                            UserController.loadLoggedUIShoppingCart(verifyResult.userData);

                            break;
                        case "profile":
                            UserController.loadLoggedUIProfile(verifyResult.userData);

                            break;
                        case "orders":
                            UserController.loadLoggedUIOrders(verifyResult.userData);

                            break;
                        case "payment-succeed":
                            UserController.loadLoggedUIPaymentSucceed(verifyResult.userData);

                            break;
                        default:
                            break;
                    }

                } else {
                    // JWT not valid (JWT will be removed by backend)

                    // Delete localStorage userData
                    UserController.deleteUserData();

                    // Redirect Home
                    Guard.redirectHome();

                }

            } else {
                // Autorization not requeired

                if (verifyResult.status === "correct") {
                    Guard.userStatus = "logged";

                    // JWT valid

                    // Save localStorage userData
                    UserController.saveUserData(verifyResult.userData);

                    // Load loggedUI
                    switch (page) {
                        case "home":
                            UserController.loadLoggedUIHome(verifyResult.userData);

                            break;
                        case "shopping-cart":
                            UserController.loadLoggedUIShoppingCart(verifyResult.userData);

                            break;
                        case "profile":
                            UserController.loadLoggedUIProfile(verifyResult.userData);

                            break;
                        case "orders":
                            UserController.loadLoggedUIOrders(verifyResult.userData);

                            break;
                        case "payment-succeed":
                            UserController.loadLoggedUIPaymentSucceed(verifyResult.userData);

                            break;
                        default:
                            break;
                    }

                } else {
                    // JWT not valid (JWT will be removed by backend)

                    // Delete localStorage userData
                    UserController.deleteUserData();

                    // Load not loggedUI
                    switch (page) {
                        case "home":
                            UserController.loadNotLoggedUIHome();

                            break;
                        default:
                            break;
                    }

                }

            }

        } else {
            Guard.userStatus = "notLogged";
            // localStorage DON'T HAVE userData

            // Need autorization?
            if (autorizationRequired) {
                // Autorization required

                // Verify JWT
                let verifyResult = await Guard.verifyJWT();
                if (verifyResult.status === "correct") {
                    // JWT valid

                    // Set localStorage userData
                    UserController.saveUserData(verifyResult.userData);

                    // Load logged UI
                    switch (page) {
                        case "home":
                            UserController.loadLoggedUIHome(verifyResult.userData);

                            break;
                        case "shopping-cart":
                            UserController.loadLoggedUIShoppingCart(verifyResult.userData);

                            break;
                        case "profile":
                            UserController.loadLoggedUIProfile(verifyResult.userData);

                            break;
                        case "orders":
                            UserController.loadLoggedUIOrders(verifyResult.userData);

                            break;
                        case "payment-succeed":
                            UserController.loadLoggedUIProfile(verifyResult.userData);

                            break;
                        default:
                            break;
                    }

                } else {
                    // JWT not valid (JWT will be removed by backend)

                    // Redirect Home
                    Guard.redirectHome();

                }

            } else {
                // Autorization not requeired

                // Load not logged UI
                switch (page) {
                    case "home":
                        UserController.loadNotLoggedUIHome();

                        break;
                    default:
                        break;
                }

            }

        }
    }

    static async verifyJWT() {
        try {
            var result = await $.ajax({
                url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/clients-api/v1/verify_jwt/",
                method: "POST",
                // Type of data send to the server
                contentType: "application/json; charset=UTF-8",
                // Expected type of data received from server response
                dataType: "json",

                // Uncomment this for securized requests
                xhrFields: {
                    withCredentials: true,
                },
            });

            return result;
        } catch (error) {

            let verifyResult = {
                status: "incorrect"
            }

            return verifyResult;
        }

    }

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
        this.card = $('#artworkCard-Prototype').clone();
        $(this.card).removeAttr("id");
        $(this.card).removeClass("d-none");
        this.addCartButton = $(this.card).find(".artworkCartButton");
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

        // Si todavía no esta en el carrito se añadirá
        if (Guard.userStatus === "logged") {

            console.log("Fin añadido articulo al carrito");
            let shoppingCartStorage = localStorage.getItem("shoppingCart");
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
                    selectedArtwork.artistEmail == this.artwork.artist.artist_email
                );
            });


            if (artworkIndex === -1) {

                shoppingCartStorage.push({
                    artistEmail: this.artwork.artist.artist_email,
                    title: this.artwork.title,
                    units: 1,
                });

                localStorage.setItem("shoppingCart", JSON.stringify(shoppingCartStorage));


                // Update number of items in shoppingCartBadge
                ShoppingCartController.updateShoppingCartBadgeNumber();
            }



            UserController.showAlertMessage('Artículo añadido al carrito', 'alert-success');

        } else if (Guard.userStatus === "notLogged") {
            $("#loginButton").click();
        }
    }
}

export class Catalogue {
    constructor() {
        throw new Error("Can't instantiate abstract class!");
    }

    static init() {
        Catalogue.artworksContainer = $("section.artworks-section .artworks");
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
            Catalogue.artworksContainer.append(artwork.view.card);
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

        // Obtener vw
        let vw = $(window).width();

        // Obtener separación top and left
        let filterButtonPosition = $("#expandFilterButton").offset();

        // Calcular separación right
        let filterButtonWidth = $("#expandFilterButton").width();
        filterButtonPosition.right = vw - (filterButtonPosition.left + filterButtonWidth);

        // Calcular separación top del filterSideBar (separación top botón + alto boton)
        let filterButtonHeight = $("#expandFilterButton").height();
        let offSetTop = filterButtonPosition.top + filterButtonHeight;

        // Posicionar (separación top + alto boton + ~padding boton) (separación right)

        $("#filterSidebar").css({
            position: 'absolute',
            top: (offSetTop + 20) + "px",
            right: 0,
        })

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
        if (shoppingCartStorage) {

            shoppingCartStorage = JSON.parse(shoppingCartStorage);

            // shoppingCartStorage is an array with data like this =
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

    static updateShoppingCartBadgeNumber() {
        let shoppingCartStorage = localStorage.getItem("shoppingCart");
        if (shoppingCartStorage) {

            shoppingCartStorage = JSON.parse(shoppingCartStorage);

            // shoppingCartStorage is an array with data like this =
            //
            // [
            //     { artist_email: "hola@asds.com", title: "Hola mundo", units: 21 },
            //     { artist_email: "hola@asds.com", title: "Hola mundo", units: 21 },
            // ];

            // Establecer la cantidad de articles en el badge
            let articlesQuantity = shoppingCartStorage.length;
            $('#shopping-cart-number').text(articlesQuantity);


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

        // shoppingCartStorage is an array with data like this =
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

        // Update number of items in shoppingCartBadge
        ShoppingCartController.updateShoppingCartBadgeNumber();

        // Trying to manage memory
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

            // Modify units in localStorage and shoppingCartItem
            let shoppingCartItemsLS = localStorage.getItem("shoppingCart");

            shoppingCartItemsLS = JSON.parse(shoppingCartItemsLS);

            let searchElement = {
                artistEmail: this.shoppingCartItem.artist.artist_email,
                title: this.shoppingCartItem.title,
            }

            let itemIndex = shoppingCartItemsLS.findIndex((element) => {
                return (element.title === searchElement.title && element.artistEmail === searchElement.artistEmail);
            });

            this.shoppingCartItem.units = updatedUnits;

            shoppingCartItemsLS[itemIndex] = {
                artistEmail: this.shoppingCartItem.artist.artist_email,
                title: this.shoppingCartItem.title,
                units: this.shoppingCartItem.units
            }

            localStorage.setItem("shoppingCart", JSON.stringify(shoppingCartItemsLS));

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

export class Order {
    constructor(purchased_artworks, order_date, order_id, status, total_charge) {
        this.observers = [];
        this.order_date = order_date;
        this.order_id = order_id;
        this.status = status;
        this.total_charge = total_charge;
        this.purchased_artworks = purchased_artworks;
    }

    registerObserver(observer) {
        this.observers.push(observer);
    }

    unRegisterObserver(observer) {
        let observerIndex = this.observers.indexOf(observer);

        this.observers.splice(observerIndex, 1);
    }

    copy() {
        return {
            order_date: this.order_date,
            order_id: this.order_id,
            status: this.status,
            total_charge: this.total_charge,
        };
    }
}

export class OrderView {
    constructor() {
        this.card = OrderView.cardPrototype.cloneNode(true);
    }
    static init() {
        OrderView.cardPrototype = document.getElementById("orderViewCardPrototype");
    }

    populate(orderData) {
        $(this.card).find(".orderID").text(`ID - ${orderData.order_id}`);
        $(this.card).find(".orderDate").text(`${orderData.order_date}`);
        // TODO: Change status badge color depending of status
        $(this.card).find(".status").text(`${orderData.status}`);

        $(this.card).find(".orderTotalCharge").text(`${orderData.total_charge} €`);

        $(this.card).removeClass("d-none");
        $("#ordersList").append(this.card);
    }
}

export class OrderController {
    constructor(order, orderView) {
        this.order = order;
        this.orderView = orderView;

        this.order.registerObserver(orderView);
        this.orderView.populate(this.order.copy());

        // Add EventsListeners
        $(this.orderView.card).click(() => {

            this.showOrderDetails();
            OrderView.showingDetails = this.orderView;
        });

        $("#downloadOrderButton").click(() => {
            if (OrderView.showingDetails === this.orderView) {
                this.sendDownloadOrderRequest();
            }
        });
    }

    showOrderDetails() {
        $("#orderCard").find(".orderDate").text(`${this.order.order_date}`);
        $("#orderCard").find(".orderId").text(`${this.order.order_id}`);
        $("#orderCard").find(".totalCharge").text(`${this.order.total_charge} €`);
        // TODO: Change status badge color depending of status
        $("#orderCard").find(".status").text(`${this.order.status}`);

        // Sale ordersCard hacia la izquierda
        $("#ordersCard").toggle("slide");
        // Entra orderCard por la derecha
        $("#orderCard").toggle("slide", { direction: "right" });

        // TODO: Fullfill products resume

        for (let i = 0; i < this.order.purchased_artworks.length; i++) {
            const purchased_artwork_data = this.order.purchased_artworks[i];
            let purchasedArtworkNode = $("#purchasedArtwork-CardPrototype").clone();

            $(purchasedArtworkNode)
                .find(".artworkImage")
                .attr("src", purchased_artwork_data.url);

            $(purchasedArtworkNode)
                .find(".artworkTitle")
                .text(purchased_artwork_data.artwork_title);

            $(purchasedArtworkNode)
                .find(".artworkArtist")
                .text(purchased_artwork_data.artist_full_name);

            $(purchasedArtworkNode)
                .find(".artworkPrice")
                .text(`${purchased_artwork_data.price_by_unit} €`);

            $(purchasedArtworkNode)
                .find(".artworkUnits")
                .text(purchased_artwork_data.units);

            let total =
                purchased_artwork_data.price_by_unit * purchased_artwork_data.units;
            $(purchasedArtworkNode).find(".artworkTotal").text(`${total} €`);

            $(purchasedArtworkNode).removeClass("d-none");
            $(purchasedArtworkNode).appendTo("#purchasedArtworksContainer");
        }
    }

    static hideOrderDetails() {
        // TODO: Sale ordersCard hacia la izquierda
        $("#ordersCard").toggle("slide");
        // TODO: Sale orderCard por la derecha
        $("#orderCard").toggle("slide", { direction: "right" });
    }

    static async init() {
        let orders = await OrderController.sendGetOrdersRequest();

        let ordersData = orders.ordersData;
        for (let i = 0; i < ordersData.length; i++) {
            const orderData = ordersData[i];

            let dateFragments = orderData.order_date.split("-");
            let yyyy = dateFragments[0];
            let mm = dateFragments[1];
            let dd = dateFragments[2];
            let formatedDate = dd + "/" + mm + "/" + yyyy;

            let order = new Order(
                orderData.purchased_artworks,
                formatedDate,
                orderData.order_id,
                orderData.status,
                orderData.total_charge
            );
            let orderView = new OrderView();
            let orderController = new OrderController(order, orderView);
        }

        // Add EventListener to #orderCard for orders details

        $("#backToOrdersButton").click(() => {
            OrderController.hideOrderDetails();
        });
    }

    static async sendGetOrdersRequest() {
        try {
            let result = await $.ajax({
                url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/clients-api/v1/consult_orders/",
                method: "POST",
                // Expected type of data received from server response
                dataType: "json",

                // Uncomment this for securized requests
                xhrFields: {
                    withCredentials: true,
                },
            });

            return result;
        } catch (error) {
            console.log(error);
        }
    }

    sendDownloadOrderRequest() {
        let orderData = {
            order_id: this.order.order_id,
        };

        // jQuery ajax
        var request = $.ajax({
            method: "POST",
            url: "https://backend.ecommerce-leonard-devinch.abigaelheredia.es/apis/clients-api/v1/download_order/",
            data: JSON.stringify(orderData),
            xhrFields: {
                withCredentials: true,
                responseType: "blob",
            },
        });

        request.done((data, textStatus, jqXHR) => {
            // First of all be sure the backend allow you read response headers "Content-Disposition" and "Content-Type",
            // this can be done setting the header
            // PHP eg:
            // header("Access-Control-Expose-Headers: Content-Type,Content-Disposition");

            // check for a filename
            var filename = "";
            var disposition = jqXHR.getResponseHeader("Content-Disposition");
            if (disposition && disposition.indexOf("attachment") !== -1) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1])
                    filename = matches[1].replace(/['"]/g, "");
            }

            var type = jqXHR.getResponseHeader("Content-Type");
            var blob = new Blob([data], { type: type });

            if (typeof window.navigator.msSaveBlob !== "undefined") {
                // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                window.navigator.msSaveBlob(blob, filename);
            } else {
                var URL = window.URL || window.webkitURL;
                var downloadUrl = URL.createObjectURL(blob);

                if (filename) {
                    // use HTML5 a[download] attribute to specify filename
                    var a = document.createElement("a");
                    // safari doesn't support this yet
                    if (typeof a.download === "undefined") {
                        window.location = downloadUrl;
                    } else {
                        a.href = downloadUrl;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                    }
                } else {
                    window.location = downloadUrl;
                }

                setTimeout(function() {
                    URL.revokeObjectURL(downloadUrl);
                }, 100); // cleanup
            }
        });

        request.fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(textStatus);
            console.log("Error :", errorThrown);
        });
    }
}