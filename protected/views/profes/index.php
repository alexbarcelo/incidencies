<?php
/* @var $this ProfesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Profes',
);

$this->menu=array(
	array('label'=>'Create Profes', 'url'=>array('create')),
	array('label'=>'Manage Profes', 'url'=>array('admin')),
);
?>

<h1>Profes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
