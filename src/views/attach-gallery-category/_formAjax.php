<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/lispa/amos-attachments/src/views 
 */
use lispa\amos\core\helpers\Html;
use lispa\amos\core\forms\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use lispa\amos\core\forms\Tabs;
use lispa\amos\core\forms\CloseSaveButtonWidget;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use lispa\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;

/**
* @var yii\web\View $this
* @var lispa\amos\attachments\models\AttachGalleryCategory $model
* @var yii\widgets\ActiveForm $form
*/
?>
<?= $this->render('_form', [
    'model' => $model,
    'fid' => (NULL !== (filter_input(INPUT_GET, 'fid')))? filter_input(INPUT_GET, 'fid') : '',
    'dataField' => (NULL !== (filter_input(INPUT_GET, 'dataField')))? filter_input(INPUT_GET, 'dataField') : '',
    'dataEntity' => (NULL !== (filter_input(INPUT_GET, 'dataEntity')))? filter_input(INPUT_GET, 'dataEntity') : '',
    'class' => 'dynamicCreation'
]) ?>