<?php

namespace App\Modules\NewsDesk\Database\Seeds;

use Illuminate\Database\Seeder;
Use DB;
use Schema;


class ModulePermissionsSeeder extends Seeder
{


	public function run()
	{

// Permissions -------------------------------------------------------------
		$permissions = array(
			[
				'name'				=> 'Manage NewsDesk',
				'slug'				=> 'manage_newsdesk',
				'description'		=> 'Give permission to user to manage the NewsDesk system'
			],
		 );

		if (Schema::hasTable('permissions'))
		{
			DB::table('permissions')->insert( $permissions );
		}

	} // run


}
