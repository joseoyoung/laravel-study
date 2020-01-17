@extends('layouts.app')

@section('content')
<div class="row justify-content-center ">  
  <div class="row container__forum col-md-9">
  {{-- <div class="page-header">
    <h4>
      {!! icon('forum', null, 'margin-right:1rem') !!}
      <a href="{{ route('articles.index') }}">
        {{ trans('forum.title_forum') }}
      </a>
      <small> / </small>
      {{ $article->title }}
    </h4>
  </div> --}}

  
    <div class="col-md-3 sidebar__forum">
      {{-- <aside> --}}
        @include('articles.partial.search')<br/>
        @include('tags.partial.index')
      {{-- </aside> --}}
    </div>

   
    <div class="col-md-9">
      <article id="article__article" data-id="{{ $article->id }}"> 
        @php
            $user = Auth::user();
            // dd($article->isAuthor());
        @endphp
        @include('articles.partial.article', ['article' => $article])

        @include('attachments.partial.list', ['attachments' => $article->attachments])
        
        <p>
          {!! markdown($article->content) !!}
        </p>

        <div class="divider">&nbsp;</div>

        @if ($article->solution)
          @include('comments.partial.best', ['comment' => $article->solution])
        @endif
       
        {{-- @php
         if(Auth::user()->hasRole( 'admin'))
         echo "관리자임";
        //  dd($currentUser->isAdmin()); 
        if(Auth::user()->id == $article->id)
          echo "글 주인임";
        //  exit;   
        @endphp --}}
        @if($user->hasRole('admin') or $article->isAuthor())
        <div class="text-center">
            <button type="button" class="btn btn-danger btn__delete">
              {!! icon('delete') !!} {{ trans('common.delete') }}
            </button>
            <a href="{{route('articles.edit', $article->id)}}" class="btn btn-info">
              {!! icon('pencil') !!} {{ trans('common.edit') }}
            </a>
          </form>
        </div>
        @endif
        {{-- @if (Auth::user()->hasRole( 'admin') or Auth::user()->id == $article->id)
        <div class="text-center">
            <button type="button" class="btn btn-danger btn__delete">
              {!! icon('delete') !!} {{ trans('common.delete') }}
            </button>
            <a href="{{route('articles.edit', $article->id)}}" class="btn btn-info">
              {!! icon('pencil') !!} {{ trans('common.edit') }}
            </a>
          </form>
        </div>
        @endif --}}
      </article>

      <hr class="divider"/> 

      <article>
        {{-- @include('comments.index', [
          'solved' => $article->solution,
          'owner'  => (Auth::user()->hasRole('admin') or Auth::user()->id == $article->id)
        ]) --}} 
        @include('comments.index')
        {{-- @include('comments.index', [
          'solved' => $article->solution,
          'owner'  => $user && $article->isAuthor()
        ]) --}}
      </article>
    </div>

    @include('layouts.partial.markdown')
  {{-- </div> --}}
</div>
@endsection

@section('script')
  <script>

    $("button.btn__delete").on("click", function(e) {
      var articleId = $("#article__article").data("id");
      
      if (confirm("{{ trans('common.confirm_delete') }}")) {
        $.ajax({
          type: "POST",
          url: "/articles/" + articleId,
          data: {
            _method: "DELETE", 
            _token: '{{ csrf_token() }}'
          },
          success: function() {
            // flash("success", "{{ trans('common.deleted') }} {{ trans('common.msg_reload') }}", 1500);
            alert("{{ trans('common.deleted') }}");

            var timer = setTimeout(function () {
              window.location.href = '/articles';
            }, 2000);
          }
        });
      }
    });
  </script>
@endsection