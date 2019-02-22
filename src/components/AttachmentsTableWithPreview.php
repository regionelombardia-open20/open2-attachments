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
 * Class AttachmentsTableWithPreview
 * @package lispa\amos\attachments\components
 */
class AttachmentsTableWithPreview extends Widget
{
    use FileModuleTrait;
    
    /**
     * @var ActiveRecord
     */
    public $model;
    
    public $attribute;
    
    public $tableOptions = ['class' => 'table table-striped table-bordered table-condensed'];
    
    public $viewDeleteBtn = true;
    
    public function run()
    {
        if (!$this->model) {
            return Html::tag('div',
                Html::tag('b', FileModule::t('amosattachments', 'Error')) . ': ' . FileModule::t('amosattachments', 'The model cannot be empty.'),
                [
                    'class' => 'alert alert-danger'
                ]
            );
        }
        
        $hasFileBehavior = false;
        foreach ($this->model->getBehaviors() as $behavior) {
            if ($behavior instanceof FileBehavior) {
                $hasFileBehavior = true;
                break;
            }
        }
        if (!$hasFileBehavior) {
            return Html::tag('div',
                Html::tag('b', FileModule::t('amosattachments', 'Error')) . ': ' . FileModule::t('amosattachments', 'The behavior FileBehavior has not been attached to the model.'),
                [
                    'class' => 'alert alert-danger'
                ]
            );
        }
        
        Url::remember(Url::current());
        
        if (!empty($this->attribute)) {
            return $this->drawWidget($this->attribute);
        } else {
            $widgets = null;
            $attributes = $this->model->getFileAttributes();
            
            if (!empty($attributes)) {
                foreach ($attributes as $attribute) {
                    $widgets .= $this->drawWidget($attribute);
                }
            }
            
            return $widgets;
        }
    }
    
    public function drawWidget($attribute = null)
    {
        if (!$attribute) {
            return null;
        }
        
        $files = $this->model->hasMultipleFiles($attribute);
        $columns = [
            [
                'class' => 'yii\grid\SerialColumn'
            ],
            [
                'label' => FileModule::t('amosattachments', 'File Preview'),
                'format' => 'raw',
                'value' => function ($model) {
                    if (in_array($model->type, ['jpg', 'jpeg', 'png', 'gif'])) {
                        return
                            Html::beginTag('div', ['class' => 'file-preview-other']) .
                            Html::beginTag('a', ['href' => $model->getUrl(), 'class' => ' group' . $model->itemId . ' cboxElement',]) .
                            Html::img($model->getUrl('square_small'), [
                                'onclick' => 'return false;',
                            ]) .
                            Html::endTag('a');
                        Html::endTag('div');
                        
                    } else {
                        return
                            Html::beginTag('div', ['class' => 'file-preview-other']) .
                            Html::beginTag('span', ['class' => 'file-other-icon']) .
                            Html::tag('i', '', ['class' => 'glyphicon glyphicon-file']) .
                            Html::endTag('span') .
                            Html::endTag('div');
                    }
                    
                }
            ],
            [
                'label' => FileModule::t('amosattachments', 'File name'),
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a("$model->name.$model->type", $model->getUrl(), [
                        'class' => ' ',
                        'title' => FileModule::t('amosattachments', 'Click to download')
                    
                    ]);
                }
            ]
        ];
        
        if ($this->viewDeleteBtn) {
            $columns[] = [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        /** @var ActiveRecord $model */
                        
                        // The base class name
                        $baseClassName = \yii\helpers\StringHelper::basename($this->model->className());
                        
                        // Update permission name
                        $updatePremission = strtoupper($baseClassName . '_UPDATE');
                        
                        $btn = '';
                        if (\Yii::$app->user->can($updatePremission, ['model' => $this->model])) {
                            $btn = Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                [
                                    '/' . FileModule::getModuleName() . '/file/delete',
                                    'id' => $model->id,
                                    'item_id' => $this->model->id,
                                    'model' => $this->getModule()->getClass($this->model),
                                    'attribute' => $this->attribute
                                ],
                                [
                                    'title' => FileModule::t('amosattachments', 'Delete'),
                                    'data-confirm' => FileModule::t('amosattachments', 'Are you sure you want to delete this item?'),
                                    'data-method' => 'post'
                                ]
                            );
                        }
                        return $btn;
                    }
                ]
            ];
        }
        
        return GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $files]),
                'layout' => '{items}',
                'tableOptions' => $this->tableOptions,
                'columns' => $columns,
            ]) .
            Colorbox::widget([
                'targets' => [
                    '.group' . $this->model->id => [
                        'rel' => '.group' . $this->model->id,
                        'photo' => true,
                        'scalePhotos' => true,
                        'width' => '100%',
                        'height' => '100%',
                        'maxWidth' => 800,
                        'maxHeight' => 600,
                    ],
                ],
                'coreStyle' => 4,
            ]);
    }
}
