<?php

namespace App\Http\Controllers;

use App\Article;
use App\Events\ArticleConsumed;
use App\Events\ModelChanged;
use App\Http\Requests\ArticlesRequest;
use App\Http\Requests\FilterArticlesRequest;
use App\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('accessible', ['except' => ['index', 'show', 'create']]);
        view()->share('allTags', \App\Tag::with('articles')->get());
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterArticlesRequest $request, $id = null)
    {
        // dd(Tag::whereSlug($id)->firstOrFail()); exit;
        $query = $id
        ? Tag::whereSlug($id)->firstOrFail()->articles()//Tag::findOrFail($id)->articles()
        : new Article;

        $query = $query->with('comments', 'author', 'tags', 'solution', 'attachments');
        
        $articles = $this->filter($request, $query)->paginate(10);

        return view('articles.index', compact('articles'));
    }

    protected function filter($request, $query)
    {
        // dd($request->input());exit;
        if ($filter = $request->input('filter')) {
            // 'f' 쿼리 스트링 필드가 있으면, 그 값에 따라 쿼리를 분기한다.
            switch ($filter) {
                case 'no_comment':
                    $query->noComment();
                    break;
                case 'not_solved':
                    $query->notSolved();
                    break;
            }
        }

        if ($keyword = $request->input('q')) {
            // 이번에도 'q' 필드가 있으면 풀텍스트 검색 쿼리를 추가한다.
            $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
            $query->whereRaw($raw, [$keyword]);
        }

        // 'sort' 필드가 있으면 사용하고, 없으면 created_at 을 기본값으로 사용한다.
        $sort = $request->input('sort', 'created_at');
        // 'order' 필드가 있으면 사용하고, 없으면 desc 를 기본값으로 사용한다.
        $direction = $request->input('order', 'desc');

        return $query->orderBy($sort, $direction);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new Article;

        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all()); exit;
        // $article = Article::create($request->all());
        // flash()->success(trans('forum.created'));

        // return redirect(route('articles.index'));

        // print_r($request->input('tags'));
        // dd(Tag::whereSlug($request->input('tags'))); 
        // $tagCh = (Tag::whereId($request->input('tags'))->get('slug'));
        // echo "<pre>";
        // print_r($tagCh);
        // exit; 

        $tags = $this->addTag($request->input('tags'));

        // $newTag = Tag::create(['name' => substr($tagId, 4)]);
        // if ( ! $request->has('tags'))
        // {
        //     $article->tags()->detach();
        //     return;
        // }

        // $allTagIds = array();

        // foreach ($request->input('tags') as $tagId)
        // {
        //     if(! \App\Tag::whereId($tagId)->exists()){

        //         $newTag = \App\Tag::create(['name' => "{$tagId}", 'slug' => "{$tagId}"]);
        //         $newTag = \App\Tag::whereSlug($tagId)->get('id');
        //         // echo($newTag[0]['id']);
        //         $allTagIds[] = $newTag[0]['id'];
        //     }
        //     else{ 
        //         $allTagIds[] = $tagId;
        //     }
           
        // }
        // dd($allTagIds);exit;
        // $article->tags()->sync($allTagIds);
        
        $payload = array_merge($request->except('_token'), [
            'notification' => $request->has('notification')
        ]);

        $article = $request->user()->articles()->create($payload);
        
        // $article->tags()->sync($allTagIds);
        $article->tags()->sync($tags);
        flash()->success(trans('forum.created'));

        if ($request->has('attachments')) {
            $attachments = \App\Attachment::whereIn('id', $request->input('attachments'))->get();
            $attachments->each(function ($attachment) use ($article) {
                $attachment->article()->associate($article);
                $attachment->save();
            });
        }

        // event(new ModelChanged(['articles', 'tags']));

        return redirect(route('articles.index'));
    }

    protected function addTag($tags){
        $allTagIds = array();

        foreach ($tags as $tagId)
        {
            if(! \App\Tag::whereId($tagId)->exists()){
                $newTag = \App\Tag::create(['name' => "{$tagId}", 'slug' => "{$tagId}"]);
                $newTag = \App\Tag::whereSlug($tagId)->get('id');
                // echo($newTag[0]['id']);
                $allTagIds[] = $newTag[0]['id'];
            }
            else{ 
                $allTagIds[] = $tagId;
            }
        }
        return $allTagIds;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::with('comments', 'author', 'tags')->findOrFail($id);
        $commentsCollection = $article->comments()->with('replies', 'author')->whereNull('parent_id')->latest()->get();
    
        return view('articles.show', [
            'article'         => $article,
            'comments'        => $commentsCollection,
            'commentableType' => Article::class,
            'commentableId'   => $article->id
        ]);
        // $article = Article::with('comments', 'author', 'tags')->findOrFail($id);
        // // $user = config('roles.models.defaultUser')::find($id);
        // // view()->share('user', $user);

        // return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $article = Article::findOrFail($id);
        // $article->update($request->except('_token', '_method'));
        // flash()->success(trans('forum.updated'));

        // return redirect(route('articles.index'));

        $tags = $this->addTag($request->input('tags'));

        $payload = array_merge($request->except('_token'), [
            'notification' => $request->has('notification')
        ]);
    
        $article = Article::findOrFail($id);
        $article->update($payload);
        // $article->tags()->sync($request->input('tags'));
        $article->tags()->sync($tags);
        flash()->success(trans('forum.updated'));
    
        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     Article::findOrFail($id)->delete();
    //     flash()->success(trans('forum.deleted'));

    //     return redirect(route('articles.index'));
    // }
    public function destroy($id)
    {
        $article = Article::with('attachments', 'comments')->findOrFail($id);

        foreach($article->attachments as $attachment) {
            \File::delete(attachment_path($attachment->name));
        }

        $article->attachments()->delete();
        $article->comments->each(function($comment) { // foreach 로 써도 된다.
            app(\App\Http\Controllers\CommentsController::class)->recursiveDestroy($comment);
        });
        $article->delete();

        flash()->success(trans('forum.deleted'));

        return redirect(route('articles.index'));
    }
        
}
