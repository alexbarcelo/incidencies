/*
 * Operativa -- Javascript, jQuery & AJAX
 */

 URLprefix = "/yii/incidencies/index.php/operativa/"
 llistat_profes = new Array();

$(function(){
    // Seleccionem link actiu del menú principal
    $("#operativa").addClass("active");

    // Amaguem certes coses per defecte
    $("#filtres").css("display","none");
    $("#accioPrincipal").css("display","none");

    // Sistema de slidetoggle per als filtres d'alumnes
    $("#filtresflip").click(function(e){
        $("#filtres").slideToggle("slow");
        $("#icon_filtres").toggleClass("icon-chevron-down icon-chevron-up");
        e.preventDefault();
    });

    // Possibilitat de fer incidencies en nom d'altres responsables
    $("#ap_self").click(function(){
        $("#ap_idprofe").val("-1");
        $("#ap_profe").val("");
        if ($(this).is(":checked")) {
            $("#ap_divprofe").slideUp("slow");
        } else {
            $("#ap_divprofe").slideDown("slow");
        }
    });
    // estat inicial amagat
    $("#ap_divprofe").slideUp("slow");
    // carreguem les dades dels profes inicialment
    $.get(URLprefix + "llistatProfes", setupUserAcUi);
    // i ho preparem
    $("#ap_profe").typeahead({
        source: function(query, process) {
            process(profes);
        },
        updater: function(item) {
            $("#ap_idprofe").val(map[item].id);
            return item;
        }
    });

    // Accio quan s'escull un alumne
    $("#escull").click(alumneSeleccionat);
    $("#llista_alumnes").dblclick(alumneSeleccionat);

    // el llistat de classes
    $("#filtres_classe").load(URLprefix + "llistatClasses")

    // Filtratge d'alumnes
    $("#filtres_refresca").click(filtraAlumnes);
    $("#filtres_nom").change(filtraAlumnes);
    $("#filtres_cognom").change(filtraAlumnes);
    $("#filtres_classe").change(filtraAlumnes);

    /*
     * Generica per a totes les accions:
     * (per si encara no s'havia seleccionat cap acció,
     *  fem visible l'actual, sigui quina sigui)
     */
    $(".accions").click(function(){
        $("#accioPrincipal").css("display","inherit");
        $(".accions").each(function() {
            $(this).parent().removeClass("active");
        });
    });

    $("#retard").click(retard);
    $("#amonestacioOral").click(amonestacioOral);
    $("#expulsio").click(expulsio);
});


/* *********************************************************************
 * Funcions per a actualitzar la informació de
 * cada acció diferent, d'operativa
 * (menú de navegació de la columna)
 * ****************************************************************** */

function retard() {
    $("#ap_legend").text("Retard");
    $("#retard").parent().addClass("active");
}

function amonestacioOral() {
    $("#ap_legend").text("Amonestació oral");
    $("#amonestacioOral").parent().addClass("active");
}

function expulsio() {
    $("#ap_legend").text("Expulsió");
    $("#expulsio").parent().addClass("active");
}

/*
 * Quan es selecciona un alumne (doble click o btn adequat)
 *
 * S'actualitza la informació de la form, visible i no visible
 */
function alumneSeleccionat() {
    al = $("#llista_alumnes option:selected");
    $("#ap_idalumne").val(al.val());
    $("#ap_alumne").val(al.text());
}

/*
 * Inicialitzacio de les variables globals per al typeahead
 * de profes
 */
function setupUserAcUi(data) {
    profes = [];
    map = {};

    $.each(data, function(i, profe){
        map[profe.nom] = profe;
        profes.push(profe.nom);
    });
};

/*
 * Acció de filtratge d'alumnes
 * Torna una comanda SQL correcta. Sanitized, no explotable per
 * usuaris malintencionats
 */
function filtraAlumnes() {
    var nom = $("#filtres_nom").val();
    var cognom = $("#filtres_cognom").val();
    var query = new Array();

    if (nom) {
        query.push("nom LIKE '%" + nom + "%'");
    }

    if (cognom) {
        query.push("cognom LIKE '%" + cognom + "%'");
    }

    val = $("#filtres_classe option:selected").val();
    if (val > 0) {
        query.push("classe="+val);
    }

    $("#llista_alumnes").load(URLprefix + "filtraAlumnes",{'query':query.join(" AND ")});
}
