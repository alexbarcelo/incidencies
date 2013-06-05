<?php
/* @var $this AlumnesController */
/* @var $model Alumnes */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'alumnes-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nom'); ?>
		<?php echo $form->textField($model,'nom',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'nom'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cognom'); ?>
		<?php echo $form->textField($model,'cognom',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'cognom'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'emailContacte'); ?>
		<?php echo $form->textField($model,'emailContacte',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'emailContacte'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'classe'); ?>
		<?php echo $form->textField($model,'classe'); ?>
		<?php echo $form->error($model,'classe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->