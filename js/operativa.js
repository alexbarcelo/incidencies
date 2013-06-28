/*
 * Operativa -- Javascript, jQuery & AJAX
 */

 URLprefix = "barcelo/yii/incidencies/index.php/operativa/"
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
    $("#col_meves").css("display","none");

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
     *
     * Fem visible el que toca i tapem el que no s'ha de mostrar.
     */
    $(".accions").click(function(){
        $("#accioPrincipal").css("display","inherit");
        $("#col_alumnes").css("display","inherit");
        $("#respostaPrincipal").css("display","none");
        $("#col_meves").css("display","none");
        $(".accions").each(function() {
            $(this).parent().removeClass("active");
        });
    });

    // similar però amb les consultes, no es mostra columna d'usuaris
    $(".consultes").click(function(){
        $("#col_alumnes").css("display","none");
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
    $("#retard").parent().addClass("active");
    $("#ap_tipus").val(llistat_tipus["retard"].id);
}

function amonestacioOral() {
    $("#ap_legend").html("Amonestació oral <small>"+ llistat_tipus["amonestacioOral"].longDescr +"</small>")
    $("#amonestacioOral").parent().addClass("active");
    $("#ap_tipus").val(llistat_tipus["amonestacioOral"].id);
}

function expulsio() {
    $("#ap_legend").html("Expulsió <small>"+ llistat_tipus["expulsio"].longDescr +"</small>")
    $("#expulsio").parent().addClass("active");
    $("#ap_tipus").val(llistat_tipus["expulsio"].id);
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
    var descr = new String( $("#ap_descripcio").val() );
    if ( descr.length == 0 ) {
        totOk = false;
        $("#ap_alerts").append( formataAlerta (
            'Descripció',
            'El camp descripció no pot estar buit, escriviu-hi alguna descripció curta per a la incidència'
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
        $("#ap_idprofe").attr("name", "ennomde");
        if ( $("#ap_idprofe").val() < 0 ) {
            totOk = false;
            $("#ap_alerts").append( formataAlerta (
                'Professor responsable' ,
                'Si no sou el professor responsable, sel·leccioneu correctament un professor existent.'
            ));
        }
    }   else {
        $("#ap_idprofe").removeAttr("name");
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
