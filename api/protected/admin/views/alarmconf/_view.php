<?php
/* @var $this AlarmconfController */
/* @var $data AlarmConf */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('suggest')); ?>:</b>
	<?php echo CHtml::encode($data->suggest); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('send_email')); ?>:</b>
	<?php echo CHtml::encode($data->send_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('send_msg')); ?>:</b>
	<?php echo CHtml::encode($data->send_msg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_red')); ?>:</b>
	<?php echo CHtml::encode($data->type_red); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('type_orange')); ?>:</b>
	<?php echo CHtml::encode($data->type_orange); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_yellow')); ?>:</b>
	<?php echo CHtml::encode($data->type_yellow); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	*/ ?>

</div>