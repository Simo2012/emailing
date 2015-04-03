/**
 * Classe de gestion de la demande de paiement
 *
 * <pre>
 * Elias 03/04/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Web
 */
function CashIn()
{
    // ==== Constructeur ====
} // CashIn
CashIn.prototype = {
    
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function()
    {
        // ==== Gestion des recommandations Email ====
        var controllerUrl = '';
        $(document).on('click', '#a_RBZ_paymentRequest', function() {
            controllerUrl = $(this).attr('hrefbis');
            goPopup.confirm(CashIn.prototype._paymentConfirmMessage, function() { window.location = controllerUrl; })
            return false;
        });
    }, // ready
    
    /**
     * Set le message de confirmation de recommandation par email
     */
    setPaymentConfirmMessage: function(message)
    {
        CashIn.prototype._paymentConfirmMessage = message;
    }, // setPaymentConfirmMessage

    /**
     * Message de confirmation de la recommendation par email
     */
    _paymentConfirmMessage: '',
    
    /**
     * Token de fin
     */
    _endPrototype: null
}; // CashIn.prototype

//==== Définition de l'objet global CashIn ====
var goCashIn = new CashIn();
goCashIn.ready();
