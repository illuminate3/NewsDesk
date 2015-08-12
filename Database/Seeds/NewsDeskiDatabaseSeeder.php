<?php

namespace App\Modules\NewsDesk\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class NewsDeskDatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('App\Modules\NewsDesk\Database\Seeds\ModuleSeeder');
		$this->call('App\Modules\NewsDesk\Database\Seeds\PrintStatusesSeeder');

	}

}
