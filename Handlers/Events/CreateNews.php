<?php

namespace App\Modules\Newsdesk\Handlers\Events;

use App\Modules\Newsdesk\Events\NewsWasCreated;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use App\Modules\Newsdesk\Http\Models\News;
use App\Modules\Newsdesk\Http\Repositories\NewsRepository;

use App\Modules\Yubin\Http\Models\MailCanned;
use App\Modules\Yubin\Http\Repositories\MailCannedRepository;
use App\Modules\Yubin\Http\Models\MailGroup;
use App\Modules\Yubin\Http\Repositories\MailGroupRepository;

use App\Modules\Yubin\Http\Library\YubinMailer;

use Config;
use Mail;
use Setting;


class CreateNews {


	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct(
			MailCannedRepository $mail_canned_repo,
			MailGroupRepository $mail_group_repo,
			NewsRepository $news_repo,
			YubinMailer $yubin_mailer
		)
	{
		$this->mail_canned_repo = $mail_canned_repo;
		$this->mail_group_repo = $mail_group_repo;
		$this->news_repo = $news_repo;
		$this->yubin_mailer = $yubin_mailer;
	}


	/**
	 * Handle the event.
	 *
	 * @param  NewsWasCreated  $email
	 * @return void
	 */
	public function handle(NewsWasCreated $data)
	{
//dd($data->id);
		if ($data != null) {

			$content = News::find($data->id);
			$this->sendEmail($content);

		}
	}


	public function sendEmail($content)
	{

		$group_slug = Config::get('news.mailer.group_slug');
		$mail_group_id = $this->mail_group_repo->getMailerIDbySlug($group_slug);
		$recipients = MailGroup::find($mail_group_id)->groups;
//dd($recipients);

		$slug = Config::get('news.mailer.create_canned_slug');
		$canned_id = $this->mail_canned_repo->getCannedIDbySlug($slug);
		$mail_canned = MailCanned::find($canned_id);

		$from_email = Setting::get('news_from_email', Config::get('news.mailer.from_email'));
		$from_name = Setting::get('news_from_name', Config::get('news.mailer.from_name'));

		$subject = $mail_canned->subject;

		$message = '<h1>' . $mail_canned->message . '</h1>';
		$message .= '<br>' . trans('kotoba::general.title') . ': ' . $content->title;
		$message .= '<br>' . trans('kotoba::cms.summary') . ': ' . $content->summary;

		$template = Setting::get('news_template', Config::get('news.mailer.template'));
		$theme_layout = 'emails.layouts.html';
		$template_view = 'emails.templates.' . $template;

		foreach ($recipients as $recipient )
		{

			if ( ($recipient->id != Config::get('news.editor_one')) || ($recipient->id != Config::get('news.editor_two')) ) {
				$data = array(
					'news_id'			=> $content->id,
					'from_email'		=> $from_email,
					'from_name'			=> $from_name,
//					'to_email'			=> $to_email,
					'to_email'			=> $recipient->email,
					'subject'			=> $subject,
					'canned'			=> $message,
//					'theme'				=> $theme,
					'theme_layout'		=> $theme_layout
				);

				$this->yubin_mailer->sendMail($template_view, $data, $message);
			}
		}

		return;
	}


}
