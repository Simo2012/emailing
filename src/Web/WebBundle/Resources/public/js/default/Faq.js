/**
 * Classe de gestion globale
 *
 * <pre>
 * Victor 08/02/15 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package default
 */
function Faq()
{
    // ==== Constructeur ====
} // Global
Faq.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function()
    {
        $(document).on('click', '.div_RBZ_faqQuestion', function() {
            $(this).children('div').toggle();
            $('.RBZ_faq_down').html('&nbsp; &#708;');
            return false;
        });
        $(document).on('click', '#div_RBZ_faqMenu ul li', function() {
            // ==== Réinitialisation ====
            $('#div_RBZ_faqMenu ul li').css('border-left','none');
            // ---- Effet active ----
            $(this).css('border-left','3px solid #f04f61');
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