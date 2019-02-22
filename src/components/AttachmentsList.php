<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments
 * @category   CategoryName
 */

namespace lispa\amos\attachments\components;

use lispa\amos\attachments\behaviors\FileBehavior;
use lispa\amos\attachments\FileModule;
use lispa\amos\attachments\FileModuleTrait;
use himiklab\colorbox\Colorbox;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class AttachmentsList
 * @package lispa\amos\attachments\components
 */
class AttachmentsList extends AttachmentsTableWithPreview
{

    var $viewDownloadBtn = false;
    var $viewFilesCounter = false;

    // EDITED
    public function drawWidget($attribute = null)
    {
        if (!$attribute) {
            return null;
        }

        $files = $this->model->hasMultipleFiles($attribute);

        $dataProvider = new ActiveDataProvider(['query' => $files]);

        $files = [];

        $filesQuantity = count($dataProvider->getModels());

        foreach ($dataProvider->getModels() as $model) {

            $file = [];

            // Get rendered filename and download link
            $file['filename'] = Html::a("$model->name.$model->type", $model->getUrl(), [
                'class' => ' ',
                'title' => FileModule::t('amosattachments', '#attach_list_download_title')
            ]);

            // Get rendered preview button

            $renderedPreviewHtml = "";

            if (in_array(strtolower($model->type), ['jpg', 'jpeg', 'png', 'gif'])) {

                $renderedPreviewHtml = Html::a(
                    Html::tag('span', '', ['class' => 'btn btn-icon am am-search']),
                    $model->getUrl(),
                    [
                        'class' => 'att' . $model->itemId . ' cboxElement',
                        'title' => FileModule::t('amosattachments', '#attach_list_preview_icon_title')
                    ]
                );

            }

            $file['preview'] = $renderedPreviewHtml;

            // Get rendered delete button if set visible
            if ($this->viewDeleteBtn) {

                // The base class name
                $baseClassName = \yii\helpers\StringHelper::basename($this->model->className());

                // Update permission name
                $updatePremission = strtoupper($baseClassName . '_UPDATE');

                $btn = '';
                if (\Yii::$app->user->can($updatePremission, ['model' => $this->model])) {
                    $btn = Html::a(
                        Html::tag('span', '', ['class' => 'btn btn-icon am am-close']),
                        [
                            '/' . FileModule::getModuleName() . '/file/delete',
                            'id' => $model->id,
                            'item_id' => $this->model->id,
                            'model' => $this->getModule()->getClass($this->model),
                            'attribute' => $this->attribute
                        ],
                        [
                            'title' => FileModule::t('amosattachments', '#attach_list_delete_title'),
                            'data-confirm' => FileModule::t('amosattachments', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                        ]
                    );
                }
                $file['deleteButton'] = $btn;

            } else {

                $file['deleteButton'] = null;

            }

            // Get rendered download button if set visible
            if ($this->viewDownloadBtn) {

                $btn = Html::a(
                    Html::tag('span', '', ['class' => 'btn btn-icon am am-download']),
                    $model->getUrl(),
                    [
                        'title' => FileModule::t('amosattachments', '#attach_list_download_title')
                    ]
                );

                $file['downloadButton'] = $btn;

            } else {

                $file['downloadButton'] = null;

            }

            $file['id'] = $this->model->id;

            $files[] = $file;

        }

        return $this->render('attachments-list', [
            'filesList' => $files,
            'viewFilesCounter' => $this->viewFilesCounter,
            'filesQuantity' => $filesQuantity
        ]);

    }

}
