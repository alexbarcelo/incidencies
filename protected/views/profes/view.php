<?php
/* @var $this ProfesController */
/* @var $model Profes */

$this->breadcrumbs=array(
	'Profes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Profes', 'url'=>array('index')),
	array('label'=>'Create Profes', 'url'=>array('create')),
	array('label'=>'Update Profes', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Profes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Profes', 'url'=>array('admin')),
);
?>

<h1>View Profes #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'equip_directiu',
		'tutor',
		'nom',
		'email',
		'password',
	),
)); ?>
