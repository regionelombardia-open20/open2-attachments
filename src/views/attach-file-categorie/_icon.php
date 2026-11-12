<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */
?>

<?php 
    
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/attachments/views 
 */
     
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use backend\modules\admin\Module as AdminModule; 
    
/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */
 ?>

<div class="card-container">
    <div class="icon-header grow-pict">
        <a href="#"><img class="img-responsive img-round"src="<?= $model->getAvatarUrl('medium') ?>"  alt="<?= $model ?>"></a>
    </div>
    <div class="icon-body">
        <div class="container-title"><h3><a href="#"><?= $model ?></a></h3></div>
        <?php if ($model->getTestoIcon()): ?>
            <p><?= $model->getTestoIcon() ?></p>
        <?php endif; ?>
        <?php if ($model->getTelefonoDefault()): ?>
            <p>
                <?= AmosIcons::show('phone'); ?>
                <span title="<?= $model->getTelefonoDefault() ?>"><?= $model->getTelefonoDefault() ?></span>
            </p>
            <?php
        endif;
        if ($model->getEmailDefault()):
            ?>                                           
            <?= AmosIcons::show('email', ['class' => 'ellipsis-text', 'title' => $model->getEmailDefault()], \Yii::$app->params['icon-framework'], true, 'p', $model->getEmailDefault()); ?>                            
        <?php endif; ?>
    </div>
    <div class="icon-footer">
        <div class="foot-bar">
            <?php if($model->getContattiComune()): ?>
            <div class="col-sm-7 col-xs-12 ">
                <?= $model->getContattiComune() ?>
            </div>
            <div class="col-sm-5 col-xs-12 container-action">
                <?= $buttons ?>
            </div>
            <?php else: ?>
            <div class="col-xs-12 container-action">
                <?= $buttons ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="foot-button">
            <button class="btn btn-navigation-primary">
                <?= AmosIcons::show('link'); ?>
                <span class="hidden-xs">LINK</span>
            </button>
        </div>
    </div>

</div>