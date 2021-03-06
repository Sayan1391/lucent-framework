<?php
use core\classes\SysLocale as locale;
?>

%system_title%

%system_breadcrumbs%

%system_notifications%

<form action="" method="post">
	<div class="m-row">
		<label for="name"><?= $model->getLabel('name'); ?>*</label>
		<input type="text" value="<?= $model->name ?>" name="name" class="form-control title"
			   placeholder="<?= locale::t("Name"); ?>"/>
		<br/>
	</div>

	<br>

	<div class="m-row">
		<label for="description"><?= $model->getLabel('description'); ?></label>
		<br/>
            <textarea name="description" id="description" class="form-control"
					  placeholder="<?= locale::t("Description"); ?>"
					  rows="8"><?= ($model->description != '') ? $model->description : '' ?></textarea>
	</div>

	<br>

	<div class="m-row">
		<label for="region_id"><?= $model->getLabel('region_id'); ?>*</label>
		<br/>
		<select id="region_id" name="region_id" id="region" class="form-control">
			<?php if (!empty($regions)): ?>
				<?php foreach($regions as $region): ?>
					<option <?= ($region->id == $model->region_id) ? 'selected' : '' ?>
						value="<?= $region->id; ?>"><?= $region->name; ?></option>
				<?php endforeach; ?>
			<?php else: ?>
				<option value="-1"><?= locale::t("Not found"); ?></option>
			<?php endif; ?>
		</select>
	</div>

	<br/>

	<div class="m-row">
		<label for="page_type_id"><?= $model->getLabel('page_type_id'); ?>*</label>
		<select id="page_type_id" class="form-control" name="page_type_id" id="page_type_id">
			<?php if (!empty($pageTypes)): ?>
				<?php foreach ($pageTypes as $type): ?>
					<option <?= ($type->id == $model->page_type_id) ? 'selected' : '' ?>
						value="<?= $type->id ?>"><?= $type->title; ?></option>
				<?php endforeach; ?>
			<?php else: ?>
				<option value="-1"><?= locale::t("Not found"); ?></option>
			<?php endif; ?>
		</select>
	</div>

	<br>

	<div class="m-row">
		<label for="links"><?= $model->getLabel('links'); ?></label>
		<input type="text" value="<?= ($model->links != '') ? $model->links : '' ?>" class="form-control" name="links"
			   id="links">
	</div>

	<br>

	<div class="m-row">
		<label for="pagination"><?= $model->getLabel('pagination'); ?></label>
		<input type="number" value="<?= ($model->pagination != '') ? $model->pagination : '' ?>" class="form-control"
			   name="pagination" id="pagination">
	</div>

	<br>

	<div class="m-row">
		<label for="weight"><?= $model->getLabel('weight'); ?></label>
		<input type="number" value="<?= ($model->weight != '') ? $model->weight : '' ?>" class="form-control"
			   name="weight" id="weight">
	</div>

	<br>

	<div class="m-row">
		<input type="hidden" name="id" value="<?= $model->id; ?>">
		<input type="button" onclick="SysAjaxSave('/page/collection/ajaxupdate', '/page/collection/')"
			   value="<?= locale::t("Save"); ?>" class="btn btn-primary"/>
	</div>
</form>