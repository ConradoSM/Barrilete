<?php
namespace barrilete\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use barrilete\Articles;
use barrilete\Gallery;
use barrilete\Poll;


class IndexController extends Controller
{
    /**
     * @var Articles
     */
    protected $_articles;

    /**
     * @var Gallery
     */
    protected $_gallery;

    /**
     * @var Poll
     */
    protected $_poll;

    /**
     * IndexController constructor.
     * @param Articles $articles
     * @param Gallery $gallery
     * @param Poll $poll
     */
    public function __construct(
        Articles $articles,
        Gallery $gallery,
        Poll $poll
    )
    {
        $this->_articles = $articles;
        $this->_gallery = $gallery;
        $this->_poll = $poll;
    }

    /**
     * Home Site
     * @return Factory|View
     */
    public function home()
    {
        $articlesIndex = $this->_articles->articlesHome();
        $galleryIndex = $this->_gallery->galleryHome()->count() != 0 ? $this->_gallery->galleryHome()->first() : null;
        $pollsIndex = $this->_poll->pollsHome()->count() != 0 ? $this->_poll->pollsHome() : null;

        return view('default', compact('articlesIndex','galleryIndex','pollsIndex'));
    }
}
