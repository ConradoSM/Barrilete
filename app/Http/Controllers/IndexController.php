<?php
namespace barrilete\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
        /** Get Breaking News **/
        $breakingNews = $this->breakingNews();

        /** Get Index Content **/
        $articlesIndex = $this->articlesIndex();
        $galleryIndex = $this->galleryIndex();
        $pollsIndex = $this->pollsIndex();

        /** Get top 5 articles **/
        $mostSeen = $this->mostSeen();
        $mostCommented = $this->mostCommented();
        $mostLikes = $this->mostLikes();

        return view('default', compact('articlesIndex','galleryIndex','pollsIndex', 'breakingNews', 'mostSeen' ,'mostCommented', 'mostLikes'));
    }

    /**
     * @return mixed
     */
    protected function articlesIndex()
    {
        return $this->_articles->articlesHome();
    }

    /**
     * @return mixed
     */
    protected function galleryIndex()
    {
        return $this->_gallery->galleryHome()->count() != 0 ? $this->_gallery->galleryHome()->first() : null;
    }

    /**
     * @return mixed
     */
    protected function pollsIndex()
    {
        return $this->_poll->pollsHome()->count() != 0 ? $this->_poll->pollsHome() : null;
    }

    /**
     * @return Builder|Model|object|null
     */
    protected function breakingNews()
    {
        return $this->_articles->query()->where('is_breaking', true)->first();
    }

    /**
     * @return Builder[]|Collection
     */
    protected function mostCommented()
    {
        return $this->_articles->query()->withCount('comments')->take(3)->orderBy('comments_count', 'DESC')->get();
    }

    /**
     * @return Builder[]|Collection
     */
    protected function mostLikes()
    {
        return $this->_articles->query()->withCount('reactions')->take(3)->orderBy('reactions_count', 'DESC')->get();
    }

    /**
     * @return Builder[]|Collection
     */
    protected function mostSeen()
    {
        return $this->_articles->query()->orderBy('views', 'DESC')->take(3)->get();
    }
}
