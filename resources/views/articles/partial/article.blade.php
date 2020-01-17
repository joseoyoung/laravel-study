<div class="media"  style="padding:1em; margin:0.5em; border:1px solid #90909033;">
  @include('users.partial.avatar', ['user' => $article->author, 'size' => 64])

  <div class="media-body" style="padding:1em;">
    <h4 class="media-heading">
      {{-- @if ($article->isNotice())
        <span style="margin-right: 1rem;" title="{{ trans('common.notice') }}">
          {!! icon('pin', false) !!}
        </span>
      @endif --}}

      <a href="{{ route('articles.show', $article->id) }}">
        {{ $article->title }}</a>

        @php
            $user = Auth::user();
            // dd($article->isAuthor());
            // dd($user); exit;
        @endphp
        {{-- @if($user->hasRole('admin') or $article->isAuthor())
            <button type="button" class="btn btn-danger pull-right btn__delete" >
              {!! icon('delete') !!} {{ trans('common.delete') }}
            </button>
            <a href="{{route('articles.edit', $article->id)}}" class="btn btn-info pull-right" style="margin:1px">
              {!! icon('pencil') !!} {{ trans('common.edit') }}
            </a>
        @endif --}}

        @if ($commentCount = $article->comments->count())
          <span class="badge pull-right">
            {!! icon('comments') !!} {{ $commentCount }}
          </span>
        @endif

        @if ($article->solution_id)
          <span class="badge pull-right" title="{{ trans('forum.solved') }}">
            {!! icon('check', false) !!}
          </span>
        @endif

        @if ($attachmentsCount = $article->attachments->count())
          <span class="badge pull-right">
            {!! icon('clip') !!} {{ $attachmentsCount }}
          </span>
        @endif
      {{-- </a> --}}
    </h4>

    <p class="text-muted">
      <a href="{{ gravatar_profile_url($article->author->email) }}" style="margin-right: 1rem;">
        {!! icon('user') !!} {{ $article->author->name }}
      </a>

      <span style="margin-right: 1rem;">
        {!! icon('clock') !!} {{ $article->created_at->diffForHumans() }}
      </span>

      <span style="margin-right: 1rem;">
        {!! icon('view_count') !!} {{ number_format($article->view_count) }}
      </span>
    </p>

    @include('tags.partial.list', ['tags' => $article->tags])
  </div>
</div>