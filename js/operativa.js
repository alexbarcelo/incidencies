/*
 * Operativa -- Javascript, jQuery & AJAX
 */

 llistat_idtipus = new Array();
 hores = new Array();

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
    $("#filtres_cognom").change(filtraAlumnes);
    $("#filtres_classe").change(filtraAlumnes);
});

/*
 * Inicialitzem les tipiques variables i displays
 * per quan hi ha algun click o canvi de pantalla
 */
function preparaPagina () {
    $("#consultaAlumnes").css("display","none");
    $("#col_alumnes").css("display","inherit");
    $("#respostaPrincipal").css("display","none");
}

/*
 * Quan es selecciona un alumne (doble click o btn adequat)
 *
 * S'actualitza la informació de la form, visible i no visible
 */
function alumneSeleccionat() {
    // per defecte, carreguem el valor al camp corresponent
    al = $("#llista_alumnes option:selected");
    $("#ap_idalumne").val(al.val());
    $("#ap_alumne").val(al.text());

    // en cas que estiguem en una consulta d'alumne, ens disparem
    if (consultingStatus) {
        // si estem en consulta "per alumnes", llavors treiem l'alert
        $("#consultaAlumnes").css("display","none");
        $("#respostaPrincipal").css("display","inherit")
          .html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>');
        // i procedim a fer la query i aquestes coses
        $.get(URLprefix + "consulta/alumne/" + al.val() , processaConsultaAlumnes );
    }
}

function setupAmonestacioTipus(data) {
    $.each(data, function(i, t) {
        llistat_tipus[t.descr] = t;
        llistat_idtipus[t.id] = t;
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
        '<h1><small>' + $("#ap_alumne").val() + '</small></h3>';

    // preparem 3 taules, amonestacions (grossa) i dos mitges per faltes i retards
    var taulaInc = '<h3>Amonestacions</h3>' +
        '<div class="accordion" id="accAmonestacions">';
    var taulaR = '<div class="accordion span6" id="accR">' +
        '<h3>Retards</h3>';
    var taulaF = '<div class="accordion span6" id="accF">' +
        '<h3>Faltes</h3>';

    /*
     * Procedim a processar "data" i a fer un html addient per a
     * tota la informació rebuda.
     */
    $.each(data, function (index, value) {
        taulaInc += capcaleraEntrada(index, "accAmonestacions");
        taulaInc += '<div class="col colId span1">' +
            '#' + value['id'] + '</div>';
        taulaInc += '<div class="col colennomde span5">' +
            value['ennomdeProfe'] + '</div>';
        taulaInc += '<div class="col colhoraLectiva offset2 span2">' +
            hores[value['horaLectiva']] + '</div>';
        taulaInc += '<div class="col coldataLectiva span2">' +
            value['dataLectiva'] + '</div>';
        taulaInc += mitjaEntrada(index);
        taulaInc += "hello world";
        taulaInc += tancamentEntrada;
    });

    // tanquem
    taulaInc += '</div> <!-- accordion Amonestacions -->';
    taulaR += '</div> <!-- accordion Retards -->';
    taulaF += '</div> <!-- accordion Faltes -->';

    // Volcat de tota l'estructura html al div central d'informació
    $("#respostaPrincipal").html(capDoc + taulaInc +
        '<div class="row-fluid">' +
        taulaR + taulaF +
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
        query.push("nom LIKE '%" + nom + "%'");
    }

    val = $("#filtres_classe option:selected").val();
    if (val > 0) {
        query.push("classe="+val);
    }

    $("#llista_alumnes").load(URLprefix + "filtraAlumnes",{'query':query.join(" AND ")});
}
