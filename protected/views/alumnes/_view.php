<?php
/* @var $this AlumnesController */
/* @var $data Alumnes */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nom')); ?>:</b>
	<?php echo CHtml::encode($data->nom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cognom')); ?>:</b>
	<?php echo CHtml::encode($data->cognom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('emailContacte')); ?>:</b>
	<?php echo CHtml::encode($data->emailContacte); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('classe')); ?>:</b>
	<?php echo CHtml::encode($data->classe); ?>
	<br />


</div>