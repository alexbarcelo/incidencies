<?php
/* @var $this AmonestacionsController */
/* @var $model Amonestacions */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'amonestacions-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tipus'); ?>
		<?php echo $form->textField($model,'tipus'); ?>
		<?php echo $form->error($model,'tipus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alumne'); ?>
		<?php echo $form->textField($model,'alumne',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'alumne'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'profe'); ?>
		<?php echo $form->textField($model,'profe'); ?>
		<?php echo $form->error($model,'profe'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ennomde'); ?>
		<?php echo $form->textField($model,'ennomde'); ?>
		<?php echo $form->error($model,'ennomde'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dataRegistre'); ?>
		<?php echo $form->textField($model,'dataRegistre'); ?>
		<?php echo $form->error($model,'dataRegistre'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'horaLectiva'); ?>
		<?php echo $form->textField($model,'horaLectiva'); ?>
		<?php echo $form->error($model,'horaLectiva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'situacio'); ?>
		<?php echo $form->textField($model,'situacio',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'situacio'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descripcio'); ?>
		<?php echo $form->textArea($model,'descripcio',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'descripcio'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'assignadaEscrita'); ?>
		<?php echo $form->textField($model,'assignadaEscrita'); ?>
		<?php echo $form->error($model,'assignadaEscrita'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->