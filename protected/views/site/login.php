<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Entrada';
?>

<div class="page-header">
  <h1>Entrada</h1>
</div>

<form id="login-form" method="post" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/login">
<fieldset>
  <legend>Introdueixi la següent informació per entrar al sistema</legend>
  <label>Identificador d'usuari</label>
  <input type="text" id="LoginForm_username" name="LoginForm[username]">

  <label>Contrasenya</label>
  <input type="password" id="LoginForm_password" name="LoginForm[password]">
  <label></label>
  <button type="submit" class="btn btn-large btn-primary">Entrar</button>

</fieldset>
</form><!-- form -->

<script>
$(function(){
    $("#entrada").addClass("active");
});
</script>
