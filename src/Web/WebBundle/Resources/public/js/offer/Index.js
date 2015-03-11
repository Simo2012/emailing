/**
 * Classe de gestion des la page Index (graphiques ...)
 *
 * <pre>
 * Elias 11/02/15 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Popup
 */
function Index()
{
    // ==== Constructeur ====
       //Index.prototype._availableAmount = 50;
} // Popup
Index.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function(piAvailableAmount, paEarning)
    {
        //json decode
        var loEarnings = $.parseJSON(paEarning);
        // month bar line initialisation
        var jan = loEarnings[1] === undefined ? 0 : loEarnings[1];
        var feb = loEarnings[2] === undefined ? 0 : loEarnings[2];
        var mar = loEarnings[3] === undefined ? 0 : loEarnings[3];
        var apr = loEarnings[4] === undefined ? 0 : loEarnings[4];
        var may = loEarnings[5] === undefined ? 0 : loEarnings[5];
        var jun = loEarnings[6] === undefined ? 0 : loEarnings[6];
        var jui = loEarnings[7] === undefined ? 0 : loEarnings[7];
        var aug = loEarnings[8] === undefined ? 0 : loEarnings[8];
        var sep = loEarnings[9] === undefined ? 0 : loEarnings[9];
        var oct = loEarnings[10] === undefined ? 0 : loEarnings[10];
        var nov = loEarnings[11] === undefined ? 0 : loEarnings[11];
        var dec = loEarnings[12] === undefined ? 0 : loEarnings[12];

        // montant restant donut
        var liRemainingAmount = 0;
       if(piAvailableAmount <= 150) {
            liRemainingAmount = 150 - piAvailableAmount;
        }

        // ==== Graph stats ====

        var lineChartData = {
            labels: ["JAN", "FEV", "MAR", "AVR", "MAI", "JUN", "JUL", "AOU", "SEP", "OCT", "NOV", "DEC"],
            datasets: [
                {
                    label: "Gains",
                    fillColor: "000",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "#F14E61",
                    pointStrokeColor: "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(220,220,220,1)",
                    data: [jan, feb, mar, apr, may, jun, jui, aug, sep, oct, nov, dec]
                }
            ]

        }
        // donut
        var doughnutData = [
            {
                value: piAvailableAmount,
                color: "#F7464A",
                highlight: "#C94251",
                label: "Gain"
            },
            {
                value: liRemainingAmount,
                color: "#3F3F3F",
                highlight: "#C94251",
                label: "Remaining"
            }

        ];
        // graph line
        var ctx = document.getElementById("canavs_RBZ_line").getContext("2d");
        window.myLine = new Chart(ctx).Line(lineChartData, {
            responsive: true,
            scaleShowVerticalLines : false,
            bezierCurve : false,
            pointDotRadius : 5,
            datasetFill : false

        });
        // graph donut
        var ctxDonut = document.getElementById("canavs_RBZ_donut").getContext("2d");
        window.myDoughnut = new Chart(ctxDonut).Doughnut(doughnutData, {
            responsive: false,
            percentageInnerCutout : 80,
            segmentShowStroke: false,
        });

    }, // ready

    /**
     * Token de fin
     */
    _endPrototype: null
}; // Popup.prototype

//==== Définition de l'objet Index goIndex ====
var goIndex = new Index();
