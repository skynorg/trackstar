<?php
/* @var $this IssueController */
/* @var $data Issue */
?>

<div class="view">
    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</
    b>
    <?php echo CHtml::link(CHtml::encode($data->name), array('issue/view', 'id'=>$data->id)); ?>
    <br />
    <b><?php echo CHtml::encode($data->getAttributeLabel('type_id'));
        ?>:</b>
    <?php echo CHtml::encode($data->typeText); ?>
    <br />
    <b><?php echo CHtml::encode($data->getAttributeLabel('status_id'));
        ?>:</b>
    <?php echo CHtml::encode($data->statusText); ?>
</div>