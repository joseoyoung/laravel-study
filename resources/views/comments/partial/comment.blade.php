<div class="media media__item " data-id="{{ $comment->id }}">

  @include('users.partial.avatar', ['user' =>  $comment->author, 'author' => $commentAuthor])
  @php
      // dd($commentAuthor);
      // foreach ($commentAuthor as $ca => $a) {
      //   print_r($a->name);
      // }
  @endphp         
{{-- <p> {{ $commentAuthor }}</p> --}}
  <div class="media-body">
    @if($user and ($user->hasRole('admin') or ($comment->author_id == $user->id)))
      @include('comments.partial.control')
    @endif

    <h4 class="media-heading">
      <!-- Gravatar and comment generated time are presented here ... -->
    </h4>
    <p>{!! markdown($comment->content) !!}</p>
    @php
        // echo $user->id;
        // dd($comment->content); exit;
        // // dd($article->isAuthor());
        // exit;
    @endphp

    @if ($user)
   
    @endif

    @if($user and ($user->hasRole('admin') or ($comment->author_id == $user->id)))
      @include('comments.partial.edit')
    @endif

    @if($user)
      @include('comments.partial.create', ['parentId' => $comment->id])
      <br>
    @endif

    @forelse ($comment->replies as $reply)
      @include('comments.partial.comment', ['comment'  => $reply])
    @empty
    @endforelse
  </div>
</div>

@section('script')
    <script>
      $("button.btn__vote").on("click", function(e) {
      var self = $(this),
          commentId = $(this).closest(".media__item").data("id");

      $.ajax({
        type: "POST",
        url: "/comments/" + commentId + "/vote",
        data: {
          vote: self.data("vote"), _token: '{{ csrf_token() }}'
        },
        success: function(data) {
          self.find("span").html(data.value);
          self.attr("disabled", "disabled");
          self.siblings().attr("disabled", "disabled");
        },
        error: function() {
          alert("{{ trans('common.msg_whoops') }}");
          // flash("danger", "{{ trans('common.msg_whoops') }}", 2500);
        }
      });
    });

    $("button.btn__pick").on("click", function(e) {
      // Update Best Answer against the Article model
      var articleId = $("#article__article").data("id"),
          commentId = $(this).closest(".media__item").data("id");

      if (confirm("{{ trans('forum.msg_pick_best') }}")) {
        $.ajax({
          type: "POST",
          url: "/articles/" + articleId + "/pick",
          data: {
            _method: "PUT",
            solution_id: commentId,
            _token: '{{ csrf_token() }}'
          },
          success: function() {
          alert("{{ trans('common.updated') }}");
            // flash("success", "{{ trans('common.updated') }} {{ trans('common.msg_reload') }}", 1500);
            // reload(2000);
          }
        });
      }
    });

    $("#md-modal").on("click", function(e) {
      // Make an overlay, explaining markdown syntax
      e.preventDefault();
      $("#md-modal").modal();
      return false;
    });
  </script>
    </script>
@endsection

{{-- @section('script')
  @parent
  <script>
    $("button.btn__reply").on("click", function(e) {
      // Toggle reply form
      var el__create = $(this).closest(".media__item").find(".media__create").first(),
          el__edit = $(this).closest(".media__item").find(".media__edit").first();

      el__edit.hide("fast");
      el__create.toggle("fast").end().find('textarea').focus();
    });

    $("a.btn__edit").on("click", function(e) {
      // Toggle edit form
      var el__create = $(this).closest(".media__item").find(".media__create").first(),
          el__edit = $(this).closest(".media__item").find(".media__edit").first();

      el__create.hide("fast");
      el__edit.toggle("fast").end().find('textarea').first().focus();
    });

    $("a.btn__delete").on("click", function(e) {
      // Make a delete request to the server
      var commentId = $(this).closest(".media__item").data("id");

      if (confirm("{{ trans('forum.msg_delete_comment') }}")) {
        $.ajax({
          type: "POST",
          url: "/comments/" + commentId,
          data: {
            _method: "DELETE", _token: '{{ csrf_token() }}'
          },
          success: function() {
            alert("{{ trans('common.deleted') }}");
          // flash("success", "{{ trans('common.deleted') }} {{ trans('common.msg_reload') }}", 1500);
          reload(2000);
          }
        });
      }
    });

    $("button.btn__vote").on("click", function(e) {
      var self = $(this),
          commentId = $(this).closest(".media__item").data("id");

      $.ajax({
        type: "POST",
        url: "/comments/" + commentId + "/vote",
        data: {
          vote: self.data("vote"), _token: '{{ csrf_token() }}'
        },
        success: function(data) {
          self.find("span").html(data.value);
          self.attr("disabled", "disabled");
          self.siblings().attr("disabled", "disabled");
        },
        error: function() {
          alert("{{ trans('common.msg_whoops') }}");
          // flash("danger", "{{ trans('common.msg_whoops') }}", 2500);
        }
      });
    });

    $("button.btn__pick").on("click", function(e) {
      // Update Best Answer against the Article model
      var articleId = $("#article__article").data("id"),
          commentId = $(this).closest(".media__item").data("id");

      if (confirm("{{ trans('forum.msg_pick_best') }}")) {
        $.ajax({
          type: "POST",
          url: "/articles/" + articleId + "/pick",
          data: {
            _method: "PUT",
            solution_id: commentId,
            _token: '{{ csrf_token() }}'
          },
          success: function() {
          alert("{{ trans('common.updated') }}");
            // flash("success", "{{ trans('common.updated') }} {{ trans('common.msg_reload') }}", 1500);
            // reload(2000);
          }
        });
      }
    });

    $("#md-modal").on("click", function(e) {
      // Make an overlay, explaining markdown syntax
      e.preventDefault();
      $("#md-modal").modal();
      return false;
    });
  </script>
@endsection --}}
