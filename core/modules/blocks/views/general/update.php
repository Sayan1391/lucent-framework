<?php
use core\classes\SysWidget;
?>

%system_title%

%system_breadcrumbs%

%system_notifications%

<div class="form-group">
    <form action="" method="post">

        <div class="m-row">
            <label for="title"><?= $model->getLabel('name'); ?></label>
            <input type="text" name="name" value="<?= $model->name; ?>"
                   class="form-control name" placeholder="Введите имя"/>
            <br/>
        </div>

        <div class="m-row">
            <label for="machine_name"><?= $model->getLabel('machine_name'); ?></label>
            <input type="text" name="machine_name" value="<?= $model->machine_name;; ?>"
                   class="form-control machine_name" placeholder="<?php echo _("Machine name"); ?>"/>
            <small><?php echo _("Machine name of block have to contain: a-z and/or _"); ?></small>
            <br/>
            <br/>
        </div>

        <div class="m-row">
            <label for="content"><?php echo $model->getLabel('content'); ?></label>
            <br/>
            <textarea name="content" id="content" class="form-control" placeholder="<?php echo _("content"); ?>"
                      rows="8"><?php echo $model->content; ?></textarea>
        </div>
        <br/>

        <hr/>

        <?php if (!empty($regions)): ?>
            <div class="m-row">
                <label for="region_id"><?php echo $model->getLabel('region_id'); ?></label>
                <br/>
                <select name="region_id" id="region" class="form-control">
                    <option value="none"><?php echo _("not selected"); ?></option>
                    <?php foreach($regions as $region): ?>
                        <option value="<?php echo $region->id; ?>" <?php echo ($region->id == $regionSelected) ?
                            'selected' : '' ?>><?php echo $region->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br/>
        <?php endif; ?>

        <div class="m-row">
            <label for="weight"><?php echo $model->getLabel('weight'); ?></label>
            <br/>
            <input type="number" class="form-control" name="weight" placeholder="<?php echo _("Weight"); ?>" min="0" max="99"
                   maxlength="99" value="<?php echo $model->weight; ?>" />
        </div>
        <br/>

        <div class="m-row">
            <input type="hidden" name="id" value="<?php echo $model->id; ?>"/>
            <input type="button" onclick="SysAjaxSave('/blocks/general/ajaxupdate','/blocks/general/')" value="<?php echo _("Save"); ?>" class="btn btn-primary"/>
            <?php
            echo SysWidget::build('WBtnAsk', $model, [
                'link' => '/blocks/general/delete?id=' . $model->id,
                'ok_class' => 'btn btn-danger',
            ]);
            ?>
        </div>

    </form>
</div>