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

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\attachments\FileModule;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\attachments\models\search\AttachDatabankFileSearch $model
 */

$this->title = Yii::t('amoscore', 'Allegati');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/attachments']];
$this->params['breadcrumbs'][] = $this->title;


$js = <<<JS
    $('.open-modal-detail-btn').on('click', function(e){
         e.preventDefault();
         var file_id = $(this).attr('data-key');
         $.ajax({
          method: 'get',
          url: "/attachments/attach-databank-file/load-detail-index",
          data: {
              id_file: file_id,
          },
          success: function(data){
                $('#detail-file-modal-content').html(data);
                $('#detail-file-model').modal('show');
                }
        }); 
    });

    $(document).on('click', '.copy-and-paste', function(e){
        e.preventDefault();
        var link = $(this).attr('href');
        copyToClipboard(link);
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);

  // Alert the copied text
  // alert("link del file copiato: " + text);
    }
JS;
$this->registerJs($js);

$dataProvider->pagination->pageSize = 18;
$models = $dataProvider->models;

//\open20\amos\attachments\utility\AttachmentsUtility::filterFilesDoesntExit($dataProvider);

?>

<div class="attach-databank-file">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                [
                    'format' => 'raw',
                    'label' => FileModule::t('amosattachments', 'Estensione'),
                    'value' => function ($model) {
                        return $model->getAttachmentIcon();
                    }
                ],
                'name',
                [
                    'label' => FileModule::t('amosattachments', 'Dimensione'),
                    'value' => function ($model) {
                        return $model->formattedSize;
                    }
                ],
                //                'extension',
//                [
//                    'format' => 'raw',
//                    'label' => FileModule::t('amosattachments', 'Estensione'),
//                    'value' => function ($model) {
//                        $file = $model->attachmentFile;
//                        /** @var $file \open20\amos\attachments\models\File */
//                        if ($file) {
//                            //                            return $file->type;
//                            return $file->getAttachmentIcon();
//                        }
//                        return '';
//                    }
//                ],
//                'name',
//                [
//                    'label' => FileModule::t('amosattachments', 'Dimensione'),
//                    'value' => function ($model) {
//                        $file = $model->attachmentFile;
//                        /** @var $file \open20\amos\attachments\models\File */
//                        if ($file) {
//                            return $file->formattedSize;
//                        }
//                        return '';
//                    }
//                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
        'iconView' => [
            'itemView' => '_icon'
        ],
    ]); ?>

</div>

<div id="detail-file-model" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h6 class="modal-title"><?= FileModule::t('amosattachments', "Dettagli allegato") ?></h6>

            </div>
            <div class="modal-body">
                <div id="detail-file-modal-content"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-dismiss="modal"><?= FileModule::t('amosattachments', "Chiudi") ?></button>
            </div>
        </div>
    </div>
</div>