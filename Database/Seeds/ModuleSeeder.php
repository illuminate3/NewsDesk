<?php

namespace App\Modules\NewsDesk\Database\Seeds;

use Illuminate\Database\Seeder;
Use DB;
use Schema;


class ModuleSeeder extends Seeder {

	public function run()
	{

// Permissions -------------------------------------------------------------
		$permissions = array(
			[
				'name'				=> 'Manage NewsDesk CMS',
				'slug'				=> 'manage_newsdesk',
				'description'		=> 'Give permission to user to manage CMS'
			],
		 );

		if (Schema::hasTable('permissions'))
		{
			DB::table('permissions')->insert( $permissions );
		}

// Links -------------------------------------------------------------------
// Locales

		$link_names = array([
			'menu_id'				=> 1, // admin menu
			'position'				=> 3,
		]);

		if (Schema::hasTable('menulinks'))
		{
			DB::table('menulinks')->insert( $link_names );
		}

		$last_insert_id = DB::getPdo()->lastInsertId();
		$locale_id = DB::table('locales')
			->where('name', '=', 'English')
			->where('locale', '=', 'en', 'AND')
			->pluck('id');

		$ink_name_trans = array([
			'status'				=> 1,
			'title'					=> 'Contents',
			'url'					=> '/admin/contents',
			'menulink_id'			=> $last_insert_id,
			'locale_id'				=> $locale_id // English ID
		]);

		if (Schema::hasTable('menulinks'))
		{
			DB::table('menulink_translations')->insert( $ink_name_trans );
		}

// Settings
		$link_names = array([
			'menu_id'				=> 1, // admin menu
			'position'				=> 7,
		]);

		if (Schema::hasTable('menulinks'))
		{
			DB::table('menulinks')->insert( $link_names );
		}

		$last_insert_id = DB::getPdo()->lastInsertId();
		$locale_id = DB::table('locales')
			->where('name', '=', 'English')
			->where('locale', '=', 'en', 'AND')
			->pluck('id');

		$ink_name_trans = array([
			'status'				=> 1,
			'title'					=> 'Print Statuses',
			'url'					=> '/admin/print_statuses',
			'menulink_id'			=> $last_insert_id,
			'locale_id'				=> $locale_id // English ID
		]);

		if (Schema::hasTable('menulinks'))
		{
			DB::table('menulink_translations')->insert( $ink_name_trans );
		}

	} // run

}
