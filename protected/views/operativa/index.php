<?php
/* @var $this SiteController */
?>

        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Nova entrada</li>
              <li><a id="retard" class="accions" href="#">Retard</a></li>
              <li><a id="amonestacioOral" class="accions" href="#">Amonestació oral</a></li>
              <li><a id="expulsio" class="accions" href="#">Expulsió</a></li>
              <li class="nav-header">Consulta</li>
              <li><a href="#">Les meves</a></li>
              <li><a href="#">Dels meus alumnes</a></li>
            </ul>
          </div><!--/.well -->

          <div class="well">
            <p>Alumnes:</p>
<select class="span12" id="llista_alumnes" size=10>
</select>
<div class="well well-small">
    <p><a href="#" id="filtresflip">
        Filtres i opcions
         <i id="icon_filtres" class="icon-chevron-down"></i>
    </a></p>
    <div id="filtres">
        <fieldset>
        <label>Dades de l'alumne:</label>
        <input type="text" class="span12" id="filtres_nom" placeholder="Nom"></input>
        <input type="text" class="span12" id="filtres_cognom" placeholder="Cognom"></input>
        <label>Classe:</label>
        <select class="span12" id="filtres_classe"></select>
        <button type="submit" id="filtres_refresca" class="btn">Refresca</button>
        </fieldset>
    </div>
</div><!--/.well -->
    <p><button id="escull" class="span6">Escull l'alumne</button></p>
    <p>&nbsp</p>

          </div><!--/.well -->

        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit">
            <h1>Operativa</h1>
            <p>Des d'aquí podreu introduir i consultar entrades de la base de dades d'incidències.</p>
          </div>
    <div id="accioPrincipal">
        <form id="ap_form">
            <fieldset>
                <legend id="ap_legend">Legend</legend>
                <label>Introduiu una breu descripció</label>
                <input type="text" id="ap_descripcio" placeholder="Descripcio">
                <label>Alumne</label>
                <input type="text" id="ap_alumne" disabled>
                <input type="hidden" id="ap_idalumne" value="-1">
                <span class="help-block">Sel·leccioneu-lo al menú lateral</span>
                <label class="checkbox">
                    <input type="checkbox" id="ap_self" checked>Sóc el responsable de la incidència
                </label>
                <div class="row">
                <div id="ap_divprofe" class="well well-small span6">
                    <label>Professor responsable de la incidència:</label>
                    <input type="text" id="ap_profe" data-provide="typeahead">
                    <input type="hidden" id="ap_idprofe">
                    <span class="help-block">Comenci a introduir el nom o cognoms del professor. Asseguri's de sel·leccionar una entrada correcta de les opcions que apareixeran.</span>
                </div>
                </div> <!-- /row -->
                <label>...</label>
                <button type="submit" id="ap_btn" class="btn">Guardar incidència</button>
            </fieldset>
        </form>
    </div>
        </div><!--/span-->

<!-- Le more javascript
================================================== -->
<!-- Operativa-specific -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/operativa.js"></script>
