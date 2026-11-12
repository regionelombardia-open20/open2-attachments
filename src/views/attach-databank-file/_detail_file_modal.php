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

/** @var \open20\amos\attachments\models\File $model */

use open20\amos\attachments\FileModule;
use yii\helpers\Html;

$createdAt = $model->getCreatedAt();
$tagsCustomImage = $model->getCustomTagsModel();

$modulo = FileModule::instance();
?>

<div class="content-modal-databank-file">
    <div class="row">
        <div class="col-lg-2 col-md-4 m-b-20">
            <?= $this->render('_icon', ['model' => $model, 'hideView' => true]) ?>
            <?= Html::a(
                FileModule::t('amosattachment', 'Scarica'),
               $model->getWebUrl(),
                [
                    'title' => FileModule::t('amosattachment', 'Scarica il file'),
                    'class' => 'btn btn-primary-outline m-t-20',
                    'target' => 'blank'
                ]
            );?>
            <?= Html::a(
                FileModule::t('amosattachments', 'Copia url'),
                \Yii::$app->params['platform']['frontendUrl'].$model->getWebUrl(),
                [
                    'title' => FileModule::t('amosattachment', 'Copia url'),
                    'class' => 'btn btn-primary-outline m-t-20 copy-and-paste',
                    'target' => 'blank'
                ]
            );?>
        </div>
        <div class="col-lg-10 col-md-8">
            <div class="detail-attachment-modal">
                <p>
                    <strong><?= FileModule::t('amosattachments', "Caricato il") . ': ' ?></strong>
                    <?= $createdAt->format('d/m/Y') . ' alle ' . $createdAt->format('H:i') ?>
                    <?= FileModule::t('amosattachments', "da") ?> <?= $model->getCreatedByProfile() ?>
                </p>
                <p>
                    <strong><?= FileModule::t('amosattachments', "Nome file") . ': ' ?></strong><?= $model->name ?>
                </p>

                <p>
                    <strong>
                        <?= FileModule::t('amosattachments', "Estensione") . ': ' ?>
                    </strong>
                    <?= $model->type ?>
                </p>

                <p>
                    <strong>
                        <?= FileModule::t('amosattachments', "Dimensione") . ': ' ?>
                    </strong>
                    <?= $model->formattedSize ?>
                </p>

                <p>
                    <strong>
                        <?= FileModule::t('amosattachments', "Url file") . ': ' ?>
                    </strong>
                    <?= \Yii::$app->params['platform']['frontendUrl'].$model->getWebUrl() ?>
                </p>
                <?php if (!empty($tagsCustomImage)) { ?>
                <br>
                <p>
                    <strong>
                        <?= FileModule::t('amosattachments', "Tag liberi") . ': ' ?>
                    </strong>
                    <div class="content-tag">
                        <?= \open20\amos\attachments\utility\AttachmentsUtility::formatTags($tagsCustomImage) ?>
                    </div>
                </p>
                <?php } ?>
                <?php if ((!empty($modulo->categorizationEnabled))
                    || (!empty($modulo->categorizationModelsEnabled))): ?>
                <p>
                    <strong>
                        <?= FileModule::t('amosattachments', "Categoria") . ': ' ?>
                    </strong>
                    <?= $model->getNomeCategoria() ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>