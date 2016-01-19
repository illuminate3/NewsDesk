<?php

namespace App\Modules\Newsdesk\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NewsdeskDatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('App\Modules\Newsdesk\Database\Seeds\ModulePermissionsSeeder');
		$this->call('App\Modules\Newsdesk\Database\Seeds\ModuleLinksSeeder');
		$this->call('App\Modules\Newsdesk\Database\Seeds\NewsStatusesSeeder');

	}


}
