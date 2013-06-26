<?php
/* @var $this AmonestacionsController */
/* @var $data Amonestacions */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tipus')); ?>:</b>
	<?php echo CHtml::encode($data->tipus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descripcio')); ?>:</b>
	<?php echo CHtml::encode($data->descripcio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alumne')); ?>:</b>
	<?php echo CHtml::encode($data->alumne); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profe')); ?>:</b>
	<?php echo CHtml::encode($data->profe); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ennomde')); ?>:</b>
	<?php echo CHtml::encode($data->ennomde); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dataRegistre')); ?>:</b>
	<?php echo CHtml::encode($data->dataRegistre); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('horaLectiva')); ?>:</b>
	<?php echo CHtml::encode($data->horaLectiva); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dataLectiva')); ?>:</b>
	<?php echo CHtml::encode($data->dataLectiva); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('situacio')); ?>:</b>
	<?php echo CHtml::encode($data->situacio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notes')); ?>:</b>
	<?php echo CHtml::encode($data->notes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('assignadaEscrita')); ?>:</b>
	<?php echo CHtml::encode($data->assignadaEscrita); ?>
	<br />

	*/ ?>

</div>