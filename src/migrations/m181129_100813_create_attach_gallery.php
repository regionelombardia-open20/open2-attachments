<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m181129_100813_create_attach_gallery extends Migration
{
    const TABLE_GALLERY = "attach_gallery";
    const TABLE_IMAGE = "attach_gallery_image";
    const TABLE_CATEGORY = "attach_gallery_category";



    /**
     * @inheritdoc
     */
    public function up()
    {

        if ($this->db->schema->getTableSchema(self::TABLE_GALLERY, true) === null)
        {
            $this->createTable(self::TABLE_GALLERY, [
                'id' => Schema::TYPE_PK,
                'slug' => $this->string(),
                'name' => $this->string()->notNull()->comment('Name'),
                'description' => $this->text()->comment('Description'),
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


        if ($this->db->schema->getTableSchema(self::TABLE_CATEGORY, true) === null)
        {
            $this->createTable(self::TABLE_CATEGORY, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->notNull()->comment('Name'),
                'description' => $this->text()->comment('Description'),
                'default_order' => $this->integer()->comment('Order'),
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

        if ($this->db->schema->getTableSchema(self::TABLE_IMAGE, true) === null)
        {
            $this->createTable(self::TABLE_IMAGE, [
                'id' => Schema::TYPE_PK,
                'category_id' => $this->integer()->notNull()->comment('Category'),
                'gallery_id' => $this->integer()->notNull()->comment('Gallery'),
                'name' => $this->string()->comment('Name'),
                'description' => $this->text()->comment('Description'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_attach_gallery_image_category_id1', self::TABLE_IMAGE, 'category_id', 'attach_gallery_category', 'id');
            $this->addForeignKey('fk_attach_gallery_id1', self::TABLE_IMAGE, 'gallery_id', 'attach_gallery', 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        $this->insert(self::TABLE_GALLERY, ['id' => 1, 'slug' => 'general', 'name' => 'General']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE_IMAGE);
        $this->dropTable(self::TABLE_GALLERY);
        $this->dropTable(self::TABLE_CATEGORY);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
