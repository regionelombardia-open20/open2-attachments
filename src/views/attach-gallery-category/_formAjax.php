<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
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