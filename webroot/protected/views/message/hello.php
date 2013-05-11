<?php
/* @var $this MessageController */

$this->breadcrumbs=array(
	'Message'=>array('/message'),
	'Hello',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
<h1>Hello, World!</h1>
<?php echo $time;
echo CHtml::link('goodbye',array('message/goodbye'));?>
</p>
