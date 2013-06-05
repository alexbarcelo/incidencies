<?php
/* @var $this ProfesController */
/* @var $model Profes */

$this->breadcrumbs=array(
	'Profes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Profes', 'url'=>array('index')),
	array('label'=>'Create Profes', 'url'=>array('create')),
	array('label'=>'View Profes', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Profes', 'url'=>array('admin')),
);
?>

<h1>Update Profes <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>