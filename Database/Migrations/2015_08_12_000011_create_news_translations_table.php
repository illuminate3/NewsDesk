<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Bluenews;


class CreateContentTranslationsTable extends Migration
{

	public function __construct()
	{
		// Get the prefix
		$this->prefix = Config::get('newsdesk.newsdesk_db.prefix', '');
	}


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create($this->prefix . 'content_translations', function(Bluenews $table) {

			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();

// 			$table->string('title', 255)->nullable();
// 			$table->string('slug', 255)->nullable();
// 			$table->string('summary',512)->nullable();
// 			$table->text('content')->nullable();

			$table->string('title')->nullable();
//			$table->string('slug')->nullable();
			$table->string('summary')->nullable();
			$table->text('content')->nullable();

			$table->string('meta_title')->nullable();
			$table->string('meta_keywords')->nullable();
			$table->string('meta_description')->nullable();

			$table->integer('content_id')->unsigned()->index();
			$table->foreign('content_id')->references('id')->on('news')->onDelete('cascade');

			$table->integer('locale_id')->unsigned()->index();
			$table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');

			$table->unique(['content_id', 'locale_id']);

			$table->softDeletes();
			$table->timestamps();

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop($this->prefix . 'content_translations');
	}

}
