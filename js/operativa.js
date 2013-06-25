/*
 * Operativa -- Javascript, jQuery & AJAX
 */

 URLprefix = "/yii/incidencies/index.php/operativa/"
 llistat_profes = new Array();
 llistat_tipus  = new Object();
 llistat_idtipus = new Array();

$(function(){
    // Seleccionem link actiu del menú principal
    $("#operativa").addClass("active");

    // Amaguem certes coses per defecte
    $("#filtres").css("display","none");
    $("#accioPrincipal").css("display","none");
    $("#respostaPrincipal").css("display","none");

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
    $.get(URLprefix + "llistatProfes", setupProfes);
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

    $.get(URLprefix + "llistatTipus", setupAmonestacioTipus);

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

    // Accio principal de submit de la form
    $("#ap_form").submit(novaIncidencia);

    /*
     * Generica per a totes les accions:
     * (per si encara no s'havia seleccionat cap acció,
     *  fem visible l'actual, sigui quina sigui)
     */
    $(".accions").click(function(){
        $("#accioPrincipal").css("display","inherit");
        $("#respostaPrincipal").css("display","none");
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
    $("#ap_legend").html("Retard <small>"+ llistat_tipus["retard"].longDescr +"</small>")
    $("#ap_tipus").val("retard");
    $("#retard").parent().addClass("active");
}

function amonestacioOral() {
    $("#ap_legend").html("Amonestació oral <small>"+ llistat_tipus["amonestacioOral"].longDescr +"</small>")
    $("#amonestacioOral").parent().addClass("active");
    $("#ap_tipus").val("amonestacioOral");
}

function expulsio() {
    $("#ap_legend").html("Expulsió <small>"+ llistat_tipus["expulsio"].longDescr +"</small>")
    $("#expulsio").parent().addClass("active");
    $("#ap_tipus").val("expulsio");
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
function setupProfes(data) {
    profes = [];
    map = {};

    $.each(data, function(i, profe){
        map[profe.nom] = profe;
        profes.push(profe.nom);
    });
};

function setupAmonestacioTipus(data) {
    $.each(data, function(i, t) {
        llistat_tipus[t.descr] = t;
        llistat_idtipus[t.id] = t;
    });
}

/*
 * Funció de gestió de ``submit''
 *
 * Aquesta funció es crida quan hi ha un submit de l'acció principal,
 * és a dir, quan l'usuari sel·leccionar guardar una nova incidència.
 *
 * Es fa una comprovació ràpida JavaScript i s'envia la informació
 * a-là-AJAX esperant rebre la resposta satisfactòria.
 */
function novaIncidencia() {
}

/*
 * Callback per a la resposta AJAX de nova incidència
 */
function novaI_CB() {
    $("#accioPrincipal").css("display","none");
    $("#respostaPrincipal").css("display","inherit");
}


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
