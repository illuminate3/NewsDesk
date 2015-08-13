<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateNewsTable extends Migration
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
		Schema::create($this->prefix . 'news', function(Blueprint $table) {

			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();

			$table->integer('user_id')->unsigned();
			$table->integer('parent_id')->nullable();
			$table->integer('lft')->nullable();
			$table->integer('rgt')->nullable();
			$table->integer('depth')->nullable();


			$table->string('slug')->nullable();


			$table->text('featured_image', 255)->nullable();
			$table->string('class', 50)->nullable();
//			$table->text('link', 255)->nullable();

			$table->tinyInteger('news_status_id')->default(0);

			$table->tinyInteger('is_banner')->default(0);
			$table->tinyInteger('is_featured')->default(0);
			$table->tinyInteger('is_timed')->default(0);

			$table->date('publish_start')->nullable();
			$table->date('publish_end')->nullable();

			$table->integer('order')->nullable();


			$table->index('parent_id');
			$table->index('lft');
			$table->index('rgt');


			$table->foreign('user_id')->references('id')->on('users');

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
		Schema::drop($this->prefix . 'news');
	}

}
