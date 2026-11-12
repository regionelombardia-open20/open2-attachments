<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments\components
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

namespace open20\amos\attachments\components;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\File;
use open20\amos\core\helpers\StringHelper;
use open20\amos\core\utilities\SortModelsUtility;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class AttachmentsList
 * @package open20\amos\attachments\components
 */
class AttachmentsList extends AttachmentsTableWithPreview
{
    public $hideWidgetIfEmpty = false;

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

    public $hideExtensionFile = false;

    public $requireModalMoveFile = true;

    public $label;

    public $useOnlyOneColumn = false;
    
    public $enableUpdateAltDescription = false;

    public $setAltDescriprionAsName = false;
    
    /**
     * 
     * @var bool $publicFiles
     */
    public $publicFiles = false;

    public $viewPreviewBtn = true;
    public $activateAttrDownloadOnAnchor = false;

    public function __construct(array $config = []) {
        $this->label = FileModule::t('amosattachments', '#attach');
        
        parent::__construct($config);
    }

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
        // $dataProviderModels = $dataProvider->getModels();
        $dataProviderModels = $dataProvider->query->all();
        $counter = 1;
        $countModels = count($dataProviderModels);
        
        /** FileModule $module */
        $module = $this->getModule();

        $categorizationEnabled = ((!empty($module->categorizationEnabled))
            && (!empty($module->categorizationModelsEnabled))
            && (in_array($this->model->className(),
                $module->categorizationModelsEnabled)));
        
        //I retrieve the translation of "categoria"
        $catTxtI18n = FileModule::t('amosattachments', 'Categoria');

        /** @var File $model */
        foreach ($dataProviderModels as $model) {
            /** @var File $model */

            $file = [];

            $filnameLength = 30;
            $stingExtension = ($this->hideExtensionFile ? '' : "." . $model->type);
            if (strlen($model->name) > $filnameLength) {
                $stingExtension = '';
            }
            
            /**
             * check if attachment categorization is enabled and if so, retrieve
             * the category name (if exists)
             */
            $file['categoryEnabled'] = $categorizationEnabled;
            $file['categoryId'] = null;
            $file['categoryNameFull'] = FileModule::t('amosattachments',
                'Nessuna categoria');
            $categoryHtml = '';
            if ($categorizationEnabled) {
                $file['categoryEnabled'] = true;
                
                //I retrieve the category data (if it exists)
                $modelCat = $model->categoria;
                if (!empty($modelCat)) {
                    /** @var \open20\amos\attachments\models\AttachFileCategorie $modelCat */
                    $file['categoryId'] = $modelCat->id;
                    //I get the name of the category
                    $file['categoryNameFull'] = $modelCat->titolo;
                    if (!empty($file['categoryNameFull'])) {
                        $file['categoryName'] = StringHelper::truncate(
                            $file['categoryNameFull'],  $this->categoryNameMaxLength);
                        
                        //I check if the category is valid for the model
                        $warningCatHtml = '';
                        if (!$modelCat->isValidForModel($model->model)) {
                            $warningCatHtml = ' ' . html::tag('span', '', [
                                'class' => 'warning-cat text-danger mdi'
                                    . ' mdi-alert-circle mdi-18px',
                                'title' => FileModule::t('amosattachments',
                                    '#warning_category_list', [
                                        'nome_categoria' => $file['categoryNameFull'],
                                        'nome_classe' => $model->model
                                    ])
                            ]);
                        }
                        
                        /**
                         * I generate the HTML relating to the category to be
                         * displayed in the list
                         */
                        $categoryHtml = html::tag('div',
                            html::tag('span', $catTxtI18n . ': '
                                . $file['categoryName'], ['title' => $catTxtI18n
                                . ': ' . $file['categoryNameFull']]) . 
                            $warningCatHtml,
                        ['class' => 'small w-100 text-muted']);
                    }
                }
                
            }

            // Get rendered filename and download link
            $file_name = '<span>' . StringHelper::truncate($model->name, $filnameLength) . '</span>' . $stingExtension;
            $file_name_long = $model->name . ($this->hideExtensionFile ? '' : "." . $model->type);
            
            $file['filename'] = Html::tag('div', Html::a($file_name, ($this->publicFiles ? $model->getWebUrl() : $model->getUrl()), [
                    'class' => 'filename',
                    'download' => $this->activateAttrDownloadOnAnchor? '': null,
                    'title' => "Dowload file ". $file_name_long,
                ])
                . html::tag('span', strtoupper($model->type) . " (". $model->formattedSize . ")",
                    ['class' => 'small'])
                . $categoryHtml,
            ['class'=> 'fileinfos']);
            $file['type'] = $model->type;
            $file['filename_cat'] = str_replace($categoryHtml, '', $file['filename']);

            // Get rendered preview button
            if($this->viewPreviewBtn) {
                $renderedPreviewHtml = "";

                if (in_array(strtolower($model->type), ['jpg', 'jpeg', 'png', 'gif'])) {

                    $renderedPreviewHtml = Html::a(
                        Html::tag('span', '', ['class' => 'am am-search']),
                        ($this->publicFiles ? $model->getWebUrl() : $model->getUrl()),
                        [
                            'data-toggle' => 'tooltip',
                            'class' => 'att' . $model->item_id . ' cboxElement',
                            'title' => 'Clicca qui per visualizzare l\'anteprima del file ' . $file_name_long,
                            //'aria-describedby' => 'preview-file',
                        ]
                    );
                    //Html::tag('p', 'Clicca qui per visualizzare l\'anteprima del file ' . $file_name_long,['class' => 'sr-only']);
                }

                $file['preview'] = $renderedPreviewHtml;
            } else {
                $file['preview'] = null;
            }


            // Get rendered delete button if set visible
            if (($this->viewDeleteBtn)
                || ($this->enableSort && $this->viewSortBtns)
                || ($this->viewUpdateBtn)) {

                // The base class name
                $baseClassName = StringHelper::basename($this->model->className());

                // Update permission name
                $updatePremission = strtoupper($baseClassName . '_UPDATE');

                $userHasUpdatePermission = Yii::$app->user->can($updatePremission, ['model' => $this->model]);

                if ($this->viewDeleteBtn) {
                    $btn = '';
                    if ($userHasUpdatePermission) {
                        $btn = Html::a(
                            Html::tag('span', '', ['class' => 'btn btn-icon am am-close']),
                            '#',
                            [
                                'data-toggle'=>'tooltip',
                                'class' => 'attachments-list-delete',
                                'title' => FileModule::t('amosattachments', '#attach_list_delete_title'),
                                'data-id' => $model->id,
                                'data-item_id' => $this->model->id,
                                'data-model' => $module->getClass($this->model),
                                'data-attribute' => $this->attribute
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
                            $link_options = [
                                    'title' => FileModule::t('amosattachments', '#attach_list_move_up_title'),
                                    'data-method' => 'post',
                                ];

                            if($this->requireModalMoveFile)
                            {
                                $link_options['data-confirm'] = FileModule::t('amosattachments', '#attach_list_move_up_data_confirm');
                            }
                            $btn .= Html::a(
                                Html::tag('span', '', ['class' => 'btn btn-icon am am-long-arrow-up']),
                                [
                                    $urlSort,
                                    'id' => $model->id,
                                    'direction' => SortModelsUtility::DIRECTION_UP
                                ],
                                $link_options
                            );
                        }
                        if ($counter < $countModels) {
                            $link_options = [
                                    'title' => FileModule::t('amosattachments', '#attach_list_move_down_title'),
                                    'data-method' => 'post',
                                ];
                            if($this->requireModalMoveFile)
                            {
                                $link_options['data-confirm'] = FileModule::t('amosattachments', '#attach_list_move_down_data_confirm');
                            }
                            $btn .= Html::a(
                                Html::tag('span', '', ['class' => 'btn btn-icon am am-long-arrow-down']),
                                [
                                    $urlSort,
                                    'id' => $model->id,
                                    'direction' => SortModelsUtility::DIRECTION_DOWN
                                ],
                                $link_options
                            );
                        }
                    }
                    $file['sortButtons'] = $btn;
                } else {
                    $file['sortButtons'] = null;
                }

                if ($this->viewUpdateBtn) {
                    $btn = '';
                    if (($file['categoryEnabled']) && ($userHasUpdatePermission)) {
                        $urlUpdate = '/' . FileModule::getModuleName()
                            . '/file/update';
                        $btn = Html::a(
                            Html::tag('span', '', ['class' => 'btn btn-icon am am-edit']),
                            [$urlUpdate, 'id' => $model->id],
                            [
//                                'class' => 'attachments-list-update',
                                'title' => FileModule::t('amosattachments', '#attach_list_update_title'),
//                                'data-id' => $model->id,
//                                'data-item_id' => $this->model->id,
//                                'data-model' => $module->getClass($this->model),
//                                'data-attribute' => $this->attribute
                            ]
                        );
                    }
                    $file['updateButton'] = $btn;
                } else {
                    $file['updateButton'] = null;
                }
            }

            // Get rendered download button if set visible
            if ($this->viewDownloadBtn) {

                $btn = Html::a(
                    Html::tag('span', '', ['class' => 'am am-download']),
                    ($this->publicFiles ? $model->getWebUrl() : $model->getUrl()),
                    [
                        'title' => FileModule::t('amosattachments', '#attach_list_download_title'),
                        'class' => 'btn btn-icon'
                    ]
                );

                $file['downloadButton'] = $btn;

            } else {

                $file['downloadButton'] = null;

            }

            $counter++;
            $file['id'] = $this->model->id;
            $file['file_id'] = $model->id;
            
            if($this->enableUpdateAltDescription){
                $file['alt_description'] = $model->alt_description;
            }

            if ($this->setAltDescriprionAsName) {
                $file['alt_description'] = $model->alt_description;

                if (!is_null($file['alt_description']) && $file['alt_description'] != ''){
                    $model->name = $model->alt_description;
                    $model->save();
                }
            }
            
            $files[] = $file;
            $filesQuantity++;
        }

        if($this->hideWidgetIfEmpty && $filesQuantity == 0) {
            return '';
        } else {
            return $this->render('attachments-list', [
                'label' => $this->label,
                'filesList' => $files,
                'viewFilesCounter' => $this->viewFilesCounter,
                'filesQuantity' => $filesQuantity,
                'useOnlyOneColumn' => $this->useOnlyOneColumn,
                'categorizationEnabled' => $categorizationEnabled,
                'idRand' => md5($attribute . mt_rand()),
                'enableUpdateAltDescription' => $this->enableUpdateAltDescription,
                'setAltDescriprionAsName' => $this->setAltDescriprionAsName
            ]);
        }
    }
}