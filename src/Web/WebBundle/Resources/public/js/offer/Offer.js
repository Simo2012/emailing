/**
 * Classe de gestion Offer
 *
 * <pre>
 * Mohammed 09/02/15 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package Web
 */
function Offer()
{   
    // ==== Constructeur ====
} // Offer

Offer.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready : function()
    {
        // ==== Initialisation du max-height de la macro ====
        Offer.prototype._manageOfferMaxHeight();

        // ==== Gestion du redimensionnement de fenêtre (et de la macro) ====
        Offer.prototype._manageResizeEvent();

        // ==== Gestion de l'affichage de la description ====
        Offer.prototype._manageDescriptionDisplaying();

        // ==== Gestion de l'affichage de menu du bouton de recommandation ====
        Offer.prototype._manageRecommendationMenuButton();

        // ==== Gestion des recommandations Twitter ====
        Offer.prototype._manageTwitterButton();

        // ==== Gestion des recommandations Facebook ====
        Offer.prototype._manageFacebookButton();

        // ==== Gestion des recommandations Email ====
        Offer.prototype._manageEmailButton();
    }, // ready

    /**
     * Règle la hauteur maximum du conteneur
     * principal de la macro des offres
     */
    _manageOfferMaxHeight: function()
    {
        $('.RBZ_offer_content').each(function () {
            $(this).css('max-height', $(this).height());
        });
    }, // _manageOfferMaxHeight

    /**
     * Détecte les début et fin de redimensionnement de la fenêtre
     * afin de redimensionner la macro des offres en conséquence
     * @returns {boolean}
     */
    _manageResizeEvent: function()
    {
        // ==== Initialisation ====
        var t, l = (new Date()).getTime(), resizing = false;

        // ==== Détection et déclenchement de trigger sur les début et fin de redimensionnement ====
        $(window).resize(function(){
            var now = (new Date()).getTime();
            if(now - l > 400 && !resizing ){
                $(this).trigger('resizeStart');
                l = now;
            }
            clearTimeout(t);
            t = setTimeout(function(){
                if (resizing) {
                    $(window).trigger('resizeEnd');
                }
            }, 300);
        });

        // ==== Bind des évènement triggés et traitement ====
        $(window).bind('resizeStart', function(){
            resizing = true;
            $('.RBZ_offer_content').each(function () {
                $(this).css('max-height', 'none');
            });
        });

        $(window).bind('resizeEnd', function(){
            resizing = false;
            $('.RBZ_offer_content').each(function () {
                $(this).css('max-height', $(this).height());
            });
        });

        return resizing;
    }, // _manageResizeEvent

    /**
     * Gère l'affichage de la description d'une offre
     * @private
     */
    _manageDescriptionDisplaying: function()
    {
        if ($(window).width() > 960) { // Desktop
            // ==== Gestion de l'ouverture ====
            $(document).on('mouseenter', '.RBZ_offer_img.RBZ_expandable_body', function(event) {
                // ---- Initialisation ----
                var image = $(this);
                var body = image.parent().find('div.RBZ_offer_body');
                var content = image.parent(); // .RBZ_offer_content
                var description = content.find('p.RBZ_offer_description');
                // ---- Affichage de la description ----
                if (!body.hasClass('RBZ_expanded')) {
                    description.toggleClass('RBZ_hide');
                    body.toggleClass('RBZ_expanded');
                    body.animate({position: 'absolute', height: content.height() - (image.height() / 3), top: -(image.height() * 2 / 3)}, 500, 'easeOutCirc');
                }
            });
            // ==== Gestion de la fermeture ====
            $(document).on('mouseleave', '.RBZ_offer_content', function(event) {
                // ---- Initialisation ----
                var content = $(this); // .RBZ_offer_content
                var image = content.find('img.RBZ_offer_img');
                var body = content.find('div.RBZ_offer_body');
                var description = content.find('p.RBZ_offer_description');
                // ---- Affichage de la description ----
                if (body.hasClass('RBZ_expanded')) {
                    description.toggleClass('RBZ_hide');
                    body.toggleClass('RBZ_expanded');
                    body.animate({position: 'relative', height: '115px', top: 0}, 500, 'easeOutCirc');
                }
            });
        } else { // Mobile
            $(document).on('click', '.RBZ_offer_img.RBZ_expandable_body', function(event) {
                // ---- Initialisation ----
                var image = $(this);
                var body = image.parent().find('div.RBZ_offer_body');
                var content = image.parent(); // .RBZ_offer_content
                var description = content.find('p.RBZ_offer_description');
                // ---- Affichage de la description ----
                if (!body.hasClass('RBZ_expanded')) {
                    description.toggleClass('RBZ_hide');
                    body.toggleClass('RBZ_expanded');
                    body.animate({position: 'absolute', height: content.height() - (image.height() / 3), top: -(image.height() * 2 / 3)}, 500, 'easeOutCirc');
                } else {
                    description.toggleClass('RBZ_hide');
                    body.toggleClass('RBZ_expanded');
                    body.animate({position: 'relative', height: '115px', top: 0}, 500, 'easeOutCirc');
                }
            });
        }
    }, // _manageDescriptionDisplaying

    /**
     * Gestion de l'affichage du menu du bouton de recommandation
     * @private
     */
    _manageRecommendationMenuButton: function()
    {
        if ($(window).width() > 960) { // Desktop
            // ---- Survol du bouton ----
            $(document).on('mouseenter', 'a.RBZ_actions', function (event) {
                var button = $(this).parent();
                var buttonLink = $(this);
                var buttonArrow = button.find('div.RBZ_arrow');
                if (!button.hasClass('RBZ_expanded')) {
                    button.toggleClass('RBZ_expanded');
                    buttonLink.siblings('a').toggleClass('RBZ_hide');
                    buttonArrow.toggleClass('RBZ_open');
                }
                return false;
            });
            // ---- Arrêt du survol du bouton ----
            $(document).on('mouseleave', 'div.RBZ_offer_input', function (event) {
                var button = $(this);
                var buttonLink = $(this).find('a.RBZ_actions');
                var buttonArrow = button.find('div.RBZ_arrow');
                if (button.hasClass('RBZ_expanded')) {
                    button.toggleClass('RBZ_expanded');
                    buttonLink.siblings('a').toggleClass('RBZ_hide');
                    buttonArrow.toggleClass('RBZ_open');
                }
                return false;
            });
        } else { // mobile
            $(document).on('click', 'a.RBZ_actions', function (event) {
                var button = $(this).parent();
                var buttonLink = $(this);
                var buttonArrow = button.find('div.RBZ_arrow');
                button.toggleClass('RBZ_expanded');
                buttonLink.siblings('a').toggleClass('RBZ_hide');
                buttonArrow.toggleClass('RBZ_open');

                return false;
            });
        }
    }, // _manageRecommandationMenuButton

    /**
     * Gère la recommandation par Twitter
     * @private
     */
    _manageTwitterButton: function()
    {
        // ==== Gestion des recommandations Twitter ====
        // ---- Catch du clic et appel de l'action du controller qui se
        // charge de construire puis appeler l'url de partage Twitter ----
        var controllerUrl = '';
        $(document).on('click', 'a.RBZ_twitter', function(event){
            controllerUrl = $(this).attr('href');
            var width  = 550;
            var height = 420;
            var left   = ($(window).width()  - width)  / 2;
            var top    = ($(window).height() - height) / 2;
            window.open(controllerUrl, 'Tweet', 'width='+width+', height='+height+', left='+left+', top='+top);
            return false;
        });
        // ---- Définition de la callback qui observe la réponse de l'API Twitter pour
        // déterminer si l'offre à bien été postée (Clic sur "Tweeter" dans la popup) ----
        var callback = function(e){
            if(e && e.data){
                var data;
                try{
                    data = JSON.parse(e.data);
                }catch(e){
                    // Don't care.
                }
                if(data && data.params && data.params.indexOf('tweet') > -1){
                    window.location = controllerUrl + '?tweeted=tweeted'
                }
            }
        };
        // ---- Mise en place de l'event listener qui se charge d'écouter l'API et d'exécuter la callback ----
        window.addEventListener ? window.addEventListener("message", callback, !1) : window.attachEvent("onmessage", callback);
    }, // _manageTwitterButton

    /**
     * Gère la recommandation par Facebook
     * @private
     */
    _manageFacebookButton: function()
    {
        // ==== Gestion des recommandations Facebook ====
        var facebookUrl = '';
        $(document).on('click', 'a.RBZ_facebook', function() {
            facebookUrl = $(this).attr('href');
            window.open(facebookUrl, 'Facebook', 'width=550, height=420');

            return false;
        });

    }, // _manageFacebookButton

    /**
     * Gère la recommandation par email
     * @private
     */
    _manageEmailButton: function()
    {
        // ==== Gestion des recommandations Email ====
        var controllerUrl = '';
        $(document).on('click', 'a.RBZ_email', function() {
            controllerUrl = $(this).attr('href');
            console.log(controllerUrl);
            goPopup.confirm(Offer.prototype._emailConfirmMessage, function() { window.location = controllerUrl; })
            return false;
        });
    }, // _manageEmailButton

    /**
     * Set le message de confirmation de recommandation par email
     */
    setEmailConfirmMessage: function(message)
    {
        Offer.prototype._emailConfirmMessage = message;
    }, // setEmailConfirmMessage

    /**
     * Message de confirmation de la recommendation par email
     */
    _emailConfirmMessage: '',

    /**
     * Token de fin
     */
    _endPrototype : null
}; // Offer.prototype

//==== Définition de l'objet Offer goOffer ====
var goOffer = new Offer();
goOffer.ready();
