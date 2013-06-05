<?php
/* @var $this AmonestacionsController */
/* @var $model Amonestacions */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tipus'); ?>
		<?php echo $form->textField($model,'tipus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alumne'); ?>
		<?php echo $form->textField($model,'alumne',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'profe'); ?>
		<?php echo $form->textField($model,'profe'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ennomde'); ?>
		<?php echo $form->textField($model,'ennomde'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dataRegistre'); ?>
		<?php echo $form->textField($model,'dataRegistre'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'horaLectiva'); ?>
		<?php echo $form->textField($model,'horaLectiva'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'situacio'); ?>
		<?php echo $form->textField($model,'situacio',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'descripcio'); ?>
		<?php echo $form->textArea($model,'descripcio',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'assignadaEscrita'); ?>
		<?php echo $form->textField($model,'assignadaEscrita'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->