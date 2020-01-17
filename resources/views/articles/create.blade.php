@extends('layouts.app')

@section('content')
  
  <div class="row justify-content-center">
    <div class="card-body  col-md-7">
        <div class="page-header">
            <h4>
              {!! icon('forum', null, 'margin-right:1rem') !!}
              <a href="{{ route('articles.index') }}">
                {{ trans('forum.title_forum') }}
              </a>
              <small> / </small>
              {{ trans('forum.create') }}
            </h4>
          </div>
      <form action="{{ route('articles.store') }}" method="POST" role="form" class="form__forum">
        {!! csrf_field() !!}

        @include('articles.partial.form')

        <div class="form-group">
          <p class="text-center">
            <a href="{{ route('articles.create') }}" class="btn btn-default">
              {!! icon('reset') !!} {{ trans('common.reset') }}
            </a>
            <button type="submit" class="btn btn-primary">
              {!! icon('plane') !!} {{ trans('common.post') }}
            </button>
          </p>
        </div>
      </form>
    </div>
@endsection



