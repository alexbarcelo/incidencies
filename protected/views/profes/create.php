<?php
/* @var $this ProfesController */
/* @var $model Profes */

$this->breadcrumbs=array(
	'Profes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Profes', 'url'=>array('index')),
	array('label'=>'Manage Profes', 'url'=>array('admin')),
);
?>

<h1>Create Profes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>