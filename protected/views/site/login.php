<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<div class="page-header">
  <h1>Entrada</h1>
</div>

<form id="login-form" method="post" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/login">
<fieldset>
  <legend>Introdueixi la següent informació per entrar al sistema</legend>
  <label>Identificador d'usuari</label>
  <input type="text" id="LoginForm_username" value="<?php echo $model->username ?>" name="LoginForm[username]"/>

  <label>Contrasenya</label>
  <input type="password" id="LoginForm_password" name="LoginForm[password]">

  <button type="submit" class="btn">Entrar</button>

</fieldset>
</form><!-- form -->
