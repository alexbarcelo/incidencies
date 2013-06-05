<?php
/* @var $this AlumnesController */
/* @var $model Alumnes */

$this->breadcrumbs=array(
	'Alumnes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Alumnes', 'url'=>array('index')),
	array('label'=>'Create Alumnes', 'url'=>array('create')),
	array('label'=>'Update Alumnes', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Alumnes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Alumnes', 'url'=>array('admin')),
);
?>

<h1>View Alumnes #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nom',
		'cognom',
		'emailContacte',
		'classe',
	),
)); ?>
