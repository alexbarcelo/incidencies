<?php
/* @var $this AmonestacionsController */
/* @var $model Amonestacions */

$this->breadcrumbs=array(
	'Amonestacions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Amonestacions', 'url'=>array('index')),
	array('label'=>'Create Amonestacions', 'url'=>array('create')),
	array('label'=>'View Amonestacions', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Amonestacions', 'url'=>array('admin')),
);
?>

<h1>Update Amonestacions <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>