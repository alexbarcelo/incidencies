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

<form method=post name=LoginForm>
<fieldset>
  <legend>Introdueixi la següent informació per entrar al sistema</legend>
  <label>Identificador d'usuari</label>
  <input type="text" name="username" placeholder="<?php echo $model->username ?>">

  <label>Contrasenya</label>
  <input type="password" name="password">

  <button type="submit" class="btn">Entrar</button>

</fieldset>
</form><!-- form -->
