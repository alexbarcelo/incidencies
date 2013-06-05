<?php
/* @var $this AmonestacionsController */
/* @var $model Amonestacions */

$this->breadcrumbs=array(
	'Amonestacions'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Amonestacions', 'url'=>array('index')),
	array('label'=>'Create Amonestacions', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#amonestacions-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Amonestacions</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'amonestacions-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'tipus',
		'alumne',
		'profe',
		'ennomde',
		'dataRegistre',
		/*
		'horaLectiva',
		'situacio',
		'descripcio',
		'assignadaEscrita',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
