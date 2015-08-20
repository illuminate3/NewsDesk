<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateDocumentNewsTable extends Migration
{


	public function __construct()
	{
		// Get the prefix
		$this->prefix = Config::get('news.newsdesk_db.prefix', '');
	}


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create($this->prefix . 'document_news', function(Blueprint $table) {

			$table->engine = 'InnoDB';

			$table->integer('document_id')->unsigned()->index();
			$table->integer('news_id')->unsigned()->index();

			$table->foreign('document_id')->references('id')->on($this->prefix.'documents')->onDelete('cascade');
			$table->foreign('news_id')->references('id')->on($this->prefix.'news')->onDelete('cascade');

		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop($this->prefix . 'document_news');
	}


}
