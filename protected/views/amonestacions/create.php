<?php
/* @var $this AmonestacionsController */
/* @var $model Amonestacions */

$this->breadcrumbs=array(
	'Amonestacions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Amonestacions', 'url'=>array('index')),
	array('label'=>'Manage Amonestacions', 'url'=>array('admin')),
);
?>

<h1>Create Amonestacions</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>