/*
 * Operativa -- Javascript, jQuery & AJAX
 */

 llistat_tipus = new Array();
 llistat_idtipus = new Array();
 hores = new Array();
 profes = new Array();
 nom_alumne = "";
 id_alumne = 0;
 nom_classe = "";
 id_classe = 0;

$(function(){
    // Seleccionem link actiu del menú principal
    $("#operativa").addClass("active");

    // Amaguem certes coses per defecte
    $("#filtres").hide();

    // Utilitzem la funcio helper d'inicialitzacio per l'estat de certs blocs
    preparaPagina();

    // Sistema de slidetoggle per als filtres d'alumnes
    $("#filtresflip").click(function(e){
        $("#filtres").slideToggle("slow");
        $("#icon_filtres").toggleClass("icon-chevron-down icon-chevron-up");
        e.preventDefault();
    });

    // Menú d'administració (visualització d'amonestacions pendents de validar)
    $("#admin a").click(function() {
      waitingResposta();
      $.get(URLprefix + "escritesPendents/" , processaConsultaPendents)
        .fail(function(data) {
          $("#respostaPrincipal").html('<div class="alert alert-block">' +
            "<h4>Error intern en l'aplicació</h4>"
            + data.status + " " + data.statusText + '</div><div>' + data.responseText + '</div>');
        });
    });

    // Click de generació d'amonestació escrita (sobre la modal de confirmació)
    $("#modalGenEscrita .btn-primary").click(modalGenOk);

    $.get(URLprefix + "llistatTipus", setupAmonestacioTipus);
    $.get(URLprefix + "llistatProfes", setupProfes);
    $.get(URLprefix + "llistatHores", setupHores);

    // Accio quan s'escull un alumne
    var preEscullAlumne = function () {
      // agafem el valor corresponent a l'alumne selected
      al = $("#llista_alumnes option:selected");
      if ( al.length > 0 ) {
        nom_alumne = al.text();
        id_alumne = al.val();
        alumneSeleccionat()
      }
    };

    $("#escull").click(preEscullAlumne);
    $("#llista_alumnes").dblclick(preEscullAlumne);

    // Accio quan s'escull una classe
    $("#escull_classe").click(classeSeleccionada);

    // el llistat de classes, el carreguem
    $.get(URLprefix + "llistatClasses", function(data) {
        $("#filtres_classe").html(data);
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
  creaEscritaOk();
}

/*
 * Funció genèrica de crida AJAX a una modificació escrita.
 * Arregla corresponentment el div de respostaPrincipal per a mostrar
 * la resposta AJAX o bé l'error obtingut (bastant verbose/debug)
 */
function modificaEscritaOk(accio, id, data=null) {
  // Buidem la pàgina, i deixem una espera fins que l'AJAX torni.
  preparaPagina();
  waitingResposta();
  $("#respostaPrincipal").load(
    URLprefix + accio + "Escrita/" + id ,
    data,
    function(response, status, xhr) {
      if (status == "error") {
        $("#respostaPrincipal").html('<div class="alert alert-block">' +
          "<h4>Error intern en l'aplicació</h4>"
          + xhr.status + " " + xhr.statusText + '</div><div>' + response + '</div>');
      }
    }
  );
}

// Els tres helpers function que arreglen la crida a modificaEscritaOk
function creaEscritaOk() {
  // Guardem la llista d'incidencies
  var llista = new Array();
  $("tbody .info").each(function() {
    llista.push( $(this).attr("data-id") );
  });
  modificaEscritaOk("crea", id_alumne, {"incidencies": llista} )
}
function validaEscritaOk() {
  modificaEscritaOk("valida", id_escrita);
}
function eliminaEscritaOk() {
  modificaEscritaOk("elimina", id_escrita);
}



/*
 * Inicialitzem les tipiques variables i displays
 * per quan hi ha algun click o canvi de pantalla
 */
function preparaPagina () {
    $("#col_alumnes").show();
    $("#respostaPrincipal").hide();
    initSideCounter();
}

/*
 * Funció genèrica per quan hi ha una espera (típicament, un AJAX
 * s'ha de cridar o s'està executant)
 */
function waitingResposta() {
  $("#respostaPrincipal")
    .html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>')
    .show();
}


/*
 * Quan es selecciona un alumne (doble click o btn adequat)
 *
 * Es realitza la consulta AJAX addient i s'imprimeix el resultat
 * de la consulta (dades d'amonestacions de l'alumne)
 */
function alumneSeleccionat() {
  waitingResposta();
  $.get(URLprefix + "consultaAlumne/" + id_alumne , processaConsultaAlumnes)
    .fail(function(data) {
      $("#respostaPrincipal").html('<div class="alert alert-block">' +
        "<h4>Error intern en l'aplicació</h4>"
        + data.status + " " + data.statusText + '</div><div>' + data.responseText + '</div>');
    });
}

/*
 * Quan es selecciona una classe.
 *
 * La sel·lecció de classe es completa amb una consulta AJAX i resultats
 * al div principal de resposta
 */
function classeSeleccionada() {
  // agafem el valor corresponent a la classe en qüestió
    al = $("#filtres_classe option:selected");
    nom_classe = al.text();
    id_classe = al.val();
  waitingResposta();
    $.get(URLprefix + "consultaClasse/" + id_classe , processaConsultaClasse)
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
 * (amonestació, falta o retard) d'una taula. O, també, es crida quan
 * s'utilitza algun butó del menú helper superior.
 *
 * La funció aquesta actualitza el comptador lateral
 */
function refreshSideCounter() {
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
 * Acció de filtratge d'alumnes
 * Torna una comanda SQL correcta. Sanitized, no explotable per
 * usuaris malintencionats
 */
function filtraAlumnes() {
    var nom = $("#filtres_nom").val();

    $("#llista_alumnes").load(URLprefix + "filtraAlumnes",{'query':nom});
}


/*
 * Aquesta funció es crida com a callback davant de crides AJAX de
 * consulta d'alumnes.
 *
 * data és la variable JSON que conté el contingut de la resposta
 * L'objectiu és mostrar una taula correctament formatada
 */
function processaConsultaAlumnes(data) {
  // capcalera del document
  var capDoc = '<h2>Consulta d\'alumne:</h2>' +
    '<h1><small>' + nom_alumne + '</small></h1>' +
    '<div id="subHeader"></div>';

  var
    totalEs = 0,
    totalAm = 0,
    totalR = 0,
    totalF = 0;

  var massCounter = {};
  var mapCorrelatiu = {};

  /*
   * Prepararem 4 taules:
   *
   * Amonestacions escrites (per a incrustar en un well)
   * amonestacions [orals] (grossa)
   * Dos "mitges" per faltes i retards
   */
  var taulaEs =
    '<table class="table table-hover table-condensed" id="taulaEs">' +
    '<thead><tr>' + '<th>#</th>' + '<th>Validada</th>' + '<th>Am. Orals</th>' +
    '<th>Faltes</th>' + '<th>Retards</th>' +
    '<th class="adminView"></th><th class="adminView"></th><th></th>' +
    '</tr></thead><tbody>';

  var taulaAm =
    '<table class="table table-hover table-condensed table-striped" id="taulaAm">' +
    '<thead><tr>'+ '<th>#</th>' + '<th>Professor</th>' +
    '<th>Hora</th>' + '<th>Data</th>' + '<th>Comentaris</th>' +
    '</tr></thead><tbody>';
  var taulaR =
    '<table class="table table-hover table-condensed table-striped" id="taulaR">' +
    '<thead><tr>'+ '<th>#</th>' + '<th>Professor</th>' +
    '<th>Hora</th>' + '<th>Data</th>' +
    '</tr></thead><tbody>';
  var taulaF =
    '<table class="table table-hover table-condensed table-striped" id="taulaF">' +
    '<thead><tr>'+ '<th>#</th>' + '<th>Professor</th>' +
    '<th>Hora</th>' + '<th>Data</th>' +
    '</tr></thead><tbody>';

  /*
   * Procedim a processar "data" i a fer un html addient per a
   * tota la informació rebuda.
   */

  // Primer carreguem les escrites, per a tenir l'estructura i variables creades
  $.each(data["escrites"] , function (index,value) {
    var classValida = "";
    totalEs ++;
    taulaEs += '<tr data-id="' + value['id'] + '" class="trEscrita warning">';
    taulaEs += '<td>' + value['numCorrelatiu'] + '</td>';
    if ( value['validada'] == '1') {
      taulaEs += '<td>Sí</td>';
      classValida = "icon-ok icon-white adminView";
    } else {
      taulaEs += '<td>No</td>';
      classValida = "icon-ok iValida adminView";
    }
    taulaEs += '<td><span id="tdAm' + value['id'] + '"></span></td>';
    taulaEs += '<td><span id="tdF' + value['id'] + '"></span></td>';
    taulaEs += '<td><span id="tdR' + value['id'] + '"></span></td>';
    taulaEs += '<td><i data-id="' + value['id'] + '" class="' + classValida + '"></i></td>';
    taulaEs += '<td><i data-id="' + value['id'] + '" class="adminView icon-remove iElimina"></i></td>';
    taulaEs += '<td><a href="' + URLprefix + 'consultaPDF/' +
      value['id'] + '"><i class="icon-print"></i></a></td>';
    taulaEs += '</tr>';
    massCounter["#tdAm" + value['id']] = 0;
    massCounter["#tdF" + value['id']] = 0;
    massCounter["#tdR" + value['id']] = 0;
    mapCorrelatiu[value["id"]] = value["numCorrelatiu"];
  });

  // Després repassem totes les incidències, contant adequadament les quantitats
  $.each(data["incidencias"], function (index, value) {
    var trMetadata = 'data-id="' + value['id'] + '"';
    var assignada = false;
    var assCorr = '-';

    if (value['idEscrites']) {
      trMetadata += ' data-idEs="' + value['idEscrites'] + '" class="trAssignadaEscrita warning"';
      assignada = true;
      assCorr = mapCorrelatiu[value['idEscrites']];
    } else {
      trMetadata += 'class="trAssignable"';
    }

    if (value['idTipoIncidencias'] > 0) {
      if (llistat_idtipus[value['idTipoIncidencias']].simbolo == "AM") {
        if (!assignada) {
          totalAm++;
        } else {
          massCounter["#tdAm" + value['idEscrites']]++;
        }
        taulaAm += '<tr '+ trMetadata +'>';
        taulaAm += '<td>' + assCorr + '</td>';
        taulaAm += '<td>' + profes[value['idProfesores']] + '</td>';
        taulaAm += '<td>' + hores[value['idHorasCentro']] + '</td>';
        taulaAm += '<td>' + value['dia'] + '</td>';

        if ( value['comentarios'] == "" || value['comentarios'] == null ) {
          taulaAm += '<td>--</td>';
        } else {
          taulaAm += '<td>' + value['comentarios'] + '</td>';
        }
        taulaAm += '</tr>';
      } else if (llistat_idtipus[value['idTipoIncidencias']].simbolo == "FA") {
        if (!assignada) {
          totalF++;
        } else {
          massCounter["#tdF" + value['idEscrites']]++;
        }
        taulaF += '<tr '+ trMetadata +'>'
        taulaF += '<td>' + assCorr + '</td>';
        taulaF += '<td>' + profes[value['idProfesores']] + '</td>';
        taulaF += '<td>' + hores[value['idHorasCentro']] + '</td>';
        taulaF += '<td>' + value['dia'] + '</td>';
        taulaF += '</tr>';
      } else if (llistat_idtipus[value['idTipoIncidencias']].simbolo == "RE") {
        if (!assignada) {
          totalR++;
        } else {
          massCounter["#tdR" + value['idEscrites']]++;
        }
        taulaR += '<tr '+ trMetadata +'>'
        taulaR += '<td>' + assCorr + '</td>';
        taulaR += '<td>' + profes[value['idProfesores']] + '</td>';
        taulaR += '<td>' + hores[value['idHorasCentro']] + '</td>';
        taulaR += '<td>' + value['dia'] + '</td>';
        taulaR += '</tr>';
      }
    }
  });

  // tanquem totes
  taulaEs += '</tbody></table> <!-- taula Escrites -->';
  taulaAm = '<h3>Amonestacions (' + totalAm + ')</h3>' + taulaAm +
    '</tbody></table> <!-- taula Amonestacions -->';
  taulaR = '<h3>Retards (' + totalR + ')</h3>' + taulaR +
    '</tbody></table> <!-- taula Retards -->';
  taulaF = '<h3>Faltes (' + totalF + ')</h3>' + taulaF +
    '</tbody></table> <!-- taula Faltes -->';

  initSideCounter();
  $("#modalNom").text(nom_alumne);

  // Volcat de tota l'estructura html al div central d'informació
  $("#respostaPrincipal").html(capDoc + taulaAm +
    '<div class="row-fluid">' +
    '<div class="span6">' + taulaR + '</div>' +
    '<div class="span6">' + taulaF + '</div>' +
    '</div> <!-- row-fluid de faltes i retards -->' );

  /*
   * Pas important: mostrar el menú superior.
   *
   * Aquest menú aporta maneres a l'usuari (client-side) de visualitzar
   * i seleccionar per blocs.
   */
  $("#subHeader").html(
'<div class="well well-small" id="divOpcions">' +
'  <p>' +
'    <span>Opcions de visualització de les incidències:</span>' +
'    <div class="btn-group" data-toggle="buttons-radio">' +
'      <button type="button" class="btn btn-info" id="vis_noAssignades" >Només no assignades</button>' +
'      <button type="button" class="btn btn-info" id="vis_Totes">Totes</button>' +
'      <button type="button" class="btn btn-info" id="vis_Cap">No en mostris cap</button>' +
'    </div>' +
'  </p>' +
'  <hr>' +
'  <p>' +
'    <span>Seleccionar o eliminar la selecció de:</span>' +
'    <div class="btn-group" data-toggle="buttons-checkbox">' +
'      <button type="button" class="btn btn-info" id="sel_am" >Amonestacions orals</button>' +
'      <button type="button" class="btn btn-info" id="sel_f" >Faltes</button>' +
'      <button type="button" class="btn btn-info" id="sel_r" >Retards</button>' +
'    </div>' +
'  </p>' +
'</div><!--/#divOpcions -->' +
'<div class="well well-small">' +
'  <h4>' +
'    <a href="#" id="escritesflip">' +
'Amonestacions Escrites (' + totalEs + ')' +
'      <i id="icon_escrites" class="icon-chevron-down"></i>' +
'    </a>' +
'  </h4>' +
'    <div id="escrites">' + taulaEs + '</div> <!-- /#escrites -->' +
'</div><!--/.well -->'
  );

    // Un cop carregat el div i taula, procedim a inicialitzar coses.

    // Click en una escrita
    $(".trEscrita").click(function() {
      var id = $(this).attr("data-id");
      // Canviem la visibilitat de la sel·lecció
      $("tr[data-idEs=" + id + "]").toggle();
      // i desmarquem totes les opcions de visibilitat
      $("#vis_noAssignades").removeClass("active");
      $("#vis_Totes").removeClass("active");
      $("#vis_Cap").removeClass("active");
    });

    // Click per validar
    $(".iValida").click(function() {
      if ( confirm("Us disposeu a validar una amonestació escrita. N'esteu segurs?")) {
        id_escrita = $(this).attr("data-id");
        validaEscritaOk();
      }
    });

    // Click per eliminar una daixonses
    $(".iElimina").click(function() {
      if ( confirm("Us disposeu a eliminar una amonestació escrita. N'esteu segurs?")) {
        id_escrita = $(this).attr("data-id");
        eliminaEscritaOk();
      }
    });

    // Assignem els valors calculats a la taula, als span corresponents
    for (var x in massCounter) {
      $(x).text(massCounter[x]);
    }

    // Totes estan visibles, així que deixem marcat el botó de visible
    $("#vis_Totes").addClass("active");

    /*
     * Binds dels botons de visibilitat d'incidències
     */
    $("#vis_noAssignades").click(function(){
      $(".trAssignable").show();
      $(".trAssignadaEscrita").hide();
    });
    $("#vis_Totes").click(function(){
      $(".trAssignable").show();
      $(".trAssignadaEscrita").show();
    });
    $("#vis_Cap").click(function(){
      $(".trAssignable").hide();
      $(".trAssignadaEscrita").hide();
    });

    /*
     * Binds dels botons de batch-selecció d'incidències
     */
    $("#sel_am").click(function(){
      if ($(this).hasClass("active")) {
        //de-seleccionem
        $("#taulaAm .trAssignable").removeClass("info");
      } else {
        //seleccionem
        $("#taulaAm .trAssignable").addClass("info");
      }
      refreshSideCounter();
    });
    $("#sel_f").click(function(){
      if ($(this).hasClass("active")) {
        //de-seleccionem
        $("#taulaF .trAssignable").removeClass("info");
      } else {
        //seleccionem
        $("#taulaF .trAssignable").addClass("info");
      }
      refreshSideCounter();
    });
    $("#sel_r").click(function(){
      if ($(this).hasClass("active")) {
        //de-seleccionem
        $("#taulaR .trAssignable").removeClass("info");
      } else {
        //seleccionem
        $("#taulaR .trAssignable").addClass("info");
      }
      refreshSideCounter();
    });

    // No ens oblidem de mostrar / amagar la part d'amonestacions escrites
    $("#escrites").hide();
    $("#escritesflip").click(function(e){
      $("#escrites").slideToggle("slow");
      $("#icon_escrites").toggleClass("icon-chevron-down icon-chevron-up");
      e.preventDefault();
    });

  // Si es fa click sobre alguna fila assignable, la sel·leccionem
  $(".trAssignable").click(function() {
    $(this).toggleClass("info");
    refreshSideCounter();

    //i ens assegurem de que cap botó de selecció estigui actiu
    $("#sel_am").removeClass("active");
    $("#sel_f").removeClass("active");
    $("#sel_r").removeClass("active");
  });
}

/*
 * Aquesta funció es crida com a callback davant de crides AJAX de
 * consulta de classe.
 *
 * data és la variable JSON que conté el contingut de la resposta
 * L'objectiu és mostrar una taula correctament formatada
 */
function processaConsultaClasse(data) {
    // capcalera del document
    var capDoc = '<h2>Consulta de la classe:</h2>' +
        '<h1><small>' + nom_classe + '</small></h1>';
    var massCounter = {};

    // preparem la taula
    var taula =
        '<table class="table table-striped" id="taulaAlumnes">' +
        '<thead><tr>'+ '<th>#</th>' + '<th>Alumne</th>' +
        '<th>Am.E (Pendents)</th>' + '<th>Am.O</th>' + '<th>Faltes</th>' +
        '<th>Retards</th>' + '</tr></thead>' + '<tbody>';

    /*
     * Procedim a processar els alumnes
     */
    i = 0;
    $.each(data["alumnos"], function (index, value) {
    taula += '<tr><td>' + ++i + '</td>';
        taula += '<td><a href="#" class="rowAlumne" data-id="' + value["id"] + '">' + value["nombre"] + '</a></td>';
        taula += '<td><span id="tdEs' + value["id"] + '"></span> ' +
      '(<span id="tdEsNoVal' + value["id"] + '"></span>)</td>';
        taula += '<td><span id="tdAm' + value["id"] + '"></span></td>';
        taula += '<td><span id="tdF' + value["id"] + '"></span></td>';
        taula += '<td><span id="tdR' + value["id"] + '"></span></td>';
        taula += '</tr>';
        massCounter["#tdEs" + value["id"]] = 0;
        massCounter["#tdEsNoVal" + value["id"]] = 0;
        massCounter["#tdAm" + value["id"]] = 0;
    massCounter["#tdF" + value["id"]] = 0;
        massCounter["#tdR" + value["id"]] = 0;
    });

    /*
     * I ara a contar totes les incidencies desglosant per alumne
     */
    $.each(data["incidencias"], function (index, value) {
    if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "AM") {
      massCounter["#tdAm" + value["idAlumnos"]]++;
        } else if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "FA") {
      massCounter["#tdF" + value["idAlumnos"]]++;
        } else if (value['idTipoIncidencias'] > 0 && llistat_idtipus[value['idTipoIncidencias']].simbolo == "RE") {
      massCounter["#tdR" + value["idAlumnos"]]++;
    }
    });

    /*
     * Per acabar, afegim totes les escrites
     */
    $.each(data["escrites"], function (index, value) {
    if (value['validada'] == '1') {
      massCounter["#tdEs" + value["idAlumnos"]]++;
    } else {
      massCounter["#tdEsNoVal" + value["idAlumnos"]]++;
    }
    });

    // tanquem
    taula += '</tbody></table> <!-- taula Amonestacions -->';

    // línia de impressió
    printFoot = '<div><a id="accioPrint" href="#"><i class="icon-print"></i> Imprimir aquest resum</a>' +
        '<span style="float:right;">' + todayIs + '</span></div>';

    // Volcat de tota l'estructura html al div central d'informació
    $("#respostaPrincipal").html(capDoc + taula + printFoot);

    $("#accioPrint").click(function(){
        $('#respostaPrincipal').printElement(
        {
            printMode:'popup',
            pageTitle:'Consulta classe ' + nom_classe,
            overrideElementCSS: [URLbase + '/css/print.css']
        });
    });

    // Assignem els valors calculats al nou html
    for (var x in massCounter) {
    $(x).text(massCounter[x]);
  }

  $(".rowAlumne").click(function() {
    // agafem el valor corresponent a l'alumne selected
    id_alumne = $(this).attr("data-id");
    nom_alumne = $(this).text();
    alumneSeleccionat()
  });
}

/*
 * Aquesta funció es crida com a callback davant de crides AJAX de
 * consulta d'amonestacions escrites pendents de validar.
 *
 * data és la variable JSON que conté el contingut de la resposta
 * L'objectiu és mostrar una taula correctament formatada, contenint
 * el número correlatiu i les amonestacions associades (informació
 * resumida).
 *
 * S'han de fer els links addients per a que des de la consulta es
 * pugui:
 *   * Validar una amonestació
 *   * Eliminar una amonestació
 *   * Consultar l'alumne
 */
function processaConsultaPendents(data) {
  // capcalera del document
  var capDoc = '<h1>Consultant amonestacions pendent de validar</h1>' +
    '<h1><small>Per a obtenir més informació cliqueu sobre el camp desitjat</small></h1>';

  var massCounter = {};

  /*
   * Prepararem la taula
   */
  var taulaP =
    '<table class="table table-hover table-condensed" id="taulaEs">' +
    '<thead><tr>' + '<th>Alumne</th>' + '<th>#</th>' +
    '<th>Am. Orals</th>' + '<th>Faltes</th>' + '<th>Retards</th>' +
    '<th><!-- valida --></th><th><!-- elimina --></th><th><!-- imprimeix --></th>' +
    '</tr></thead><tbody>';

  /*
   * Procedim a processar "data" i a fer un html addient per a
   * tota la informació rebuda.
   */
  if (data["escrites"].length > 0) {
    // si hi ha dades, clar

    $.each(data["escrites"] , function (index,value) {
      taulaP += '<tr>';
      taulaP += '<td data-idAlumno="' + value['idAlumnos'] +
        '" class="alumno">' + value['nombre'] + '</td>';
      taulaP += '<td>' + value['numCorrelatiu'] + '</td>';
      taulaP += '<td><span id="tdAm' + value['id'] + '"></span></td>';
      taulaP += '<td><span id="tdF' + value['id'] + '"></span></td>';
      taulaP += '<td><span id="tdR' + value['id'] + '"></span></td>';
      taulaP += '<td><i data-id="' + value['id'] + '" class="icon-ok iValida"></i></td>';
      taulaP += '<td><i data-id="' + value['id'] + '" class="icon-remove iElimina"></i></td>';
      taulaP += '<td><a href="' + URLprefix + 'consultaPDF/' +
        value['id'] + '"><i class="icon-print"></i></a></td>';
      taulaP += '</tr>';
      massCounter["#tdAm" + value['id']] = 0;
      massCounter["#tdF" + value['id']] = 0;
      massCounter["#tdR" + value['id']] = 0;
    });

    // Després repassem totes les incidències, contant adequadament les quantitats
    $.each(data["incidencias"], function (index, value) {
      if (value['idTipoIncidencias'] > 0) {
        if (llistat_idtipus[value['idTipoIncidencias']].simbolo == "AM") {
          massCounter["#tdAm" + value['idEscrites']]++;
        } else if (llistat_idtipus[value['idTipoIncidencias']].simbolo == "FA") {
          massCounter["#tdF" + value['idEscrites']]++;
        } else if (llistat_idtipus[value['idTipoIncidencias']].simbolo == "RE") {
          massCounter["#tdR" + value['idEscrites']]++;
        }
      }
    });

    // tanquem totes
    taulaP += '</tbody></table> <!-- taula Pendents (principal) -->';
  } else {
    taulaP =
    '<div class="alert alert-block alert-success">' +
    '  <h4>No hi ha validacions pendents</h4>' +
    '  Totes les amonestacions escrites ja han estat validades' +
    '</div>';
  }

  // Volcat de tota l'estructura html al div central d'informació
  $("#respostaPrincipal").html(capDoc + taulaP);

  /*
   * Pas posterior: mostra els contadors i arregla els clicks
   */

  // Click en un alumne
  $("td.alumno").click(function() {
    nom_alumne = $(this).text();
    id_alumne  = $(this).attr("data-idAlumno");
    alumneSeleccionat();
  });

  // Click per validar
  $(".iValida").click(function() {
    if ( confirm("Us disposeu a validar una amonestació escrita. N'esteu segurs?")) {
      id_escrita = $(this).attr("data-id");
      validaEscritaOk();
    }
  });

  // Click per eliminar una daixonses
  $(".iElimina").click(function() {
    if ( confirm("Us disposeu a eliminar una amonestació escrita. N'esteu segurs?")) {
      id_escrita = $(this).attr("data-id");
      eliminaEscritaOk();
    }
  });

  // Assignem els valors calculats a la taula, als span corresponents
  for (var x in massCounter) {
    $(x).text(massCounter[x]);
  }
}
