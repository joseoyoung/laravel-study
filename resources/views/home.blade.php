@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    <br>
                    {{-- <div id="calendar"> --}}

                        <!-- 일자 클릭시 메뉴오픈 -->
                        <div id="contextMenu" class="dropdown clearfix">
                            <ul class="dropdown-menu dropNewEvent" role="menu" aria-labelledby="dropdownMenu"
                                style="display:block;position:static;margin-bottom:5px;">
                                <li><a tabindex="-1" href="#">카테고리1</a></li>
                                <li><a tabindex="-1" href="#">카테고리2</a></li>
                                <li><a tabindex="-1" href="#">카테고리3</a></li>
                                <li><a tabindex="-1" href="#">카테고리4</a></li>
                                <li class="divider"></li>
                                <li><a tabindex="-1" href="#" data-role="close">Close</a></li>
                            </ul>
                        </div>
                
                        <div id="wrapper">
                            <div id="loading"></div>
                            <div id="calendar"></div>
                        </div>
                
                
                        <!-- 일정 추가 MODAL -->
                        <div class="modal fade" tabindex="-1" role="dialog" id="eventModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label class="col-xs-4" for="edit-allDay">하루종일</label>
                                                <input class='allDayNewEvent' id="edit-allDay" type="checkbox"></label>
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label class="col-xs-4" for="edit-title">일정명</label>
                                                <input class="inputModal" type="text" name="edit-title" id="edit-title"
                                                    required="required" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12"  id=''>
                                                <label class="col-xs-4" for="edit-start">시작</label>
                                                <input class="inputModal" type="text" name="edit-start" id="edit-start" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label class="col-xs-4" for="edit-end">끝</label>
                                                <input class="inputModal" type="text" name="edit-end" id="edit-end" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label class="col-xs-4" for="edit-type">구분</label>
                                                <select class="inputModal" type="text" name="edit-type" id="edit-type">
                                                    <option value="카테고리1">카테고리1</option>
                                                    <option value="카테고리2">카테고리2</option>
                                                    <option value="카테고리3">카테고리3</option>
                                                    <option value="카테고리4">카테고리4</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label class="col-xs-4" for="edit-color">색상</label>
                                                <select class="inputModal" name="color" id="edit-color">
                                                    <option value="#D25565" style="color:#D25565;">빨간색</option>
                                                    <option value="#9775fa" style="color:#9775fa;">보라색</option>
                                                    <option value="#ffa94d" style="color:#ffa94d;">주황색</option>
                                                    <option value="#74c0fc" style="color:#74c0fc;">파란색</option>
                                                    <option value="#f06595" style="color:#f06595;">핑크색</option>
                                                    <option value="#63e6be" style="color:#63e6be;">연두색</option>
                                                    <option value="#a9e34b" style="color:#a9e34b;">초록색</option>
                                                    <option value="#4d638c" style="color:#4d638c;">남색</option>
                                                    <option value="#495057" style="color:#495057;">검정색</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div style="float:left; margin-right:5px;">
                                                <label class="col-xs-4" for="edit-desc" style="">설명</label>
                                                </div>
                                                <div>
                                                <textarea rows="4" cols="50" class="inputModal" name="edit-desc"
                                                    id="edit-desc"></textarea></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer modalBtnContainer-addEvent">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                                        <button type="button" class="btn btn-primary" id="save-event">저장</button>
                                    </div>
                                    <div class="modal-footer modalBtnContainer-modifyEvent">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                                        <button type="button" class="btn btn-danger" id="deleteEvent">삭제</button>
                                        <button type="button" class="btn btn-primary" id="updateEvent">저장</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                
                        <div class="panel panel-default">
                
                            <div class="panel-heading">
                                <h3 class="panel-title">필터</h3>
                            </div>
                
                            <div class="panel-body">
                
                                <div class="col-lg-6">
                                    <label for="calendar_view">구분별</label>
                                    <div class="input-group">
                                        <select class="filter" id="type_filter" multiple="multiple">
                                            <option value="카테고리1">카테고리1</option>
                                            <option value="카테고리2">카테고리2</option>
                                            <option value="카테고리3">카테고리3</option>
                                            <option value="카테고리4">카테고리4</option>
                                        </select>
                                    </div>
                                </div>
                
                                <div class="col-lg-6">
                                    <label for="calendar_view">등록자별</label>
                                    <div class="input-group">
                                        <label class="checkbox-inline"><input class='filter' type="checkbox" value="정연"
                                                checked>정연</label>
                                        <label class="checkbox-inline"><input class='filter' type="checkbox" value="다현"
                                                checked>다현</label>
                                        <label class="checkbox-inline"><input class='filter' type="checkbox" value="사나"
                                                checked>사나</label>
                                        <label class="checkbox-inline"><input class='filter' type="checkbox" value="나연"
                                                checked>나연</label>
                                        <label class="checkbox-inline"><input class='filter' type="checkbox" value="지효"
                                                checked>지효</label>
                                    </div>
                                </div>
                
                            </div>
                        </div>
                        <!-- /.filter panel -->
                    </div>
                    <!-- /.container -->
                </div>
            </div>
        </div>
    {{-- </div> --}}
</div>
{{-- <meta http-equiv="Refresh" content="3;url=./articles"> --}}
@endsection

{{-- @push('scripts_user') --}}
@section('script')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css"/>

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css"/> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.css"/> --}}
{{-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"  rel="stylesheet"> --}}
{{-- <link rel="stylesheet" href="{{ asset('css/cal_css.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('css/cal.css') }}">
 <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
 <script type="text/javascript" src="{{ asset('js/ko.js') }}"></script>
{{-- <script src="//mugifly.github.io/jquery-simple-datetimepicker/jquery.simple-dtpicker.js"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

{{-- <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script> --}}

<script type="text/javascript" src="{{ asset('js/cal.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/cal_main.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/cal_addEvent.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/cal_editEvent.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/cal_etcSetting.js') }}"></script> --}}
<script>
</script>

    
@endsection
{{-- @endpush --}}
