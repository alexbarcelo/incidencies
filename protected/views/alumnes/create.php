<?php
/* @var $this AlumnesController */
/* @var $model Alumnes */

$this->breadcrumbs=array(
	'Alumnes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Alumnes', 'url'=>array('index')),
	array('label'=>'Manage Alumnes', 'url'=>array('admin')),
);
?>

<h1>Create Alumnes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>