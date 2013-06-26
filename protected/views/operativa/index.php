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
      <li><a id="lesmeves" href="#">Les meves</a></li>
      <li><a id="consulta" href="#">Per classes i alumnes</a></li>
    </ul>
  </div><!--/.well -->

  <div class="well" id="col_alumnes">
    <p>Alumnes:</p>

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

    <select class="span12" id="llista_alumnes" size=10></select>
    <p><button id="escull" class="span6">Escull l'alumne</button></p>
    <p> &nbsp </p>
  </div><!--/.well col_alumnes-->

  <div class="well" id="col_meves">
    Llistat de les meves amonestacions
  </div><!--/.well col_meves -->
</div><!--/span-->

<div class="span9">
  <div class="hero-unit visible-desktop">
    <h1>Operativa</h1>
    <p>Des d'aquí podreu introduir i consultar entrades de la base de dades d'incidències.</p>
  </div>
  <div id="respostaPrincipal">
  </div><!-- /#respostaPrincipal-->
  <div id="accioPrincipal">
    <form id="ap_form" method="post">
      <fieldset>
        <input type="hidden" name="tipus" id="ap_tipus" value="">
        <input type="hidden" name="profe" id="ap_profe" value="<?php echo Yii::app()->user->getState('uid',0); ?>">
        <legend id="ap_legend">Legend</legend>
        <label>Introduiu una breu descripció</label>
        <input type="text" class="input-large" name="descripcio" id="ap_descripcio" placeholder="Descripció">
        <label>Alumne</label>
        <input type="text" class="input-medium" id="ap_alumne" disabled>
        <input type="hidden" id="ap_idalumne" name="alumne" value="-1">
        <span class="help-block">Sel·leccioneu-lo al menú lateral</span>
        <label class="checkbox">
          <input type="checkbox" id="ap_self" checked>Sóc el responsable de la incidència
        </label>

        <div class="row-fluid">
          <div id="ap_divprofe" class="well well-small span9">
            <label>Professor responsable de la incidència:</label>
            <input type="text" id="ap_profe" data-provide="typeahead" autocomplete="off">
            <input type="hidden" id="ap_idprofe" name="ennomde">
            <span class="help-block">Comenci a introduir el nom o cognoms del professor. Asseguri's de sel·leccionar una entrada correcta de les opcions que apareixeran.</span>
          </div>
        </div> <!-- /row -->

        <div class="row-fluid">
          <div class="span6">
            <label>Situació:</label>
              <input type="text" class="span12" name="situacio" id="ap_situacio" placeholder="Lloc, aula, espai...">
            <label>Notes addicionals per a la incidència</label>
            <textarea rows="3" id="ap_notes" name="notes" class="span12" style="resize: vertical;"></textarea>
          </div>
          <div class="span6">
            <label>Hora lectiva:</label>
            <select id="ap_horalectiva" name="horaLectiva" class="input-small">
              <option value=1>8:00</option>
              <option value=2>9:00</option>
              <option value=3>10:00</option>
              <option value=4>11:00</option>
              <option value=5>11:30</option>
              <option value=6>12:30</option>
              <option value=7>13:30</option>
              <option value=8>14:30</option>
              <option value=9>15:30</option>
              <option value=10>16:30</option>
              <option value=11>17:30</option>
              <option value=12>Altres</option>
            </select>
            <label>Dia:</label>
            <div id="ap_data"></div>
            <input type="hidden" name="dataLectiva" id="ap_datahidden">
          </div>
        </div> <!-- /row -->

        <button type="submit" id="ap_btn" class="btn">Guardar incidència</button>
      </fieldset>
    </form>
  </div><!-- /#accioPrincipal-->
</div><!--/span-->

<!-- Le more javascript
================================================== -->
<!-- Operativa-specific -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/operativa.js"></script>
