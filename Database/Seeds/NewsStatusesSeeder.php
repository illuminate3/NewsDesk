<?php

namespace App\Modules\NewsDesk\Database\Seeds;

use Illuminate\Database\Seeder;
Use DB, Eloquent, Model, Schema;


class NewsStatusesSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
//		DB::table('news_statuses')->truncate();

		$locale_id = DB::table('locales')
			->where('name', '=', 'English')
			->where('locale', '=', 'en', 'AND')
			->pluck('id');

		$news_statuses = array(
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
		$news_status_translations = array(
		[
			'name'					=> 'Draft',
			'description'			=> 'Article is a draft',
			'news_status_id'		=> 1,
			'locale_id'				=> $locale_id
		],
		[
			'name'					=> 'Publish',
			'description'			=> 'Article is a draft',
			'news_status_id'		=> 2,
			'locale_id'				=> $locale_id
		],
		[
			'name'					=> 'Unpublish',
			'description'			=> 'Article is a draft',
			'news_status_id'		=> 3,
			'locale_id'				=> $locale_id
		],
		[
			'name'					=> 'Archieve',
			'description'			=> 'Article has been archieved',
			'news_status_id'		=> 4,
			'locale_id'				=> $locale_id
		],
		);

		// Uncomment the below to run the seeder
//		DB::table('news_statuses')->insert($seeds);

// Create Menus
		DB::table('news_statuses')->delete();
			$statement = "ALTER TABLE news_statuses AUTO_INCREMENT = 1;";
			DB::unprepared($statement);

// Create Menu Translations
		DB::table('news_status_translations')->delete();
			$statement = "ALTER TABLE news_status_translations AUTO_INCREMENT = 1;";
			DB::unprepared($statement);

// Insert Data
		DB::table('news_statuses')->insert( $news_statuses );
		DB::table('news_status_translations')->insert( $news_status_translations );

	} // run

}
