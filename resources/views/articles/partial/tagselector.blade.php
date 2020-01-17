
<div class="form-group">
    <label for="tags">{{ trans('forum.tags') }}</label>
    <select class="form-control" multiple="multiple" id="tags" name="tags[]">
        @foreach($allTags as $tag)
            <option value="{{ $tag->id }}" {{ in_array($tag->id, $article->tags->pluck('id')->toArray()) ? 'selected="selected"' : '' }}>{{ $tag->name }}</option>
        @endforeach
    </select>
    {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
</div>






{{-- <div class="form-group">
    {!! Form::label('tags', trans('forum.tags')) !!}
    {!! Form::select('tags', $allTags, old('tags', $article->tags->lists('id')->toArray()), ['multiple' => 'multiple', 'class' => 'form-control']) !!}}
    {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
</div> --}}