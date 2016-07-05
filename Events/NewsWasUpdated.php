<?php

namespace App\Modules\Newsdesk\Events;

use App\Modules\Newsdesk\Events\Event;
use Illuminate\Queue\SerializesModels;


class NewsWasUpdated extends Event {

	use SerializesModels;

	public $data;

	public function __construct($data)
	{
//dd($data);
//dd($data->id);

		$this->id				= $data->id;
//		$this->email			= $data->email;

	}


}
