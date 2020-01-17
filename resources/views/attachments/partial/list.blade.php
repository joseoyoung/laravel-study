@if ($attachments->count())
  <ul class="tags__forum">
    @foreach ($attachments as $attachment)
      <li class="label label-default">
        {!! icon('download') !!}
        <a href="/attachments/{{ $attachment->name }}">{{ $attachment->name }}</a>
        @if ($user->hasRole('admin') or $article->isAuthor())
          <form action="{{ route('files.destroy', $attachment->id) }}" method="post" style="display: inline;">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
            <button type="submit" style="color: #fff; background-color: #e3342f; border-color: #e3342f; text-align: center; vertical-align: middle; 
            border-radius: 0.25rem; padding: 0.1rem 0.5rem;">x</button>
          </form>
        @endif
      </li>
      {{-- <img src="/attachments/{{ $attachment->name }}"/><br/> --}}
    @endforeach
  </ul>
@endif