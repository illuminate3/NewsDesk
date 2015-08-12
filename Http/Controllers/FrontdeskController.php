<?php

namespace App\Modules\NewsDesk\Http\Controllers;

use App\Modules\NewsDesk\Http\Models\Content;
use App\Modules\NewsDesk\Http\Repositories\ContentRepository;

use App\Helpers\Nifty\NiftyMenus;

use Illuminate\Http\Request;
use App\Modules\NewsDesk\Http\Requests\DeleteRequest;
// use App\Http\Requests\PageCreateRequest;
// use App\Http\Requests\PageUpdateRequest;

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
			Content $content,
			ContentRepository $content_repo
		)
	{
//dd('__construct');
		$this->content = $content;
		$this->content_repo = $content_repo;

		$lang = Session::get('locale');
		$locales = $this->content_repo->getLocales();
		$locale_id = 1;

//		$this->hashIds = new Hashids( Config::get('app.key'), 8, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' );

		$this->page = Route::current()->parameter('page');
//dd($this->page);
		$slugs = explode('/', $this->page);
//dd($slugs);
		$lastSlug = Route::current()->getName() == 'search' ? 'search' : $slugs[count($slugs)-1];
//dd($lastSlug);

//		$this->currentPage = Page::getPage( $slug = $lastSlug );
//		$this->currentPage = Content::getPage( $slug = $lastSlug );
//		$this->currentPage = $this->content_repo->getPage($locale_id, $slug = $lastSlug);
//		$this->currentPage = new \Illuminate\Support\Collection($this->currentPage);

		$page_ID = $this->content_repo->getPageID($slug = $lastSlug);
//dd($page_ID);
		$this->currentPage = $this->content_repo->getContent($page_ID);
//dd($this->currentPage);

//dd('here');
//		$this->roots = Page::getRoots();
//		$this->roots = Content::getRoots();
//		$this->roots = $this->content_repo->getRoots($locale_id);
//		$this->roots = Content::getRoots($locale_id);
//dd($this->roots);

// 		$this->postsOrderBy = ['id', 'desc'];
// 		$this->postsOrderByOrder = ['order', 'asc'];
// 		$this->postItemsNum = 10;
// 		$this->postItemsPerPage = 2;
		// $this->latestNewsPosts = Post::getLatestNewsPosts($this->postItemsNum, $this->postsOrderBy);
// 		$this->contact = ["Demo NiftyCMS", "demo@niftycms.com"];
	}

	public function get_page()
	{
//dd('get_page');
//dd($this->currentPage);
		if ( $this->currentPage ) {
// 			$mainMenu = NiftyMenus::getMainMenu( $this->currentPage );
// dd($mainMenu);
//dd($this->currentPage);
// 			$root = $this->currentPage->getRoot();
// 			$secMenu = NiftyMenus::getSecMenu($root, $this->currentPage );

//			return View::make('frontends.page', ['page' => $this->currentPage, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);

			$page = $this->currentPage;
/*
    0 => "meta_description"
    1 => "meta_keywords"
    2 => "meta_title"
    3 => "content"
    4 => "slug"
    5 => "summary"
    6 => "title"
*/

//dd($page);
// 			$mainMenu = $mainMenu;
// 			$secMenu = $secMenu;

		return Theme::View('modules.newsdesk.frontend.index',
			compact(
				'page'
			));
		}
		else
			App::abort(404);
	}

	public function index()
	{
dd('index');
		if ( $homePage = Page::getPage( $slug = 'home-page' ) ) {
			$mainMenu = NiftyMenus::getMainMenu( $homePage );
			// $posts = Post::getFrontendPosts($category = 'Home Featured', $this->postsOrderBy);
//			return View::make('frontends.index', ['page' => $homePage, /*'posts' => $posts,*/ 'mainMenu' => $mainMenu]);

			$page = $homePage;
			$mainMenu = $mainMenu;

			return View('nifty.frontends.index', compact(
				'mainMenu',
				'page'
				));
		}
		else
			App::abort(404);
	}

	public function contact_us()
	{
dd('contact_us');
		if ( $contact_us = Page::getPage( $slug = 'contact-us' ) ) {
			$mainMenu = NiftyMenus::getMainMenu( $contact_us );
			$root = $contact_us->getRoot();
			$secMenu = NiftyMenus::getSecMenu($root, $contact_us);
			return View::make('frontends.contact-us', ['page' => $contact_us, 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
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

	public function previewPage($hashedId)
	{
dd('previewPage');
		$id = $this->hashIds->decrypt($hashedId)[0];

		if ( $id ) {
			$previewPage = Page::getPreviewPage( $id );
			$mainMenu = NiftyMenus::getMainMenu( $previewPage );
			$root = $previewPage->getRoot();
			$secMenu = NiftyMenus::getSecMenu( $root, $previewPage );

			return View::make('frontends.page', ['page' => $previewPage, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function get_blog()
	{
dd('get_blog');
		if ( $blog = Page::getPage( $slug = 'blog' ) ) {
			$mainMenu = NiftyMenus::getMainMenu( $blog );
			$root = $blog->getRoot();
			$secMenu = NiftyMenus::getSecMenu($root, $blog);

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerPage );

			return View::make('frontends.blog', ['page' => $blog, 'posts' => $posts, 'links' => $posts->links('backend.pagination.nifty'), 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function get_post()
	{
dd('get_post');
		$slugs = explode( '/', Route::current()->parameter('any') );
		$lastSlug = $slugs[count($slugs)-1];

		if ( $blog = Page::getPage( $slug = 'blog' ) ) {
			$mainMenu = NiftyMenus::getMainMenu( $blog );
			$root = $blog->getRoot();
			$secMenu = NiftyMenus::getSecMenu($root, $blog);

			$post = Post::getFrontendPost( $lastSlug );

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerPage );

			return View::make('frontends.post', ['page' => $post, 'posts' => $posts, 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function previewPost($hashedId)
	{
dd('previewPost');
		$id = $this->hashIds->decrypt($hashedId)[0];

		if ( $id ) {
			$blogPage = Page::getPage( $lug = 'blog' );
			$blogPost = Post::find($id);
			$mainMenu = NiftyMenus::getMainMenu( $blogPage );
			$root = $blogPage->getRoot();
			$secMenu = NiftyMenus::getSecMenu( $root, $blogPage );

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerPage );

			return View::make('frontends.post', ['page' => $blogPost, 'posts' => $posts, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function do_search()
	{
dd('do_search');
		$term = Sanitiser::trimInput( Input::get('term') );
		$results = Search::getSearchResults($term);

		$searchPage = Page::getPage( $slug = 'search' );
		$mainMenu = NiftyMenus::getMainMenu( $searchPage );
		$secMenu = '';

		return View::make('frontends.search', ['page' => $searchPage, 'term' => $term, 'results' => $results, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
	}

}
