<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\components
 * @category   CategoryName
 */

namespace open20\amos\attachments\components;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\File;
use open20\amos\core\utilities\SortModelsUtility;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/**
 * Class AttachmentsList
 * @package open20\amos\attachments\components
 */
class AttachmentsList extends AttachmentsTableWithPreview
{
    var $viewDownloadBtn = false;
    var $viewFilesCounter = false;

    /**
     * @var bool $enableSort If true enable attachments sorting by the "sort" field.
     */
    public $enableSort = false;

    /**
     * @var bool $viewSortBtns If true enable the buttons to order the attachments in a multi attachments input.
     */
    public $viewSortBtns = true;

    // EDITED
    public function drawWidget($attribute = null)
    {
        if (!$attribute) {
            return null;
        }

        if ($this->enableSort) {
            $fileQuery = $this->model->hasMultipleFiles($attribute, 'sort');
        } else {
            $fileQuery = $this->model->hasMultipleFiles($attribute);
        }

        $dataProvider = new ActiveDataProvider(['query' => $fileQuery]);

        $files = [];
        $filesQuantity = 0;
        $dataProviderModels = $dataProvider->getModels();
        $counter = 1;
        $countModels = count($dataProviderModels);

        foreach ($dataProviderModels as $model) {
            /** @var File $model */

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
            if ($this->viewDeleteBtn || ($this->enableSort && $this->viewSortBtns)) {

                // The base class name
                $baseClassName = \yii\helpers\StringHelper::basename($this->model->className());

                // Update permission name
                $updatePremission = strtoupper($baseClassName . '_UPDATE');

                $userHasUpdatePermission = \Yii::$app->user->can($updatePremission, ['model' => $this->model]);

                if ($this->viewDeleteBtn) {
                    $btn = '';
                    if ($userHasUpdatePermission) {
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

                if ($this->enableSort && $this->viewSortBtns) {
                    $urlSort = '/' . FileModule::getModuleName() . '/file/order-attachment';
                    $btn = '';
                    if ($userHasUpdatePermission) {
                        if ($counter > 1) {
                            $btn .= Html::a(
                                Html::tag('span', '', ['class' => 'btn btn-icon am am-long-arrow-up']),
                                [
                                    $urlSort,
                                    'id' => $model->id,
                                    'direction' => SortModelsUtility::DIRECTION_UP
                                ],
                                [
                                    'title' => FileModule::t('amosattachments', '#attach_list_move_up_title'),
                                    'data-confirm' => FileModule::t('amosattachments', '#attach_list_move_up_data_confirm'),
                                    'data-method' => 'post',
                                ]
                            );
                        }
                        if ($counter < $countModels) {
                            $btn .= Html::a(
                                Html::tag('span', '', ['class' => 'btn btn-icon am am-long-arrow-down']),
                                [
                                    $urlSort,
                                    'id' => $model->id,
                                    'direction' => SortModelsUtility::DIRECTION_DOWN
                                ],
                                [
                                    'title' => FileModule::t('amosattachments', '#attach_list_move_down_title'),
                                    'data-confirm' => FileModule::t('amosattachments', '#attach_list_move_down_data_confirm'),
                                    'data-method' => 'post',
                                ]
                            );
                        }
                    }
                    $file['sortButtons'] = $btn;
                } else {
                    $file['sortButtons'] = null;
                }
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

            $counter++;
            $file['id'] = $this->model->id;
            $files[] = $file;
            $filesQuantity++;
        }

        return $this->render('attachments-list', [
            'filesList' => $files,
            'viewFilesCounter' => $this->viewFilesCounter,
            'filesQuantity' => $filesQuantity
        ]);
    }
}
