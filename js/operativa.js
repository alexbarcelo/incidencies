/*
 * Operativa -- Javascript, jQuery & AJAX
 */

 llistat_profes = new Array();
 llistat_tipus  = new Object();
 llistat_idtipus = new Array();
 consultingStatus = false;
 hores = new Array();
 hores[1] = "8:00";
 hores[2] = "9:00";
 hores[3] = "10:00";
 hores[4] = "11:00";
 hores[5] = "11:30";
 hores[6] = "12:30";
 hores[7] = "13:30";
 hores[8] = "14:30";
 hores[9] = "15:00";
 hores[10] = "16:00";
 hores[11] = "Altres";
 // variable ``profeAutor'' està definida amb php al index.php addient
 // variable ``URLprefix'' està definida amb php al index.php addient

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

    // datepicker, una cosa de jQuery UI per a sel·leccionar el dia
    $.datepicker.setDefaults({
        defaultDate: 0,
        setDate: 0,
        closeText: 'Tancar',
        prevText: '&#x3c;Ant',
        nextText: 'Seg&#x3e;',
        currentText: 'Avui',
        monthNames: ['Gener','Febrer','Mar&ccedil;','Abril','Maig','Juny',
          'Juliol','Agost','Setembre','Octubre','Novembre','Desembre'],
        monthNamesShort: ['Gen','Feb','Mar','Abr','Mai','Jun',
          'Jul','Ago','Set','Oct','Nov','Des'],
        dayNames: ['Diumenge','Dilluns','Dimarts','Dimecres','Dijous','Divendres','Dissabte'],
        dayNamesShort: ['Dug','Dln','Dmt','Dmc','Djs','Dvn','Dsb'],
        dayNamesMin: ['Dg','Dl','Dt','Dc','Dj','Dv','Ds'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    });
    $( "#ap_data" ).datepicker();

    // Possibilitat de fer incidencies en nom d'altres responsables
    $("#ap_self").click(function(){
        $("#ap_idprofe").val("-1");
        $("#ap_profe").val("").removeAttr("disabled");
        if ($(this).is(":checked")) {
            $("#ap_divprofe").slideUp("slow");
        } else {
            $("#ap_divprofe").slideDown("slow");
        }
        $("#ap_checkprofe").addClass("icon-remove").removeClass("icon-ok");
        $("#ap_modificaprofe").attr("disabled","");

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
            // fem el tick, per indicar que està bé
            $("#ap_checkprofe").removeClass("icon-remove").addClass("icon-ok");
            $("#ap_modificaprofe").val("").removeAttr("disabled");
            $("#ap_profe").attr("disabled","");
            profeTrigger = true;
            // aixo ara cridarà a el event change, on es comprovara profeTrigger
            return item;
        }
    });
    // mes un event per a canvis (invalidar informació del profe
    $("#ap_modificaprofe").click(function() {
        // resetegem per a que l'usuari pugui canviar el profe
        $("#ap_idprofe").val("-1");
        $("#ap_checkprofe").addClass("icon-remove").removeClass("icon-ok");
        $("#ap_profe").val("").removeAttr("disabled");
        $(this).attr("disabled","");
    });

    $.get(URLprefix + "llistatTipus", setupAmonestacioTipus);

    /*
     * ****************************************************************
     *  ToDo: fer que a la pantalla principal quan es carregui hi hagi
     * una llista de coses pendents
     *
     * pel tutor una columna dels alumnes seus que han tingut una
     * amonestacio ultimament; una altra columna per a alumnes seus
     * que tenen pendents una escrita
     *
     * per a equip directiu la columna de escrites pendents.
     * ****************************************************************
     */

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

    // Accio principal de submit de la form
    $("#ap_form").submit(novaIncidencia);

    /*
     * Generica per a totes les accions:
     *
     * Fem visible el que toca i tapem el que no s'ha de mostrar.
     */
    $(".accions").click(function(){
        preparaPagina();
        $("#accioPrincipal").css("display","inherit");
    });

    // similar però amb les consultes, no es mostra columna d'usuaris
    $(".consultes").click(function(){
        preparaPagina();
    });

    $("#retard").click(retard);
    $("#expulsio").click(expulsio);
    $("#amonestacioOral").click(amonestacioOral);
    $("#amonestacioEscrita").click(amonestacioEscrita);

    $("#meves").click(meves);
    $("#peralumnes").click(perAlumnes);
    $("#perclasses").click(perClasses);
});

/*
 * Inicialitzem les tipiques variables i displays
 * per quan hi ha algun click o canvi de pantalla
 */
function preparaPagina () {
    $("#consultaAlumnes").css("display","none");
    $("#col_alumnes").css("display","inherit");
    $("#accioPrincipal").css("display","none");
    $("#respostaPrincipal").css("display","none");
    $("#col_meves").css("display","none");
    $(".accions").each(function() {
        $(this).parent().removeClass("active");
    });
    $(".consultes").each(function() {
        $(this).parent().removeClass("active");
    });
    consultingStatus = false;
}

/* *********************************************************************
 * Funcions per a actualitzar la informació de
 * cada acció diferent, d'operativa
 * (menú de navegació de la columna)
 * ****************************************************************** */

function retard() {
    $("#ap_legend").html("Retard <small>"+ llistat_tipus["retard"].longDescr +"</small>")
    $("#retard").parent().addClass("active");
    $("#ap_tipus").val(llistat_tipus["retard"].id);
}

function expulsio() {
    $("#ap_legend").html("Expulsió <small>"+ llistat_tipus["expulsio"].longDescr +"</small>")
    $("#expulsio").parent().addClass("active");
    $("#ap_tipus").val(llistat_tipus["expulsio"].id);
}

function amonestacioOral() {
    $("#ap_legend").html("Amonestació oral <small>"+ llistat_tipus["amonestacioOral"].longDescr +"</small>")
    $("#amonestacioOral").parent().addClass("active");
    $("#ap_tipus").val(llistat_tipus["amonestacioOral"].id);
}

function amonestacioEscrita() {
    $("#ap_legend").html("Amonestació escrita <small>"+ llistat_tipus["amonestacioEscrita"].longDescr +"</small>")
    $("#amonestacioEscrita").parent().addClass("active");
    $("#ap_tipus").val(llistat_tipus["amonestacioEscrita"].id);
}

/* *********************************************************************
 * Funcions per a reaccionar davant d'una consulta
 * (menú de navegació de la columna, entrades de consulta)
 *
 * Es fa un heavy ús de modal i AJAX per a realitzar les consultes i
 * processar el JSON que es torna
 * ****************************************************************** */

function meves() {
    $("#meves").parent().addClass("active");
    $.get(URLprefix + "consulta/meves" , processaConsultaMeves );
}

function perAlumnes () {
    $("#peralumnes").parent().addClass("active");
    $("#consultaAlumnes").css("display","inherit");
    consultingStatus = true;
    /* la gestio es realitza a la funcio alumneSeleccionat, que es
      dispara quan es sel·lecciona algun alumne al menú de sel·lecció */
}

function perClasses() {
    $("#perclasses").parent().addClass("active");
    $("#modalClasses").modal('show');
    /* la gestio es realitza via els botons presents al propi modal
      que s'acaba de mostrar, on es pot triar classe i acceptar */
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
 * Helper function per al formatat d'alertes
 *
 * S'utilitza a novaIncidencia quan la comprovacio de la form dona algun
 * error per algun camp.
 */
function formataAlerta(header,text) {
    var openAlert = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>';
    var closeAlert = '</div>';
    return openAlert +
        '<strong>' + header + '</strong> ' +
        text +
        closeAlert;
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
    // carreguem el valor de la data en el camp hidden addient
    var data = new Date($("#ap_data").datepicker("getDate"));

    $("#ap_datahidden").val(data.toISOString());

    /*
     * Realitzem la comprovació rutinària de camps del form
     *
     * En cas que alguna cosa sigui incorrecta, ho fem notar
     */

    // buidem les alertes antigues
    $("#ap_alerts").html("");
    var totOk = new Boolean(true);

    // descripcio
    var descr = new String( $("#ap_notes").val() );
    if ( descr.length > 140 ) {
        totOk = false;
        $("#ap_alerts").append( formataAlerta (
            'Descripció',
            'La descripció no pot sobrepassar els 140 caràcters'
        ));
    }

    // alumne
    if ( $("#ap_idalumne").val() < 0 ) {
        totOk = false;
        $("#ap_alerts").append( formataAlerta (
            'Alumne' ,
            'Cal que sel·leccioneu un alumne per a assignar-li la incidència. Escolliu-ne un de la llista d\'alumnes'
        ));
    }

    // professor responsable
    // (aqui fem el control sobre el atribut "name":"ennomde")
    if (! $("#ap_self").is(":checked") ) {
        if ( $("#ap_idprofe").val() < 0 ) {
            totOk = false;
            $("#ap_alerts").append( formataAlerta (
                'Professor responsable' ,
                'Si no sou el professor responsable, sel·leccioneu correctament un professor existent.'
            ));
        }
    } else {
        $("#ap_idprofe").val(profeAutor);
    }

    if (totOk) {
        /*
         * Tot sembla correcte, procedim a serialitzar valors en un array
         */
        var incidencia = $("#ap_form").serializeArray();
        $.post(URLprefix + "novaIncidencia", incidencia, novaI_CB);
    }

    return false;
}

/*
 * Callback per a la resposta AJAX de nova incidència
 */
function novaI_CB(data) {
    $("#accioPrincipal").css("display","none");
    $("#respostaPrincipal").css("display","inherit");
    $("#respostaPrincipal").html(data);
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

function processaConsultaMeves(data) {

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
