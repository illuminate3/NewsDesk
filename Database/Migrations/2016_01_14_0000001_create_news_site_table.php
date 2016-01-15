<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateNewsSiteTable
 */
class CreateNewsSiteTable extends Migration
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

		Schema::create($this->prefix . 'news_site', function(Blueprint $table) {

			$table->engine = 'InnoDB';

			$table->integer('news_id')->unsigned()->index();
			$table->integer('site_id')->unsigned()->index();
//			$table->integer('order')->unsigned()->nullable();

			$table->foreign('news_id')->references('id')->on($this->prefix.'news')->onDelete('cascade');
			$table->foreign('site_id')->references('id')->on($this->prefix.'sites')->onDelete('cascade');

		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop($this->prefix . 'news_site');
	}


}
