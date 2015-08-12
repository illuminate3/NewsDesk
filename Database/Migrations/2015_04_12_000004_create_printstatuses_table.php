<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreatePrintStatusesTable extends Migration
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
/*
		Schema::create($this->prefix . 'print_statuses', function(Blueprint $table) {

			$table->engine = 'InnoDB';
			$table->increments('id');

			$table->string('name')->nullable();
			$table->string('description')->nullable();

			$table->softDeletes();
			$table->timestamps();

		});
*/
		Schema::create($this->prefix . 'print_statuses', function(Blueprint $table) {

			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();

// 			$table->string('name');
// 			$table->string('class')->nullable();

			$table->softDeletes();
			$table->timestamps();

		});

		Schema::create($this->prefix . 'print_status_translations', function(Blueprint $table) {

			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();

			$table->string('name')->nullable();
			$table->string('description')->nullable();

			$table->integer('print_status_id')->unsigned()->index();
			$table->foreign('print_status_id')->references('id')->on('print_statuses')->onDelete('cascade');

			$table->integer('locale_id')->unsigned()->index();
			$table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');

			$table->unique(['print_status_id', 'locale_id']);

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
		Schema::drop($this->prefix . 'print_status_translations');
		Schema::drop($this->prefix . 'print_statuses');
	}

}
