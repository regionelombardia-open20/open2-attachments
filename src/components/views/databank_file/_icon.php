<?php
/**
 * @var $documentMainFile
 */

use open20\amos\attachments\FileModule;
use yii\helpers\Html;

?>

<?= $documentMainFile->getAttachmentIcon(); ?>
<span class="text-muted small"><?= strtoupper($documentMainFile->type); ?>
    (<?= $documentMainFile->formattedSize ?>) - <?= FileModule::tHtml('amosdocumenti', 'File :') ?>
</span>

<?php echo Html::tag('span', ((strlen($documentMainFile->name) > 80) ? substr($documentMainFile->name, 0, 75) . '[...]' : $documentMainFile->name) . '.' . $documentMainFile->type, ['class' => 'text-muted small']); ?>