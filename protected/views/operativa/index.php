<?php
/* @var $this SiteController */
?>

<div class="span3">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">
      <li class="nav-header">Nova entrada</li>
      <li><a id="retard" class="accions" href="#">Retard</a></li>
      <li><a id="expulsio" class="accions" href="#">Expulsió</a></li>
      <li><a id="amonestacioOral" class="accions" href="#">Amonestació oral</a></li>
      <li><a id="amonestacioEscrita" class="accions" href="#">Amonestació escrita</a></li>
      <li class="nav-header">Consulta</li>
      <li><a id="meves" class="consultes" href="#">Les meves</a></li>
      <li><a id="peralumnes" class="consultes" href="#">Per alumnes</a></li>
      <li><a id="perclasses" class="consultes" href="#">Per classes</a></li>
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
    <p><button id="escull" class="span9">Escull l'alumne</button></p>
    <p> &nbsp </p>
  </div><!--/.well col_alumnes-->

  <div class="well" id="col_meves">
    Llistat de les meves amonestacions
  </div><!--/.well col_meves -->
</div><!--/span-->

<div class="span9">
  <!-- Una hero-unit un pèl més petita, marges reduïts-->
  <div class="hero-unit hidden-phone" style="padding: 10px; margin-bottom: 10px">
    <h1>Operativa</h1>
    <p>Des d'aquí podreu introduir i consultar entrades de la base de dades d'incidències.</p>
  </div>
  <div id="respostaPrincipal">
  </div><!-- /#respostaPrincipal-->
  <div id="accioPrincipal">
    <form id="ap_form" method="post">
      <fieldset>
        <input type="hidden" name="tipus" id="ap_tipus" value="">
        <input type="hidden" name="profe" value="<?php echo Yii::app()->user->getState('uid',0); ?>">
        <script>profeAutor="<?php echo Yii::app()->user->getState('uid',0); ?>"</script>
        <legend id="ap_legend">Legend</legend>
        <div id="ap_alerts"></div>
        <div class="row-fluid">
          <div class="span6">
            <label>Descripció</label>
            <textarea rows="3" id="ap_notes" name="notes" class="span12" style="resize: vertical;"></textarea>
            <span class="help-block">Introduïu opcionalment una descripció (140 caràcters)</span>
            <label>Alumne</label>
            <input type="text" class="input-medium" id="ap_alumne" disabled>
            <input type="hidden" id="ap_idalumne" name="alumne" value="-1">
            <span class="help-block">Sel·leccioneu-lo al menú lateral</span>
            <label class="checkbox">
              <input type="checkbox" id="ap_self" checked>Sóc el responsable de la incidència
            </label>

            <div class="row-fluid">
              <div id="ap_divprofe" class="well well-small span11">
                <label>Professor responsable de la incidència:</label>
                <div class="input-prepend input-append span11">
                  <button class="btn" type="button" id="ap_modificaprofe">Modifica</button>
                  <input type="text" id="ap_profe" data-provide="typeahead" autocomplete="off" class="input-small">
                  <span class="add-on"><i id="ap_checkprofe" class="icon-remove"></i></span>
                </div>
                <input type="hidden" id="ap_idprofe" name="ennomde">
                <span class="help-block">Comenci a introduir el nom o cognoms del professor. Asseguri's de sel·leccionar una entrada correcta de les opcions que apareixeran.</span>
              </div>
            </div> <!-- /row -->
            <label>Situació:</label>
            <input type="text" class="span12" name="situacio" id="ap_situacio" placeholder="Lloc, aula, espai...">
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
              <option value=9>15:00</option>
              <option value=10>16:00</option>
              <option value=11>Altres</option>
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
  <div class="alert alert-block alert-info" id="consultaAlumnes" style="display: none">
    <h4>Sel·lecció d'alumne</h4>
    Realitzeu la tria de l'alumne al bloc lateral per a continuar la consulta
    d'incidències.
  </div>
  <!-- Modal -->
  <div id="modalClasses" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="mClassesLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="mClassesLabel">Sel·leccioneu una classe</h3>
    </div>
    <div class="modal-body">
      <p>Escolliu una classe per a realitzar-hi la consulta d'incidències:</p>
      <select class="span9" id="mFiltresClasse"></select>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel·la</button>
      <button class="btn btn-primary">Realitzar consulta</button>
    </div>
  </div>
</div><!--/span-->

<!-- Le more javascript
================================================== -->
<!-- Operativa-specific -->
<script>URLprefix = "<?php echo Yii::app()->createUrl('operativa'); ?>/";</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/operativa.js"></script>
