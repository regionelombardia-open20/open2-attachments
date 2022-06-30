<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m210931_163813_create_attach_image_request extends Migration
{
    const TABLE = "attach_gallery_request";


    /**
     * @inheritdoc
     */
    public function up()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'title' => $this->string()->comment('Title'),
                'status' => $this->string()->comment('Status'),
                'aspect_ratio' => $this->string()->comment('Aspect ratio'),
                'text_request' => $this->text()->comment('Request'),
                'text_reply' => $this->text()->comment('Reply'),
                'attach_gallery_id' => $this->integer()->comment('Gallery'),
                'attach_gallery_image_id' => $this->integer()->comment('image'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
        $this->addForeignKey('fk_attach_gallery_request_gallery_id', 'attach_gallery_request', 'attach_gallery_id', 'attach_gallery', 'id');
        $this->addForeignKey('fk_attach_gallery_request_image_id', 'attach_gallery_request', 'attach_gallery_image_id', 'attach_gallery_image', 'id');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
