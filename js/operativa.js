/*
 * Operativa -- Javascript, jQuery & AJAX
 */

 llistat_tipus = new Array();
 llistat_idtipus = new Array();
 hores = new Array();
 profes = new Array();
 nom_alumne = "";
 id_alumne = 0;

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
    
    // Click de generació d'amonestació escrita (sobre la modal de confirmació)
    $("#modalGenEscrita .btn-primary").click(modalGenOk);

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
});

/*
 * Inicialització del div lateral contador d'incidències
 */ 
function initSideCounter() {
	// Posem a zero els comptadors de incidències seleccionades
	$("#numAm").text( 0 );
	$("#numF").text( 0 );
	$("#numR").text( 0 );
	$("#modalNumAm").text( 0 );
	$("#modalNumF").text( 0 );
	$("#modalNumR").text( 0 );	
	
	// Assegurar que el botó està ben "disabled"
	$("#btnModalGenEscrita").addClass("disabled")
		.attr("disabled","disabled");
}

/*
 * S'ha acceptat la confirmació de la finestra modal per a generar
 * una escrita.
 */
function modalGenOk() {
	$("#modalGenEscrita").modal('hide');
	// Guardem la llista d'incidencies
	var llista = new Array();
	$("tbody .info").each(function() {
		llista.push( $(this).attr("id") );
	});
	
	// Buidem la pàgina, i deixem una espera fins que l'AJAX torni.
	preparaPagina();
    $("#respostaPrincipal").css("display","inherit")
      .html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>')
	  .load(URLprefix + "creaEscrita/" + id_alumne , {"incidencies": llista}, function(response, status, xhr) {
		if (status == "error") {
		  $("#respostaPrincipal").html('<div class="alert alert-block">' +
			"<h4>Error intern en l'aplicació</h4>"
			+ xhr.status + " " + xhr.statusText + '</div><div>' + response + '</div>');
	    }
	  });
}

/*
 * Inicialitzem les tipiques variables i displays
 * per quan hi ha algun click o canvi de pantalla
 */
function preparaPagina () {
    $("#col_alumnes").css("display","inherit");
    $("#respostaPrincipal").css("display","none");
    initSideCounter();
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
    id_alumne = al.val();
    $("#respostaPrincipal").css("display","inherit")
          .html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>');
    $.get(URLprefix + "consultaAlumne/" + id_alumne , processaConsultaAlumnes)
		.fail(function(data) {
			$("#respostaPrincipal").html('<div class="alert alert-block">' +
				"<h4>Error intern en l'aplicació</h4>"
				+ data.status + " " + data.statusText + '</div><div>' + data.responseText + '</div>');
		});
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

// setup de les hores
function setupHores(data) {
    $.each(data, function(i,h) {
        hores[h.id] = h.inicio;
    });
}

// setup dels profes
function setupProfes(data) {
    $.each(data, function(i,p) {
        profes[p.id] = p.nombre;
    });
}

/*
 * Aquesta funció es crida quan es clica sobre alguna entrada 
 * (amonestació, falta o retard) d'una taula.
 * 
 * El bind es fa per la part de consulta alumne, a l'hora de mostrar
 * els resultats.
 * 
 * La funció aquesta mostra fa visible la sel·lecció feta i actualitza
 * el comptador lateral
 */
function clickOnRow() {
	$(this).toggleClass("info");
	var c = $("#taulaAm tbody .info").length;
	$("#numAm").text( c );
	$("#modalNumAm").text( c );
	c = $("#taulaF tbody .info").length;
	$("#numF").text( c );
	$("#modalNumF").text( c );
	c = $("#taulaR tbody .info").length;
	$("#numR").text( c );
	$("#modalNumR").text( c );
	
	// Assegurar que el botó està ben "enabled"
	$("#btnModalGenEscrita").removeClass("disabled")
		.removeAttr("disabled");
}

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
        '<h1><small>' + nom_alumne + '</small></h1>';
        
    var totalAm = 0, totalR = 0, totalF = 0;

    // preparem 3 taules, amonestacions (grossa) i dos mitges per faltes i retards
    var taulaAm =
        '<table class="table table-striped" id="taulaAm">' +
        '<thead><tr>'+ '<th>Professor</th>' + '<th>Hora</th>' + '<th>Data</th>' +
        '<th>Comentaris</th>' +
        '</tr></thead><tbody>';
    var taulaR =
        '<table class="table table-striped" id="taulaR">' +
        '<thead><tr>'+ '<th>Professor</th>' + '<th>Hora</th>' + '<th>Data</th>' +
        '</tr></thead><tbody>';
    var taulaF =
        '<table class="table table-striped" id="taulaF">' +
        '<thead><tr>'+ '<th>Professor</th>' + '<th>Hora</th>' + '<th>Data</th>' +
        '</tr></thead><tbody>';
    
    /*
     * Procedim a processar "data" i a fer un html addient per a
     * tota la informació rebuda.
     */
    $.each(data, function (index, value) {
        if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "AM") {
			totalAm++;
            taulaAm += '<tr id='+value['id']+'>'
            taulaAm += '<td>' + profes[value['idProfesores']] + '</td>';
            taulaAm += '<td>' + hores[value['idHorasCentro']] + '</td>';
            taulaAm += '<td>' + value['dia'] + '</td>';
            
            if ( value['comentarios'] == "" || value['comentarios'] == null ) {
				taulaAm += '<td>--</td>';
			} else {
				taulaAm += '<td>' +	value['comentarios'] + '</td>';
			}
            taulaAm += '</tr>';
                      
        } else if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "FA") {
			totalF++;
            taulaF += '<tr id='+value['id']+'>'
            taulaF += '<td>' + profes[value['idProfesores']] + '</td>';
            taulaF += '<td>' + hores[value['idHorasCentro']] + '</td>';
            taulaF += '<td>' + value['dia'] + '</td>';
            taulaF += '</tr>';

        } else if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "RE") {
			totalR++;
            taulaR += '<tr id='+value['id']+'>'
            taulaR += '<td>' + profes[value['idProfesores']] + '</td>';
            taulaR += '<td>' + hores[value['idHorasCentro']] + '</td>';
            taulaR += '<td>' + value['dia'] + '</td>';
            taulaR += '</tr>';
		}
    });

    // tanquem
    taulaAm = '<h3>Amonestacions (' + totalAm + ')</h3>' + taulaAm + 
		'</tbody></table> <!-- taula Amonestacions -->';
    taulaR += '<h3>Retards (' + totalR + ')</h3>' +
		'</tbody></table> <!-- taula Retards -->';
    taulaF += '<h3>Faltes (' + totalF + ')</h3>' +
		'</tbody></table> <!-- taula Faltes -->';
    
    initSideCounter();
    $("#modalNom").text(nom_alumne);

    // Volcat de tota l'estructura html al div central d'informació
    $("#respostaPrincipal").html(capDoc + taulaAm +
        '<div class="row-fluid">' + 
        '<div class="span6">' + taulaR + '</div>' + 
        '<div class="span6">' + taulaF + '</div>' +
        '</div> <!-- row-fluid de faltes i retards -->' );
        
    $("tr").click(clickOnRow);
}

/*
 * Acció de filtratge d'alumnes
 * Torna una comanda SQL correcta. Sanitized, no explotable per
 * usuaris malintencionats
 */
function filtraAlumnes() {
    var nom = $("#filtres_nom").val();

    $("#llista_alumnes").load(URLprefix + "filtraAlumnes",{'query':nom});
}
