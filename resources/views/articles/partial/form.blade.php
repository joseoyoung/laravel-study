<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
  <label for="title">{{ trans('forum.title') }}</label>
  <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $article->title) }}" required/>
  {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'; style-src 'self'; ">
{{-- <div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
  <label for="tags">{{ trans('forum.tags') }}</label>
  <select class="form-control select2-multiple" name="tags[]" id="tags" multiple="multiple">
    @foreach($allTags as $tag)
      <option value="{{ $tag->id }}" {{ in_array($tag->id, $article->tags->pluck('id')->toArray()) ? 'selected="selected"' : '' }}>{{ $tag->name }}</option>
    @endforeach
  </select>
  {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
</div> --}}
@include('articles.partial.tagselector')
{{-- <input type="text" name="tagAdd" id="tagAdd" class="form-control" value=""/> --}}

<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
  <a href="#" class="help-block pull-right hidden-xs" id="md-caller">
    {{-- <small>{!! icon('preview') !!} {{ trans('common.cheat_sheet') }}</small> --}}
  </a>
  <label for="content">{{ trans('forum.content') }}</label>
  <textarea name="content" id="content" class="form-control forum__content">{{ old('content', $article->content) }}</textarea>
  {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
  <div class="font-weight-light">{{ old('content', __('common.markdown_preview')) }}</div>
</div>
{{-- <textarea id="mytextarea">Hello, World!</textarea> --}}
<div class="form-group">
  <label for="my-dropzone">
    Files
  <small class="text-muted">
      Click to attach files <i class="fa fa-chevron-down"></i>
    </small>
    <small class="text-muted" style="display: none;">
      Click to close pane <i class="fa fa-chevron-up"></i>
    </small>
  </label>
  <div id="my-dropzone" class="dropzone"></div>
  <div id="template-preview">
    <div class="dz-preview dz-file-preview well" id="dz-preview-template">
            <div class="dz-details">
                    <div class="dz-filename"><span data-dz-name></span></div>
                    <div class="dz-size" data-dz-size></div>
                    <div class="dz-image"><img data-dz-thumbnail/></div>
            </div>
            
            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
            {{-- <div><img  alt="Click me to remove the file." data-dz-remove /></div> --}}
            {{-- <div class="dz-success-mark"><span></span></div>
            <div class="dz-error-mark"><span></span></div>
            <div class="dz-error-message"><span data-dz-errormessage></span></div> --}}
    </div>
</div>
</div>

<div class="form-group">
  <div class="checkbox">
    <label>
      <input type="checkbox" name="notification" {{ $article->notification ? 'checked="checked"': ''}}>
      {{ trans('forum.notification') }}
    </label>
  </div>
</div>

@if ($currentUser and $currentUser->isAdmin())
  <div class="form-group">
    <div class="checkbox">
      <label>
        <input type="checkbox" name="pin" {{ $article->pin ? 'checked="checked"': ''}}>
        {{ trans('forum.pin') }}
      </label>
    </div>
  </div>
@endif

@include('layouts.partial.markdown')

@section('script')
  <script>
    var form = $("form.form__forum").first(),
        dropzone  = $("div.dropzone"),
        dzControl = $("label[for=my-dropzone]>small");

    dzControl.on("click", function(e) {
      dropzone.fadeToggle(0);
      dzControl.fadeToggle(0);
    });

    /* Activate select2 for a nicer tag selector UI */
    $("select#tags").select2({
      placeholder: "{{ trans('forum.tags_help') }}",
      maximumSelectionLength: 3,
      tags: true,
      // createTag: function(newTag) {
      //   return {
      //       id: 'new:' + newTag.term,
      //       text: newTag.term + ' (new)'
      //   };
    // }
    });
    // $("#tags").select2();

    /* Dropzone Related */
    Dropzone.autoDiscover = false;

    /* Instantiate Dropzone for a nicer attachment upload UI */
    var myDropzone = new Dropzone("div#my-dropzone", {
      url: "/files",
      params: {
        _token: '{{ csrf_token() }}',
        articleId: "{{ $article->id }}"
      },
      dictDefaultMessage: "<div class=\"text-center text-muted\">" +
      "<h2>{{ trans('forum.msg_dropfile') }}</h2>" +
      "<p>{{ trans('forum.msg_dropfile_sub') }}</p></div>",
      acceptedFiles: ".jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF,.pdf,.pub",
      previewTemplate: document.getElementById('dz-preview-template').innerHTML,
      // thumbnail: function(file, dataUrl) {
      //   // Display the image in your file.previewElement
      // },
      addRemoveLinks: true
    });

    var handleImage = function(objId, imgUrl, remove) {
      var caretPos = document.getElementById(objId).selectionStart;
      var textAreaTxt = $("#" + objId).val();
      var txtToAdd = "![](" + imgUrl + ")";

      if (remove) {
// Todo write remove logic
//        var pattern = new RegExp(txtToAdd);
//
//        if (pattern.test(textAreaTxt)) {
//          textAreaTxt.match(pattern);
//        }
        return;
      }

      $("#" + objId).val(
        textAreaTxt.substring(0, caretPos) +
        txtToAdd +
        textAreaTxt.substring(caretPos)
      );
    };

    myDropzone.on("success", function(file, data) {
      // File upload success handler
    // 1. make a hidden input to give hint to the server side what has been attached
      // 2. if the attached file was image type, call handleImage();
      file._id = data.id;
      file._name = data.name;
      file._url = data.url;

      // alert(file);

      $("<input>", {
        type: "hidden",
        name: "attachments[]",
        class: "attachments",
        value: data.id
      }).appendTo(form);

      if (/^image/.test(data.type)) {
        handleImage('content', data.url);
      }
    });

    myDropzone.on("removedfile", function(file) {
      // When user removed a file from the Dropzone UI,
      // the image will be disappear in DOM level, but not in the service
      // The following code send ajax request to the server to handle that situation
      $.ajax({
        type: "POST",
        url: "/files/" + file._id,
        data: {
          _method: "DELETE", 
          _token: '{{ csrf_token() }}'
        }, 
        success: function(file, data){
          handleImage('content', file._url, true);
        }
      });
    });

    tinymce.init({
      selector: '#content',
      plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
      imagetools_cors_hosts: ['picsum.photos'],
      menubar: 'file edit view insert format tools table help',
      toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor preview code | ltr rtl',
      toolbar_sticky: true,
      autosave_ask_before_unload: true,
      autosave_interval: "30s",
      autosave_prefix: "{path}{query}-{id}-",
      autosave_restore_when_empty: false,
      autosave_retention: "2m",
      image_advtab: true,
      paste_data_images: true,
      content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tiny.cloud/css/codepen.min.css'
      ],
      link_list: [
        // { title: 'My page 1', value: 'http://www.tinymce.com' },
        // { title: 'My page 2', value: 'http://www.moxiecode.com' }
      ],
      image_list: [
        // { title: 'My page 1', value: 'http://www.tinymce.com' },
        // { title: 'My page 2', value: 'http://www.moxiecode.com' }
      ],
      image_class_list: [
        // { title: 'None', value: '' },
        // { title: 'Some class', value: 'class-name' }
      ],
      importcss_append: true,
      height: 400,
      // file_picker_callback: function (callback, value, meta) {
      //   /* Provide file and text for the link dialog */
      //   if (meta.filetype === 'file') {
      //     callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
      //   }

      //   /* Provide image and alt text for the image dialog */
      //   if (meta.filetype === 'image') {
      //     callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
      //   }

      //   /* Provide alternative source and posted for the media dialog */
      //   if (meta.filetype === 'media') {
      //     callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
      //   }
      // },
      /* we override default upload handler to simulate successful upload*/
      image_title: true,
      // automatic_uploads: true,
      // images_upload_url: '{{url("/files/")}}',
      // file_picker_types: 'image',
      paste_data_images: true,
      automatic_uploads: true,
      images_upload_handler: function (blobInfo, success, failure)
      {
          // no upload, just return the blobInfo.blob() as base64 data
          success("data:" + blobInfo.blob().type + ";base64," + blobInfo.base64());
      },
      file_picker_callback: function(cb, value, meta) {
 
      },
      relative_urls: false,
      remove_script_host: false,
      file_picker_types: 'image',

  // file_picker_callback: function(cb, value, meta) {
  //   var input = document.createElement('input');
  //   input.setAttribute('type', 'file');
  //   input.setAttribute('accept', 'image/*');

  //   input.onchange = function() {
  //     var file = this.files[0];
  //     var reader = new FileReader();
  //     reader.onload = function () {
  //       var id = 'blobid' + (new Date()).getTime();
  //       var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
  //       var base64 = reader.result.split(',')[1];
  //       var blobInfo = blobCache.create(id, file, base64);
  //       blobCache.add(blobInfo);
  //       cb(blobInfo.blobUri(), { title: file.name });
  //     };
  //     reader.readAsDataURL(file);
  //   };
  //   input.click();
  // },
  // images_upload_url: '/files',
  // images_reuse_filename: true,
  // language: 'ko_KR',
  // setup: function (editor) {
  //   geditor = editor;
  //   editor.on('change', function (e) {
  //     editor.save();
  //   });

  // },

      templates: [
            { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
        { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
        { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
      ],
      template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
      template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
      image_caption: true,
      quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
      noneditable_noneditable_class: "mceNonEditable",
      toolbar_drawer: 'sliding',
      contextmenu: "link image imagetools table",
    });
    //   plugins : 'advlist autolink link image imagetools lists charmap print preview emoticons table visualblocks code media ',
    //   toolbar: [
    //     'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | table | fontsizeselect | link unlink image | print preview visualblocks fullscreen code media | forecolor backcolor emoticons'
    //   ],
    //   height: 400,
    //   branding: false,
    //   image_title: true,
    //   file_picker_types: 'image',
    //   // file_picker_callback: function(cb, value, meta) {
    //   //   var input = document.createElement('input');
    //   //   input.setAttribute('type', 'file');
    //   //   input.setAttribute('accept', 'image/*');
    //   //   input.onchange = function() {

    //   //     var file = this.files[0];
    //   //     var reader = new FileReader();
    //   //     reader.onload = function () {
    //   //       var id = 'blobid' + (new Date()).getTime();
    //   //       var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
    //   //       var base64 = reader.result.split(',')[1];
    //   //       var blobInfo = blobCache.create(id, file, base64);
    //   //       blobCache.add(blobInfo);
    //   //       cb(blobInfo.blobUri(), { title: file.name });
    //   //     };
    //   //     reader.readAsDataURL(file);
    //   //     };
    //   //     input.click();
    //   //     },
    //   //     images_upload_url: '/dashboard/create/upload-cdimg',
    //   //     images_reuse_filename: true,
    //   //     language: 'ko_KR',
    //   //     setup: function (editor) {
    //   //     geditor = editor;
    //   //     editor.on('change', function (e) {
    //   //     editor.save();
    //   //     });
    //   //   }
    // });
  </script>
@endsection
