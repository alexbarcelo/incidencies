<?php
/* @var $this SiteController */
?>

<div class="span3">
  <div class="well" id="col_alumnes">
    <p>Alumnes:</p>

    <div class="well well-small">
      <p><a href="#" id="filtresflip">
        Filtre per nom
        <i id="icon_filtres" class="icon-chevron-down"></i>
      </a></p>
      <div id="filtres">
        <fieldset>
          <label>Nom de l'alumne:</label>
          <input type="text" class="span12" id="filtres_nom" placeholder="Nom"></input>
		  <label>Resultats:</label>
          <select class="span12" id="llista_alumnes" size=10></select>
        </fieldset>
        <div class="btn-group">
		  <button id="filtres_refresca" class="btn btn-info">Refresca</button>
		  <button id="escull" class="btn btn-primary">Consulta</button>
		</div>
      </div>
    </div><!--/.well -->
    <div class="well well-small">
      <p>Sel·lecció de classe:</p>
      <div class="input-append">
		<select id="filtres_classe" class="span8"></select>
		<button id="escull_classe" class="btn btn-primary" type="button">Consulta</button>
	  </div>
    </div><!--/.well -->
  </div><!--/.well col_alumnes-->
  
  <div class="well well-small" id="sideCounter">
	<h2><small>Sel·lecció actual</small></h3>
    <p>Amonestacions: <span id="numAm"></span></p>
    <p>Retards: <span id="numR"></span></p>
	<p>Faltes: <span id="numF"></span></p>
	<button id="btnModalGenEscrita" href="#modalGenEscrita" role="button" class="btn btn-danger disabled" disabled="disabled" data-toggle="modal">Generar escrita</button>	
  </div><!--/.well col_alumnes-->
  
  <!-- El DIV del modal per a quan es clica sobre el botó "generar escrita" -->
  <div id="modalGenEscrita" class="modal hide fade">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3>Generació d'amonestació escrita</h3>
    </div>
    <div class="modal-body">
      <p>Esteu a punt de crear una amonestació escrita per a l'alumne:<br>
	  <strong><span id="modalNom"></span></strong></p>
	  <p>Agrupant el següent:<br><ul>
		 <li>Amonestacions: <span id="modalNumAm"></span></li>
	     <li>Faltes: <span id="modalNumF"></span></li>
	     <li>Retards: <span id="modalNumR"></span></li>
	  </ul></p>
	  <hr>
	  <p>Assegureu-vos que aquesta combinació és adequada abans de generar l'amonestació escrita i enviar notificació a cap d'estudis</p>
    </div>
    <div class="modal-footer">
      <a href="#" data-dismiss="modal" class="btn">Cancel·la</a>
      <a href="#" class="btn btn-primary">Aplica i genera</a>
    </div>
  </div>

</div><!--/span-->

<div class="span9">
  <!-- Una hero-unit un pèl més petita, marges reduïts-->
  <div class="hero-unit hidden-phone" style="padding: 10px; margin-bottom: 10px">
    <h1>Operativa</h1>
    <p>Des d'aquí es gestiona l'aplicació d'incidències.</p>
  </div>
  <div id="respostaPrincipal">
  </div><!-- /#respostaPrincipal-->
  <div class="alert alert-block alert-info" id="consultaAlumnes" style="display: none">
    <h4>Sel·lecció d'alumne</h4>
    Realitzeu la tria de l'alumne al bloc lateral per a continuar la consulta
    d'incidències.
  </div>
</div><!--/span-->

<!-- Le more javascript
================================================== -->
<!-- Operativa-specific -->
<script>URLprefix = "<?php echo Yii::app()->createUrl('operativa'); ?>/";</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/operativa.js"></script>
