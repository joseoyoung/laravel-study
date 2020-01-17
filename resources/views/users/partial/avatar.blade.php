@php
$size = isset($size) ? $size : 48;
// $user = $commentAuthor;
@endphp

{{-- {{$author->email ?? ''}} --}}
{{-- <div>
{{$author ?? ''}}
</div> --}}
<br>
@if (isset($author) and $author)
  <a class="pull-left hidden-xs hidden-sm" href="{{ gravatar_profile_url($author->email)}}">
    <img class="media-object img-thumbnail " src="{{ gravatar_url($author->email, $size) }}" alt="{{ $author->name ?? '' }}" style="margin:0.5em; margin-bottom:0;"> <br>
    {{-- <p> {{ $commentAuthor }}</p> --}}
   <div  class="text-center"><small> {{$author->name}} </small></div>
  </a>
@else
  <a class="pull-left hidden-xs hidden-sm" href="{{ gravatar_profile_url('john@example.com') }}">
    <img class="media-object img-thumbnail" src="{{ gravatar_url('john@example.com', $size) }}" alt="Unknown User" style="margin:1em;">
  </a>
@endif