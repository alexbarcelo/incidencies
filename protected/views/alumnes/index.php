<?php
/* @var $this AlumnesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Alumnes',
);

$this->menu=array(
	array('label'=>'Create Alumnes', 'url'=>array('create')),
	array('label'=>'Manage Alumnes', 'url'=>array('admin')),
);
?>

<h1>Alumnes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
