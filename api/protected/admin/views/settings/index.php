<?php
/* @var $this SettingsController */
/* @var $dataProvider CActiveDataProvider */
/*
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));
 */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('网站设置'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>

<div class="iframaincontent">
<?php 
$this->widget('bootstrap.widgets.TbAlert', array(
		'id'=>'alert',
        'block'=>false,
        'fade'=>true,
        'closeText'=>true,
        'alerts'=>array(
            'success'=>array('fade'=>true, 'closeText'=>'&times;'), 
            'error'=>array('fade'=>true, 'closeText'=>'&times;'), 
        ),
 ));
?>
<form class="well form-horizontal" id="procates-form" action="" method="post">
<div class="alert alert-info">全局设置</div>
<div class="control-group ">
	<label class="control-label" for="Procates_ishidden">站点开关：</label>
	<div class="controls">
		<label class="radio inline">
			<input id="site_off_0" value="0" <?php if(Yii::app()->config->get('site_off') =='0') echo 'checked="checked"'; ?> type="radio" name="site_off">
			<label for="site_off_0">开启</label>
		</label>
		<label class="radio inline">
			<input id="site_off_1" value="1" <?php if(Yii::app()->config->get('site_off') =='1') echo 'checked="checked"'; ?> type="radio" name="site_off">
			<label for="site_off_1">关闭</label>
		</label>
	</div>
</div>
<div class="control-group ">
	<label class="control-label" for="Products_name">网站名称：</label>
	<div class="controls">
		<input name="sitename" id="sitename" type="text" maxlength="100" value="<?php echo Yii::app()->config->get('sitename'); ?>">
	</div>
</div>
<div class="control-group ">
	<label class="control-label" for="Products_picids">首页标题：</label>
	<div class="controls">
		<input class="input-xxlarge" name="title" id="title" type="text" maxlength="100" value="<?php echo Yii::app()->config->get('title'); ?>">
		<p class="help-inline help-block"></p>
	</div>
</div>
<div class="control-group ">
	<label class="control-label" for="Products_name">SEO关键词：</label>
	<div class="controls">
		<input class="input-xxlarge" name="keywords" id="keywords" type="text" maxlength="100" value="<?php echo Yii::app()->config->get('keywords'); ?>">
		<p class="help-inline help-block">用半角英文逗号分隔</p>
	</div>
</div>
<div class="control-group ">
	<label class="control-label" for="Products_name">SEO描述：</label>
	<div class="controls">
		<textarea class="input-xxlarge" rows="3" name="description"><?php echo Yii::app()->config->get('description'); ?></textarea>
	</div>
</div>

<div class="control-group ">
	<label class="control-label" for="Products_name">ICP备案号：</label>
	<div class="controls">
		<input class="input-xxlarge" name="icp_beian" id="icp_beian" type="text" maxlength="100" value="<?php echo Yii::app()->config->get('icp_beian'); ?>">
	</div>
</div>

<div class="control-group ">
	<label class="control-label" for="Products_name">网管邮箱：</label>
	<div class="controls">
		<input class="input-xxlarge" name="webmaster_email" id="webmaster_email" type="text" maxlength="100" value="<?php echo Yii::app()->config->get('webmaster_email'); ?>">
	</div>
</div>

<div class="control-group ">
	<label class="control-label" for="Products_name">统计代码：</label>
	<div class="controls">
		<textarea class="input-xxlarge" rows="5" name="stats_code"><?php echo Yii::app()->config->get('stats_code'); ?></textarea>
	</div>
</div>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'保存')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Reset')); ?>
</div>
</form>
</div>