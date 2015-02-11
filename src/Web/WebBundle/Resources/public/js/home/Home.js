/**
 * Classe de gestion des la page home (graphiques ...)
 *
 * <pre>
 * Elias 11/02/15 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Popup
 */
function Home()
{
    // ==== Constructeur ====
} // Popup
Home.prototype = {
    /**
     * Traitements lancés en fin de chargement de la page
     */
    ready: function()
    {
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
                    data: [40, 20, 60, 30, 50, 65, 70, 80, 90, 30, 50, 10]
                }
            ]

        }

        var doughnutData = [
            {
                value: 150,
                color: "#F7464A",
                highlight: "#C94251",
                label: "Gain"
            },
            {
                value: 25,
                color: "#3F3F3F",
                highlight: "#C94251",
                label: "Restant"
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
            responsive: true,
            percentageInnerCutout : 75,
        });

    }, // ready

    /**
     * Token de fin
     */
    _endPrototype: null
}; // Popup.prototype

//==== Définition de l'objet Popup goAaf ====
var goHome = new Home();
