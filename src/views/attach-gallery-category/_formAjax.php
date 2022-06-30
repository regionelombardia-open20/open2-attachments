<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-attachments/src/views 
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\icons\AmosIcons;

use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;

/**
* @var yii\web\View $this
* @var open20\amos\attachments\models\AttachGalleryCategory $model
* @var yii\widgets\ActiveForm $form
*/
?>
<?= $this->render('_form', [
    'model' => $model,
    'fid' => null !== (filter_input(INPUT_GET, 'fid'))
        ? filter_input(INPUT_GET, 'fid')
        : '',
    'dataField' => null !== (filter_input(INPUT_GET, 'dataField'))
        ? filter_input(INPUT_GET, 'dataField')
        : '',
    'dataEntity' => null !== (filter_input(INPUT_GET, 'dataEntity'))
        ? filter_input(INPUT_GET, 'dataEntity')
        : '',
    'class' => 'dynamicCreation'
])
?>