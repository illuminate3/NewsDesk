<?php

namespace App\Modules\NewsDesk\Database\Seeds;

use Illuminate\Database\Seeder;
Use DB, Eloquent, Model, Schema;


class PrintStatusesSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
//		DB::table('print_statuses')->truncate();

		$locale_id = DB::table('locales')
			->where('name', '=', 'English')
			->where('locale', '=', 'en', 'AND')
			->pluck('id');

		$print_statuses = array(
		[
			'id'					=> 1
		],
		[
			'id'					=> 2
		],
		[
			'id'					=> 3
		],
		[
			'id'					=> 4
		]
		);
		$print_status_translations = array(
		[
			'name'					=> 'Draft',
			'description'			=> 'Page is a draft',
			'print_status_id'		=> 1,
			'locale_id'				=> $locale_id
		],
		[
			'name'					=> 'Publish',
			'description'			=> 'Page is a draft',
			'print_status_id'		=> 2,
			'locale_id'				=> $locale_id
		],
		[
			'name'					=> 'Unpublish',
			'description'			=> 'Page is a draft',
			'print_status_id'		=> 3,
			'locale_id'				=> $locale_id
		],
		[
			'name'					=> 'Archieve',
			'description'			=> 'Page has been archieved',
			'print_status_id'		=> 4,
			'locale_id'				=> $locale_id
		],
		);

		// Uncomment the below to run the seeder
//		DB::table('print_statuses')->insert($seeds);

// Create Menus
		DB::table('print_statuses')->delete();
			$statement = "ALTER TABLE print_statuses AUTO_INCREMENT = 1;";
			DB::unprepared($statement);

// Create Menu Translations
		DB::table('print_status_translations')->delete();
			$statement = "ALTER TABLE print_status_translations AUTO_INCREMENT = 1;";
			DB::unprepared($statement);

// Insert Data
		DB::table('print_statuses')->insert( $print_statuses );
		DB::table('print_status_translations')->insert( $print_status_translations );

	} // run

}
