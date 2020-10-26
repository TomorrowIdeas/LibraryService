<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBooksTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
		$table = $this->table("books", ['id' => false, 'primary_key' => ['id']]);
		$table->addColumn('id', 'uuid')
		->addColumn('isbn', 'string')
		->addColumn('publisher_id', 'uuid')
		->addColumn('author_id', 'uuid')
		->addColumn('title', 'string', ['length' => 64])
		->addColumn('genre', 'string', ['length' => 64])
		->addColumn('summary', 'text', ['limit' => 'TEXT_TINY', 'null' => true])
		->addColumn('edition', 'integer', ['null' => true])
		->addColumn('pages', 'integer')
		->addColumn('preview_url', 'string', ['null' => true])
		->addColumn('published_at', 'date')
		->addTimestampsWithTimezone()
		->addIndex('isbn', ['unique' => true])
		->addIndex('publisher_id')
		->addIndex('author_id')
		->addIndex('genre')
		->create();
    }
}
