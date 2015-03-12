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
        // ==== Gestion de l'affichage de la description de l'offre ====
        $(document).on('mouseenter mouseleave', '.RBZ_offer_content', function(event) {
            // ---- Affichage/dissimulation de la description ----
            $(this).toggleClass('RBZ_expanded').animate();
            $(this).find('p.RBZ_offer_description').toggleClass('RBZ_hide').animate();
            // ---- Dissimulation du menu du bouton de recommandation si il est ouvert lorsqu'on sort ----
            var recommendButton = $(this).find('div.RBZ_offer_input');
            var recommendButtonLink = $(this).find('div.RBZ_offer_input a.RBZ_actions');
            if(event.type == 'mouseleave' && recommendButton.hasClass('RBZ_expanded')) {
                recommendButton.toggleClass('RBZ_expanded');
                recommendButtonLink.siblings('a').toggleClass('RBZ_hide');
                recommendButtonLink.siblings('div.RBZ_arrow').toggleClass('RBZ_open');
            }
        });

        // ==== Gestion de l'affichage de menu du bouton de recommandation ====
        // ---- Clic sur le bouton ----
        $(document).on('click', 'a.RBZ_actions', function(event) {
            $(this).siblings('a').toggleClass('RBZ_hide');
            $(this).parent().toggleClass('RBZ_expanded');
            $(this).siblings('div.RBZ_arrow').toggleClass('RBZ_open');
            return false;
        });
        // ---- Clic sur la flèche du bouton ----
        $(document).on('click', 'div.RBZ_arrow', function(event) {
            $(this).siblings('a').toggleClass('RBZ_hide');
            $(this).siblings('a.RBZ_actions').toggleClass('RBZ_hide');
            $(this).parent().toggleClass('RBZ_expanded');
            $(this).toggleClass('RBZ_open');
            return false;
        });
        
    }, // ready

    /**
     * Token de fin
     */
    _endPrototype : null
}; // Offer.prototype

//==== Définition de l'objet Offer goOffer ====
var goOffer = new Offer();
goOffer.ready();