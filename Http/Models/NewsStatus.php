<?php

namespace App\Modules\Newsdesk\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

use Vinkla\Translator\Translatable;
use Vinkla\Translator\Contracts\Translatable as TranslatableContract;

class NewsStatus extends Model implements TranslatableContract {

	use PresentableTrait;
	use Translatable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'news_statuses';

// Presenter ---------------------------------------------------------------
	protected $presenter = 'App\Modules\Newsdesk\Http\Presenters\Newsdesk';

// Translation Model -------------------------------------------------------
	protected $translator = 'App\Modules\Newsdesk\Http\Models\NewsStatusTranslation';

// DEFINE Hidden -----------------------------------------------------------
	protected $hidden = [
		'created_at',
		'updated_at'
		];

// DEFINE Fillable ---------------------------------------------------------
	protected $fillable = [
		// Translatable columns
		'name',
		'description'
		];

// Translated Columns ------------------------------------------------------
	protected $translatedAttributes = [
		'name',
		'description'
		];

// DEFINE Functions --------------------------------------------------------

	public function getNameAttribute()
	{
		return $this->name;
	}

	public function getDescriptionAttribute()
	{
		return $this->description;
	}

// DEFINE Relationships ----------------------------------------------------

// hasMany
// belongsTo
// belongsToMany

}
