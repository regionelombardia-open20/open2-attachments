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

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\FileModuleTrait;
use open20\amos\attachments\models\File;

use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class AttachmentsTable
 * @package open20\amos\attachments\components
 */
class AttachmentsTable extends Widget
{
    /**
     * 
     */
    use FileModuleTrait;
    
    /**
     * @var FileActiveRecord
     * @var type
     */
    public $model;
    
    /**
     * 
     * @var type
     */
    public $attribute;
    
    /**
     * 
     * @var type
     */
    public $tableOptions = ['class' => 'table table-striped table-bordered table-condensed'];
    
    /**
     * 
     * @var type
     */
    public $viewDeleteBtn = true;
    /**
     * 
     * @var type boolean
     */
    public $enableUpdateAltDescription = false;
    
    /**
     * 
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * 
     * @return type
     * @throws InvalidConfigException
     */
    public function run()
    {
        if (empty($this->model)) {
            throw new InvalidConfigException(FileModule::t('amosattachments', "Property {model} cannot be blank"));
        }
        
        $hasFileBehavior = false;
        $fileBehaviorClass = FileBehavior::class;
        foreach ($this->model->getBehaviors() as $behavior) {
            if (is_a($behavior, $fileBehaviorClass)) {
                $hasFileBehavior = true;
            }
        }
        
        if (!$hasFileBehavior) {
            throw new InvalidConfigException(FileModule::t('amosattachments', "The behavior {FileBehavior} has not been attached to the model."));
        }
        
        if (!empty($this->attribute)) {
            return $this->drawWidget($this->attribute);
        }
        
        $widgets = null;
        $attributes = $this->model->getFileAttributes();
            
        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                $widgets .= $this->drawWidget($attribute);
            }
        }
            
        return $widgets;
    }

    /**
     * 
     * @param type $attribute
     * @return type
     */    
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
            
        if($this->enableUpdateAltDescription){
            
            $columns[] = [
                'label' => FileModule::t('amosattachments', '#alt_description'),
                'format' => 'raw',
                'value' => function ($model) {
                    
                    return Html::input('text', 'update_alt_desription['.$model->id.']', $model->alt_description, ['class' => 'form-control']);
                }
            ];
            
        }
        
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
                        
                        if (\Yii::$app->user->can($updatePremission, ['model' => $this->model])) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                [
                                    '/' . FileModule::getModuleName() . '/file/delete',
                                    'id' => $model->id,
                                    'item_id' => $this->model->id,
                                    'model' => $this->getModule()->getClass($this->model),
                                    'attribute' => $this->attribute
                                ],
                                [
                                    'data-toggle'=>'tooltip',
                                    'title' => FileModule::t('amosattachments', 'Delete'),
                                    'data-confirm' => FileModule::t('amosattachments', 'Are you sure you want to delete this item?'),
                                    'data-method' => 'post'
                                ]
                            );
                        }
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
