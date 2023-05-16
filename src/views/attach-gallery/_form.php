<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views
 */

use open20\amos\attachments\FileModule;
use open20\amos\attachments\assets\ModuleAttachmentsAsset;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\grid\ActionColumn;

use kartik\datecontrol\DateControl;

use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;

ModuleAttachmentsAsset::register($this);

/**
 * @var yii\web\View $this
 * @var open20\amos\attachments\models\AttachGallery $model
 * @var yii\widgets\ActiveForm $form
 */

$js = <<<JS
    $('.open-modal-detail-btn').on('click', function(e){
         e.preventDefault();
         var image_id = $(this).attr('data-key');
         $.ajax({
          method: 'get',
          url: "/attachments/attach-gallery-image/load-detail-index",
          data: {
              id_image: image_id
          },
          success: function(data){
              console.log(data);
                $('#detail-image-modal-content').html(data);
                $('#detail-image-modal').modal('show');
                }
        }); 
    });

    function showImageLoaded(image){
       var image = $(image).parents('.content-item');
       $(image).find('.placeholder-image').remove();
       $(image).find('.open-modal-detail-btn').show();   
    }
    
    //hide placeholder-image after real image is loaded
    $(document).ready(function(){
      $('.content-item .open-modal-detail-btn img').each(function() {
            if( this.complete ) {
               showImageLoaded(this);
           } else {
                $(this).one('load', function(){
                    showImageLoaded(this);
                });
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
    }

JS;
$this->registerJs($js);


?>
<div class="attach-gallery-form col-xs-12 nop ">
    <?php if ($model->isNewRecord && $model->slug != 'general') : ?>
        <?php
        $form = ActiveForm::begin([
            'options' => [
                'id' => 'attach-gallery_' . ((isset($fid)) ? $fid : 0),
                'data-fid' => (isset($fid)) ? $fid : 0,
                'data-field' => ((isset($dataField)) ? $dataField : ''),
                'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
                'class' => ((isset($class)) ? $class : '')
            ]
        ]);
        ?>
        <div class="row">
            <div class="col-xs-12">
                <h2 class="subtitle-form"><?= FileModule::t('amosattachments', "#settings") ?></h2>
                <div class="row">
                    <div class="col-md-12 col xs-12"><!-- name string -->
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'description')->widget(TextEditorWidget::class, [
                            'options' => [
                                'id' => 'description' . $fid,
                            ],
                            'clientOptions' => [
                                'placeholder' => FileModule::t('amosattachments', '#description_field_placeholder'),
                                'lang' => substr(Yii::$app->language, 0, 2)
                            ]
                        ]);
                        ?>

                        <?= RequiredFieldsTipWidget::widget(); ?>

                        <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
                    </div>
                    <div class="col-md-4 col xs-12"></div>
                </div>

            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <?php
                    if (!$model->isNewRecord) {
                        $urlPlaceholder = '/img/img_default.jpg';
                        if (file_exists(\Yii::getAlias('@frontend') . '/web/img/placeholder-img.gif')) {
                            $urlPlaceholder = '/img/placeholder-img.gif';
                        }
                        \open20\amos\attachments\utility\AttachmentsUtility::filterFilesDoesntExit($dataProviderImages);
//                        $dataProviderImages->pagination->pageSize = 10;
                        echo DataProviderView::widget([
                            'dataProvider' => $dataProviderImages,
                            'currentView' => $currentView,
                            'gridView' => [
                                'rowOptions' => function ($model, $key, $index, $grid) {
                                    $filePath = $model->getPath();
                                    if (!file_exists($filePath)) {
                                        return ['style' => 'visibility:collapse;'];
                                    } else {
                                        return ['style' => 'visibility:visible;'];
                                    }
                                },
                                'columns' => [
                                    'image' => [
                                        'label' => FileModule::t('amosattachments', 'Image'),
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            $url = $model->getUrl('square_small');
                                            return Html::img($url, [
                                                'class' => 'gridview-image',
                                                'alt' => FileModule::t('amosattachments', 'Image')
                                            ]);
                                        }
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'label' => FileModule::t('amosattachments', "Title"),
                                        'value' => function ($model) {
                                            return $model->getGenericName();
                                        }

                                    ],
                                    [
                                        'label' => FileModule::t('amosattachments', "Created at"),
                                        'value' => function ($model) {
                                            return $model->getCreatedAt()->format('d/m/Y');
                                        }
                                    ],
                                    [
                                        'attribute' => 'created_by',
                                        'label' => FileModule::t('amosattachments', "Created by"),
                                        'value' => function ($model) {
                                            return $model->getCreatedByProfile();
                                        },
                                    ],
                                    [
                                        'class' => ActionColumn::class,
                                        'controller' => 'attach-gallery-image',
                                        'template' => '{zoom}{view}{update}{delete}',
                                        'buttons' => [
                                            'zoom' => function ($url, $model) {
                                                return Html::a(
                                                    AmosIcons::show('zoom-in'),
                                                    '',
                                                    [
                                                        'class' => 'open-modal-detail-btn btn btn-tools-secondary',
                                                        'data-key' => $model->id,
                                                        'title' => FileModule::t('amosattachments', 'Apri immagine' . ' ' . $model->name),
                                                    ]
                                                );
                                            },
                                            'view' => function ($url, $model) {
                                                return Html::a(
                                                    AmosIcons::show('file'),
                                                    $model->getFullLinkViewUrl(),
                                                    [
                                                        'class' => 'btn btn-tools-secondary',
                                                        'data-key' => $model->id,
                                                        'title' => FileModule::t('amosattachments', 'Apri immagine' . ' ' . $model->name),
                                                    ]
                                                );
                                            },
                                            'update' => function ($url, $model) {
                                                if ($model->isBackendFile()) {
                                                    if (\Yii::$app->user->can('ATTACHGALLERYIMAGE_UPDATE', ['model' => $model->getRealModel()])) {
                                                        return Html::a(
                                                            AmosIcons::show('edit'),
                                                            ['/attachments/attach-gallery-image/update', 'id' => $model->item_id],
                                                            [
                                                                'class' => 'btn btn-tools-secondary',
                                                                'data-key' => $model->id,
                                                                'title' => FileModule::t('amosattachments', 'Modifica'),
                                                            ]
                                                        );
                                                    }
                                                }
                                                return '';
                                            },
                                            'delete' => function ($url, $model) {
                                                if ($model->isBackendFile()) {
                                                    if (\Yii::$app->user->can('ATTACHGALLERYIMAGE_DELETE', ['model' => $model->getRealModel()])) {
                                                        return Html::a(
                                                            AmosIcons::show('delete'),
                                                            ['/attachments/attach-gallery-image/delete', 'id' => $model->item_id],
                                                            [
                                                                'class' => 'btn btn-danger-inverse',
                                                                'data-key' => $model->id,
                                                                'title' => FileModule::t('amosattachments', 'Elimina'),
                                                                'data-confirm' => FileModule::t('amosattachments', 'Sei sicuro di eliminare questo elemento?')
                                                            ]
                                                        );
                                                    }
                                                }
                                                return '';
                                            },

                                        ]
                                    ]
                                ]
                            ],
                            'listView' => [
                                'viewParams' => ['urlPlaceholder' => $urlPlaceholder],
                                'itemView' => '../attach-gallery-image/_item',
                                'masonry' => false,

                                // Se masonry settato a TRUE decommentare
                                // e settare i parametri seguenti
                                // nel CSS settare i seguenti parametri necessari
                                // al funzionamento tipo
                                //
                                // .grid-sizer, .grid-item {width: 50&;}
                                //
                                // Per i dettagli recarsi sul sito
                                // http://masonry.desandro.com
                                //
                                // 'masonrySelector' => '.grid',
                                // 'masonryOptions' => [
                                //     'itemSelector' => '.grid-item',
                                //     'columnWidth' => '.grid-sizer',
                                //     'percentPosition' => 'true',
                                //     'gutter' => 0
                                //   ],
                                'showItemToolbar' => false,
                            ],
                        ]);
                    } else {
                        echo '<p>'
                            . FileModule::t(
                                'amosattachments',
                                "E' necessario salvare per poter inserire delle immagini alla galleria"
                            )
                            . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
</div>

<?php Modal::begin([
    'id' => 'detail-image-modal',
    'size' => 'modal-lg',

]) ?>

<div id="detail-image-modal-content"></div>

<?php Modal::end() ?>
