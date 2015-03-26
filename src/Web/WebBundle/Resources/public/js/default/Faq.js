/**
 * Classe de gestion de la page FAQ
 *
 * <pre>
 * Elias 08/03/15 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package default
 */
function Faq()
{
    // ==== Constructeur ====
} // Faq
Faq.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function()
    {
        $(document).on('click', '.RBZ_faqQuestion', function() {
            $(this).children('div').toggle();
            $(this).children('.RBZ_faq_down').toggleClass('selected');
            if ($(this).children('.RBZ_faq_down').hasClass('selected')) {
                $(this).children('.RBZ_faq_down').html('&nbsp; &#708;');
            } else {
                $(this).children('.RBZ_faq_down').html('&nbsp; &#709;');
            }
            return false;
        });
        $(document).on('click', '#div_RBZ_faqMenu ul li', function() {
            // ==== Réinitialisation ====
            $('#div_RBZ_faqMenu ul li').css('border-left', 'none');
            // ---- Effet active ----
            $(this).css('border-left', '3px solid #f04f61');
        });
    }, // ready

    /**
     * Token de fin
     */
    _endPrototype: null
}; // Global.prototype

//==== Définition de l'objet Global goGlobal ====
var goFaq = new Faq();
goFaq.ready();