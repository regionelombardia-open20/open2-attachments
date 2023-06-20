<?php
/**
 * @var $modelSearch
 * @var $dataProvider
 */

use open20\amos\core\views\ListView;
use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\Shutterstock;
use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = FileModule::t('amosattachments', "Immagini Shutterstock");
$this->params['breadcrumbs'][] = $this->title;
$module = \Yii::$app->getModule('attachments');

$textSaveSuccess = FileModule::t('amosattachments', 'Immagine salvata correttamente nella galleria immagini');
$enableSandbox = true;
if (isset($module->shutterstockConfigs['enableSandbox'])) {
    $enableSandbox = $module->shutterstockConfigs['enableSandbox'];
}

\open20\amos\attachments\assets\ModuleAttachmentsAsset::register($this);
\open20\amos\layout\assets\SpinnerWaitAsset::register($this);

$js = <<<JS
    $('.open-modal-detail-shutterstock-btn').on('click', function(e){
         e.preventDefault();
         var image_id = $(this).attr('data-key');
         $.ajax({
          method: 'get',
          url: "/attachments/shutterstock/load-detail-index",
          data: {
              id_image: image_id
          },
          success: function(data){
                $('#detail-image-modal-content').html(data);
                $('#detail-image-modal').modal('show');
                }
        }); 
    });

  $(document).on('click','.upload-from-shutterstock', function(e){
            e.preventDefault();
            $('.loading').show();
            var csrf = '';
            var id_image = $(this).attr('data-key');
            var proscription_type = $('#licenceImageType-id').val();
            var src_image = $(this).attr('data-src');
            var name_image = $(this).attr('data-name');
            var tag_img = "<img class='img-responsive' src='"+src_image+"' style='display:block;width:100%;height:auto; 'min-width:0!important;min-height:0!important; max-width:none!important;max-height:none!important; image-orientation:0deg!important;'>";
              $.ajax({
              method: 'get',
              url: "/attachments/shutterstock/upload-from-gallery-ajax",
              data: {id: id_image, proscription_type: proscription_type, name:name_image },
              success: function(data){
                 $('.loading').hide();
                  if(data.success == true){
                    $('#detail-image-modal').modal('hide');
                      alert("$textSaveSuccess");
                    }else{
                      // console.log(data.data);
                      alert(data.message)
                    }
              },
              error: function(data){
                  $('.loading').hide();
                  alert("Si è verificato un errore")
              }
            });
        });
JS;
$this->registerJs($js);
if (!empty($modelSearch->errorMessage)) { ?>
    <div class="alert alert-warning" role="alert">
        <?= $modelSearch->errorMessage ?>
    </div>
<?php } ?>

<?php if ($enableSandbox) { ?>
    <div class="alert alert-info" role="alert">
        <p>
            <strong><?= FileModule::t('amosattachments', "Sandbox abilitata per la Demo") . ': ' ?></strong><?= FileModule::t('amosattachments', "potrai scaricare un numero illimitato di immagini con la filigrana di Shutterstock.") ?>
        </p>
    </div>
<?php } else { ?>
    <p>
        <?= FileModule::t('amosattachments', "Numero di immagini scaricabili rimanenti") . ': ' ?>
        <strong><?= Shutterstock::getDownloadRemainingFromDb() ?></strong>
    </p>
<?php } ?>

<?php echo $this->render('_search', ['model' => $modelSearch]); ?>

<div class="dimmable position-fixed loader loading" style="display:none">
    <div class="dimmer d-flex align-items-center" id="dimmer1">
        <div class="dimmer-inner">
            <div class="dimmer-icon">
                <div class="progress-spinner progress-spinner-active loading m-auto">
                    <span class="sr-only">Caricamento...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="attach-gallery-form m-b-35">
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <?php
                    echo \open20\amos\core\views\DataProviderView::widget([
                        'dataProvider' => $dataProvider,
                        'currentView' => $currentView,
                        'gridView' => [
                            'columns' => [
                                [
                                    'label' => 'Immagine',
                                    'value' => function ($model) {
                                        return Shutterstock::getRenderedImage($model, 'preview');
                                    },
                                    'format' => 'raw'
                                ],
                                [
                                    'label' => 'Tipo di immagine',
                                    'value' => function ($model) {
                                        return Shutterstock::imageTypes()[$model->image_type];
                                    },
                                    'format' => 'raw'
                                ],
                                'description',
                            ]
                        ],
                        'listView' => [
                            'itemView' => '_icon'
                        ]
                    ]);

                    if (!empty($modelSearch->pagination)) {
                        echo \open20\amos\core\views\AmosLinkPager::widget([
                            'pagination' => $modelSearch->pagination,
                            'showSummary' => true,
                            'bottomPositionSummary' => true,
                        ]);
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Modal::begin([
    'id' => 'detail-image-modal',
    'size' => 'modal-lg',

]) ?>

<div id="detail-image-modal-content"></div>

<?php Modal::end();

