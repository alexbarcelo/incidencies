/*
 * Operativa -- Javascript, jQuery & AJAX
 */

 llistat_tipus = new Array();
 llistat_idtipus = new Array();
 hores = new Array();
 profes = new Array();
 nom_alumne = "";

$(function(){
    // Seleccionem link actiu del menú principal
    $("#operativa").addClass("active");

    // Amaguem certes coses per defecte
    $("#filtres").css("display","none");

    // Utilitzem la funcio helper d'inicialitzacio per l'estat de certs blocs
    preparaPagina();

    // Sistema de slidetoggle per als filtres d'alumnes
    $("#filtresflip").click(function(e){
        $("#filtres").slideToggle("slow");
        $("#icon_filtres").toggleClass("icon-chevron-down icon-chevron-up");
        e.preventDefault();
    });

    $.get(URLprefix + "llistatTipus", setupAmonestacioTipus);
    $.get(URLprefix + "llistatProfes", setupProfes);
    $.get(URLprefix + "llistatHores", setupHores);

    // Accio quan s'escull un alumne
    $("#escull").click(alumneSeleccionat);
    $("#llista_alumnes").dblclick(alumneSeleccionat);

    // el llistat de classes, el carreguem al filtre d'alumnes i
    // també a la sel·lecció del modal de consulta de classe
    $.get(URLprefix + "llistatClasses", function(data) {
        $("#filtres_classe").html(data);
        $("#mFiltresClasse").html(data);
    });

    // Filtratge d'alumnes
    $("#filtres_refresca").click(filtraAlumnes);
    $("#filtres_nom").change(filtraAlumnes);
    $("#filtres_classe").change(filtraAlumnes);
});

/*
 * Inicialitzem les tipiques variables i displays
 * per quan hi ha algun click o canvi de pantalla
 */
function preparaPagina () {
    $("#col_alumnes").css("display","inherit");
    $("#respostaPrincipal").css("display","none");
}

/*
 * Quan es selecciona un alumne (doble click o btn adequat)
 *
 * Es realitza la consulta AJAX addient i s'imprimeix el resultat
 * de la consulta (dades d'amonestacions de l'alumne)
 */
function alumneSeleccionat() {
    // agafem el valor corresponent a l'alumne selected
    al = $("#llista_alumnes option:selected");
    nom_alumne = al.text();
    $("#respostaPrincipal").css("display","inherit")
          .html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>');
    $.get(URLprefix + "consulta/alumne/" + al.val() , processaConsultaAlumnes);
}

/*
 * Funcions de setup de dades internes
 *
 * Disparades per AJAX, plenen informació de variables internes
 * d'ús habitual
 */

 // setup dels Tipus
function setupAmonestacioTipus(data) {
    $.each(data, function(i, t) {
        llistat_tipus[t.simbolo] = t;
        llistat_idtipus[t.id] = t;
    });
}

// setup dels profes
function setupProfes(data) {
    $.each(data, function(i,p) {
        profes[p.id] = p.nombre;
    });
}

// setup de les hores
function setupHores(data) {
    $.each(data, function(i,h) {
        hores[h.id] = h.inicio;
    });
}

/*
 * Funcions per a obtenir cadenes de text adequades per a la creació
 * de taules de consulta (ús d'accordion, javascript de bootstrap)
 */
function capcaleraEntrada(index, parent) {
    return '<div class="accordion-group">' +
        '<div class="accordion-heading row-fluid">' +
        '<a class="accordion-toggle" data-toggle="collapse" data-parent="'
            + parent + '" href="#collapse' + index + '">';
}
function mitjaEntrada(index) {
    return '</a></div> <!-- accordion-heading -->' +
        '<div id="collapse' + index + '" class="accordion-body collapse">' +
        '<div class="accordion-inner">';
}
tancamentEntrada = '</div><!-- accordion-inner -->' +
    '</div><!-- accordion-body -->' +
    '</div><!-- accordion-group -->';
// ***************************************************

/*
 * Aquesta funció es crida com a callback davant de crides AJAX de
 * consulta.
 *
 * data és la variable JSON que conté el contingut de la resposta
 * L'objectiu és mostrar una taula correctament formatada
 */
function processaConsultaAlumnes(data) {
    // capcalera del document
    var capDoc = '<h2>Consulta d\'alumne:</h2>' +
        '<h1><small>' + nom_alumne + '</small></h3>';

    // preparem 3 taules, amonestacions (grossa) i dos mitges per faltes i retards
    var taulaInc = '<h3>Amonestacions</h3>' +
        '<div class="accordion" id="accAmonestacions">';

    var taulaR = '<div class="span6" id="accR">' +
        '<h3>Retards</h3>';
    var taulaF = '<div class="span6" id="accF">' +
        '<h3>Faltes</h3>';

    /*
     * Procedim a processar "data" i a fer un html addient per a
     * tota la informació rebuda.
     */
    $.each(data, function (index, value) {
        if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "AM") {
            taulaInc += capcaleraEntrada(index, "accAmonestacions");
            taulaInc += '<div class="col colId span2">' +
                '#' + value['id'] + '</div>';
            taulaInc += '<div class="col colProfe span5">' +
                profes[value['idProfesores']] + '</div>';
            taulaInc += '<div class="col colMomentLectiu offset1 span2">' +
                hores[value['idHorasCentro']] + '</div>';
            taulaInc += '<div class="col coldataLectiva span2">' +
                value['dia'] + '</div>';
            taulaInc += mitjaEntrada(index);
            taulaInc += value['comentarios'];
            taulaInc += tancamentEntrada;
        } else if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "FA") {
            taulaF += capcaleraEntrada(index, "accAmonestacions");
            taulaF += '<div class="col colId span2">' +
                '#' + value['id'] + '</div>';
            taulaF += '<div class="col colProfe span4">' +
                profes[value['idProfesores']] + '</div>';
            taulaF += '<div class="col colMomentLectiu span2">' +
                hores[value['idHorasCentro']] + '</div>';
            taulaF += '<div class="col coldataLectiva span2">' +
                value['dia'] + '</div>';
            taulaF += mitjaEntrada(index);
            taulaF += value['comentarios'];
            taulaF += tancamentEntrada;

        } else if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "RE") {
            taulaR += capcaleraEntrada(index, "accAmonestacions");
            taulaR += '<div class="col colId span2">' +
                '#' + value['id'] + '</div>';
            taulaR += '<div class="col colProfe span4">' +
                profes[value['idProfesores']] + '</div>';
            taulaR += '<div class="col colMomentLectiu span2">' +
                hores[value['idHorasCentro']] + '</div>';
            taulaR += '<div class="col coldataLectiva span2">' +
                value['dia'] + '</div>';
            taulaR += mitjaEntrada(index);
            taulaR += value['comentarios'];
            taulaR += tancamentEntrada;
        }

    });

    // tanquem
    taulaInc += '</div> <!-- accordion Amonestacions -->';
    taulaR += '</div> <!-- accordion Retards -->';
    taulaF += '</div> <!-- accordion Faltes -->';

    // Volcat de tota l'estructura html al div central d'informació
    $("#respostaPrincipal").html(capDoc + taulaInc +
        '<div class="row-fluid">' + taulaR + taulaF +
        '</div> <!-- row-fluid de faltes i retards -->' );
}

/*
 * Acció de filtratge d'alumnes
 * Torna una comanda SQL correcta. Sanitized, no explotable per
 * usuaris malintencionats
 */
function filtraAlumnes() {
    var nom = $("#filtres_nom").val();
    var query = new Array();

    if (nom) {
        query.push("nombre LIKE '%" + nom + "%'");
    }

    $("#llista_alumnes").load(URLprefix + "filtraAlumnes",{'query':nom});
}
