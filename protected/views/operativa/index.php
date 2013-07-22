<?php
/* @var $this SiteController */
?>

<div class="span3">
  <div class="well" id="col_alumnes">
    <p>Alumnes:</p>

    <div class="well well-small">
      <p><a href="#" id="filtresflip">
        Filtres i opcions
        <i id="icon_filtres" class="icon-chevron-down"></i>
      </a></p>
      <div id="filtres">
        <fieldset>
          <label>Nom de l'alumne:</label>
          <input type="text" class="span12" id="filtres_nom" placeholder="Nom"></input>
          <label>Classe:</label>
          <select class="span12" id="filtres_classe"></select>
          <button type="submit" id="filtres_refresca" class="btn">Refresca</button>
        </fieldset>
      </div>
    </div><!--/.well -->
    <select class="span12" id="llista_alumnes" size=10></select>
    <p><button id="escull" class="span9">Escull l'alumne</button></p>
    <p> &nbsp </p>
  </div><!--/.well col_alumnes-->
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
