<?php

namespace App\Modules\NewsDesk\Http\Controllers;

use App\Modules\Core\Http\Repositories\LocaleRepository;

use App\Modules\NewsDesk\Http\Models\News;
use App\Modules\NewsDesk\Http\Repositories\NewsRepository;

use Illuminate\Http\Request;
use App\Modules\NewsDesk\Http\Requests\DeleteRequest;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

use Carbon\Carbon;
use Config;
use Flash;
use Hashids\Hashids;
use Session;
use Route;
use Theme;


class FrontDeskController extends NewsDeskController {

	public function __construct(
			LocaleRepository $locale_repo,
			News $news,
			NewsRepository $news_repo
		)
	{
//dd('__construct');
		$this->locale_repo = $locale_repo;
		$this->news = $news;
		$this->news_repo = $news_repo;

		$lang = Session::get('locale');
		$locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);


		$this->article = Route::current()->parameter('news');
//		$this->article = Route::current()->getUri();
//dd($this->article);

		$slugs = explode('/', $this->article);
//dd($slugs);
		$lastSlug = Route::current()->getName() == 'search' ? 'search' : $slugs[count($slugs)-1];
//dd($lastSlug);

		$article_ID = $this->news_repo->getArticleID($slug = $lastSlug);
//dd($article_ID);
		$this->currentArticle = $this->news->with('images', 'documents')->find($article_ID);
//		$this->currentArticle = $this->news_repo->with('images', 'documents')->getNews($article_ID);
//dd($this->currentArticle);

	}

	public function get_article()
	{
//dd('get_article');
//dd($this->currentArticle);
		if ( $this->currentArticle ) {
// 			$mainMenu = NiftyMenus::getMainMenu( $this->currentArticle );
// dd($mainMenu);
//dd($this->currentArticle);
// 			$root = $this->currentArticle->getRoot();
// 			$secMenu = NiftyMenus::getSecMenu($root, $this->currentArticle );

//			return View::make('frontends.article', ['news' => $this->currentArticle, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);

			$article = $this->currentArticle;
/*
    0 => "meta_description"
    1 => "meta_keywords"
    2 => "meta_title"
    3 => "content"
    4 => "slug"
    5 => "summary"
    6 => "title"
*/

//dd($article);
// 			$mainMenu = $mainMenu;
// 			$secMenu = $secMenu;

		return Theme::View('modules.newsdesk.frontdesk.index',
			compact(
				'article'
			));
		}
		else
			App::abort(404);
	}

	public function index()
	{
dd('index');
		if ( $homeArticle = Article::getArticle( $slug = 'home-article' ) ) {
			$mainMenu = NiftyMenus::getMainMenu( $homeArticle );
			// $posts = Post::getFrontendPosts($category = 'Home Featured', $this->postsOrderBy);
//			return View::make('frontends.index', ['news' => $homeArticle, /*'posts' => $posts,*/ 'mainMenu' => $mainMenu]);

			$article = $homeArticle;
			$mainMenu = $mainMenu;

			return View('nifty.frontends.index', compact(
				'mainMenu',
				'news'
				));
		}
		else
			App::abort(404);
	}

	public function contact_us()
	{
dd('contact_us');
		if ( $contact_us = Article::getArticle( $slug = 'contact-us' ) ) {
			$mainMenu = NiftyMenus::getMainMenu( $contact_us );
			$root = $contact_us->getRoot();
			$secMenu = NiftyMenus::getSecMenu($root, $contact_us);
			return View::make('frontends.contact-us', ['news' => $contact_us, 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function do_contact_us()
	{
dd('do_contact_us');
		$inputs = [];
		foreach(Input::all() as $key=>$input)
		{
			$inputs[$key] = Sanitiser::trimInput($input);
		}

		$rules = [
					'name' => 'required|max:255',
					'email' => 'required|email',
					'subject' => 'required',
					'message' => 'required'
				];

		$validation = MyValidations::validate($inputs, $rules);

		if($validation != NULL) {
			return Redirect::back()->withErrors($validation)->withInput();
		}

		else {
    		$data = [ 'name' => $inputs['name'], 'emailbody' => $inputs['message'] ];
    		$to_email = $this->contact[1];
    		$to_name = $this->contact[0];

			$issent =
			Mail::send('emails.contact-us', $data, function($message) use ($inputs, $to_email, $to_name)
			{
			    $message->from($inputs['email'], $inputs['name'])->to($to_email, $to_name)->subject('Website Contact Us: ' . $inputs['subject']);
			});

			if ($issent) {
				$feedback = ['success', 'Message successfully sent. We will be in touch soon'];
			}

			else {
				$feedback = ['failure', 'Your email was not sent. Kindly try again.'];
			}

			return Redirect::to('contact-us')->with($feedback[0], $feedback[1]);
		}
	}

	public function previewArticle($hashedId)
	{
dd('previewArticle');
		$id = $this->hashIds->decrypt($hashedId)[0];

		if ( $id ) {
			$previewArticle = Article::getPreviewArticle( $id );
			$mainMenu = NiftyMenus::getMainMenu( $previewArticle );
			$root = $previewArticle->getRoot();
			$secMenu = NiftyMenus::getSecMenu( $root, $previewArticle );

			return View::make('frontends.article', ['news' => $previewArticle, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function get_blog()
	{
dd('get_blog');
		if ( $blog = Article::getArticle( $slug = 'blog' ) ) {
			$mainMenu = NiftyMenus::getMainMenu( $blog );
			$root = $blog->getRoot();
			$secMenu = NiftyMenus::getSecMenu($root, $blog);

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerArticle );

			return View::make('frontends.blog', ['news' => $blog, 'posts' => $posts, 'links' => $posts->links('backend.pagination.nifty'), 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function get_post()
	{
dd('get_post');
		$slugs = explode( '/', Route::current()->parameter('any') );
		$lastSlug = $slugs[count($slugs)-1];

		if ( $blog = Article::getArticle( $slug = 'blog' ) ) {
			$mainMenu = NiftyMenus::getMainMenu( $blog );
			$root = $blog->getRoot();
			$secMenu = NiftyMenus::getSecMenu($root, $blog);

			$post = Post::getFrontendPost( $lastSlug );

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerArticle );

			return View::make('frontends.post', ['news' => $post, 'posts' => $posts, 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function previewPost($hashedId)
	{
dd('previewPost');
		$id = $this->hashIds->decrypt($hashedId)[0];

		if ( $id ) {
			$blogArticle = Article::getArticle( $lug = 'blog' );
			$blogPost = Post::find($id);
			$mainMenu = NiftyMenus::getMainMenu( $blogArticle );
			$root = $blogArticle->getRoot();
			$secMenu = NiftyMenus::getSecMenu( $root, $blogArticle );

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerArticle );

			return View::make('frontends.post', ['news' => $blogPost, 'posts' => $posts, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function do_search()
	{
dd('do_search');
		$term = Sanitiser::trimInput( Input::get('term') );
		$results = Search::getSearchResults($term);

		$searchArticle = Article::getArticle( $slug = 'search' );
		$mainMenu = NiftyMenus::getMainMenu( $searchArticle );
		$secMenu = '';

		return View::make('frontends.search', ['news' => $searchArticle, 'term' => $term, 'results' => $results, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
	}

}
