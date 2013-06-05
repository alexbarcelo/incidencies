<?php
/* @var $this AlumnesController */
/* @var $model Alumnes */

$this->breadcrumbs=array(
	'Alumnes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Alumnes', 'url'=>array('index')),
	array('label'=>'Create Alumnes', 'url'=>array('create')),
	array('label'=>'View Alumnes', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Alumnes', 'url'=>array('admin')),
);
?>

<h1>Update Alumnes <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>