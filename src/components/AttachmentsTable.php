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
use lispa\amos\attachments\models\File;
use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class AttachmentsTable
 * @package lispa\amos\attachments\components
 */
class AttachmentsTable extends Widget
{
    use FileModuleTrait;
    
    /** @var FileActiveRecord */
    public $model;
    
    public $attribute;
    
    public $tableOptions = ['class' => 'table table-striped table-bordered table-condensed'];
    
    public $viewDeleteBtn = true;
    
    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
        
        if (empty($this->model)) {
            throw new InvalidConfigException(FileModule::t('amosattachments', "Property {model} cannot be blank"));
        }
        
        $hasFileBehavior = false;
        foreach ($this->model->getBehaviors() as $behavior) {
            if (is_a($behavior, FileBehavior::className())) {
                $hasFileBehavior = true;
            }
        }
        
        if (!$hasFileBehavior) {
            throw new InvalidConfigException(FileModule::t('amosattachments', "The behavior {FileBehavior} has not been attached to the model."));
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
        
        $columns = [
            [
                'label' => FileModule::t('amosattachments', 'File name'),
                'format' => 'raw',
                'value' => function (File $model) {
                    return Html::a("$model->name.$model->type", $model->getUrl());
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
            'dataProvider' => new ActiveDataProvider(['query' => $this->model->hasMultipleFiles($attribute)]),
            'layout' => '{items}',
            'tableOptions' => $this->tableOptions,
            'columns' => $columns
        ]);
    }
}
