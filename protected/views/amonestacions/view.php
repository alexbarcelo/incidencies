<?php
/* @var $this AmonestacionsController */
/* @var $model Amonestacions */

$this->breadcrumbs=array(
	'Amonestacions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Amonestacions', 'url'=>array('index')),
	array('label'=>'Create Amonestacions', 'url'=>array('create')),
	array('label'=>'Update Amonestacions', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Amonestacions', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Amonestacions', 'url'=>array('admin')),
);
?>

<h1>View Amonestacions #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tipus',
		'alumne',
		'profe',
		'ennomde',
		'dataRegistre',
		'horaLectiva',
		'situacio',
		'descripcio',
		'assignadaEscrita',
	),
)); ?>
