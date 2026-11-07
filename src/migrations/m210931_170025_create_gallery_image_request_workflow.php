<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\news\models\News;
use yii\helpers\ArrayHelper;
USE open20\amos\core\migration\AmosMigrationWorkflow;

class m210931_170025_create_gallery_image_request_workflow extends AmosMigrationWorkflow
{

    const REQUEST_WORKFLOW = 'GalleryImageRequestWorkflow';



    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
            parent::setWorkflow(),
            $this->workflowConf(),
            $this->workflowStatusConf(),
            $this->workflowTransitionsConf(),
            $this->workflowMetadataConf()
        );
    }


    /**
     * In this method there are the new workflow configuration.
     * @return array
     */
    private function workflowConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW,
                'id' => self::REQUEST_WORKFLOW,
                'initial_status_id' => 'OPENED'
            ]
        ];
    }

    /**
     * In this method there are the new workflow statuses configurations.
     * @return array
     */
    private function workflowStatusConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'OPENED',
                'workflow_id' => self::REQUEST_WORKFLOW,
                'label' => 'Aperta',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'CLOSED',
                'workflow_id' => self::REQUEST_WORKFLOW,
                'label' => 'Chiusa',
                'sort_order' => '2'
            ],

        ];
    }

    /**
     * In this method there are the new workflow status transitions configurations.
     * @return array
     */
    private function workflowTransitionsConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::REQUEST_WORKFLOW,
                'start_status_id' => 'OPENED',
                'end_status_id' => 'CLOSED'
            ],

        ];
    }

    /**
     * In this method there are the new workflow metadata configurations.
     * @return array
     */
    private function workflowMetadataConf()
    {
        return [

            // "Draft" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' =>  self::REQUEST_WORKFLOW,
                'status_id' => 'CLOSED',
                'key' => 'buttonLabel',
                'value' => 'Chiudi'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' =>  self::REQUEST_WORKFLOW,
                'status_id' => 'CLOSED',
                'key' => 'description',
                'value' => 'Chiudi richiesta'
            ],
//
        ];
    }

}
