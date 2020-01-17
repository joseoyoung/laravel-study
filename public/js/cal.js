$(document).ready(function(){

  //main.js 

  var draggedEventIsAllDay;
  var activeInactiveWeekends = true;

  function getDisplayEventDate(event) {

    var displayEventDate;

    if (event.allDay == false) {
      var startTimeEventInfo = moment(event.start).format('HH:mm');
      var endTimeEventInfo = moment(event.end).format('HH:mm');
      displayEventDate = startTimeEventInfo + " - " + endTimeEventInfo;
    } else {
      displayEventDate = "하루종일";
    }

    return displayEventDate;
  }

  function filtering(event) {
    var show_username = true;
    var show_type = true;

    var username = $('input:checkbox.filter:checked').map(function () {
      return $(this).val();
    }).get();
    var types = $('#type_filter').val();

    show_username = username.indexOf(event.username) >= 0;

    if (types && types.length > 0) {
      if (types[0] == "all") {
        show_type = true;
      } else {
        show_type = types.indexOf(event.type) >= 0;
      }
    }

    return show_username && show_type;
  }

  function calDateWhenResize(event) {

    var newDates = {
      startDate: '',
      endDate: ''
    };

    if (event.allDay) {
      newDates.startDate = moment(event.start._d).format('YYYY-MM-DD');
      newDates.endDate = moment(event.end._d).subtract(1, 'days').format('YYYY-MM-DD');
    } else {
      newDates.startDate = moment(event.start._d).format('YYYY-MM-DD HH:mm');
      newDates.endDate = moment(event.end._d).format('YYYY-MM-DD HH:mm');
    }

    return newDates;
  }

  function calDateWhenDragnDrop(event) {
    // 드랍시 수정된 날짜반영
    var newDates = {
      startDate: '',
      endDate: ''
    }

    //하루짜리 all day
    if (event.allDay && event.end === null) {
      newDates.startDate = moment(event.start._d).format('YYYY-MM-DD');
      newDates.endDate = newDates.startDate;
    }

    //2일이상 all day
    else if (event.allDay && event.end !== null) {
      newDates.startDate = moment(event.start._d).format('YYYY-MM-DD');
      newDates.endDate = moment(event.end._d).subtract(1, 'days').format('YYYY-MM-DD');
    }

    //all day가 아님
    else if (!event.allDay) {
      newDates.startDate = moment(event.start._d).format('YYYY-MM-DD HH:mm');
      newDates.endDate = moment(event.end._d).format('YYYY-MM-DD HH:mm');
    }

    return newDates;
  }


  var calendar = $('#calendar').fullCalendar({

    eventRender: function (event, element, view) {

      //일정에 hover시 요약
      element.popover({
        title: $('<div />', {
          class: 'popoverTitleCalendar',
          text: event.title
        }).css({
          'background': event.backgroundColor,
          'color': event.textColor
        }),
        content: $('<div />', {
            class: 'popoverInfoCalendar'
          }).append('<p><strong>등록자:</strong> ' + event.username + '</p>')
          .append('<p><strong>구분:</strong> ' + event.type + '</p>')
          .append('<p><strong>시간:</strong> ' + getDisplayEventDate(event) + '</p>')
          .append('<div class="popoverDescCalendar"><strong>설명:</strong> ' + event.description + '</div>'),
        delay: {
          show: "800",
          hide: "50"
        },
        trigger: 'hover',
        placement: 'top',
        html: true,
        container: 'body'
      });

      return filtering(event);

    },

    //주말 숨기기 & 보이기 버튼
    customButtons: {
      viewWeekends: {
        text: '주말',
        click: function () {
          activeInactiveWeekends ? activeInactiveWeekends = false : activeInactiveWeekends = true;
          $('#calendar').fullCalendar('option', {
            weekends: activeInactiveWeekends
          });
        }
      }
    },

    header: {
      left: 'today, prevYear, nextYear, viewWeekends',
      center: 'prev, title, next',
      right: 'month,agendaWeek,agendaDay,listWeek'
    },
    views: {
      month: {
        columnFormat: 'dddd'
      },
      agendaWeek: {
        columnFormat: 'M/D ddd',
        titleFormat: 'YYYY년 M월 D일',
        eventLimit: false
      },
      agendaDay: {
        columnFormat: 'dddd',
        eventLimit: false
      },
      listWeek: {
        columnFormat: ''
      }
    },

    /* ****************
    *  일정 받아옴 
    * ************** */
    events: function (start, end, timezone, callback) {
      $.ajax({
        type: "get",
        url: "js/data.json",
        data: {
          // 실제 사용시, 날짜를 전달해 일정기간 데이터만 받아오기를 권장
        },
        success: function (response) {
          // alert(JSON.stringify(start));
          // alert(JSON.stringify(end));
          // alert(JSON.stringify(timezone));
          var fixedDate = response.map(function (array) {
            if (array.allDay && array.start !== array.end) {
              // 이틀 이상 AllDay 일정인 경우 달력에 표기시 하루를 더해야 정상출력
              array.end = moment(array.end).add(1, 'days');
            }
            return array;
          })
          callback(fixedDate);
          alert(JSON.stringify(fixedDate));
          // console.log();
        }
      });
    },

    eventAfterAllRender: function (view) {
      if (view.name == "month") {
        $(".fc-content").css('height', 'auto');
      }
    },

    //일정 리사이즈
    eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
      $('.popover.fade.top').remove();

      /** 리사이즈시 수정된 날짜반영
       * 하루를 빼야 정상적으로 반영됨. */
      var newDates = calDateWhenResize(event);

      //리사이즈한 일정 업데이트
      $.ajax({
        type: "get",
        url: "",
        data: {
          //id: event._id,
          //....
        },
        success: function (response) {
          alert('수정: ' + newDates.startDate + ' ~ ' + newDates.endDate);
        }
      });

    },

    eventDragStart: function (event, jsEvent, ui, view) {
      draggedEventIsAllDay = event.allDay;
    },

    //일정 드래그앤드롭
    eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {
      $('.popover.fade.top').remove();

      //주,일 view일때 종일 <-> 시간 변경불가
      if (view.type === 'agendaWeek' || view.type === 'agendaDay') {
        if (draggedEventIsAllDay !== event.allDay) {
          alert('드래그앤드롭으로 종일<->시간 변경은 불가합니다.');
          location.reload();
          return false;
        }
      }

      // 드랍시 수정된 날짜반영
      var newDates = calDateWhenDragnDrop(event);

      //드롭한 일정 업데이트
      $.ajax({
        type: "get",
        url: "",
        data: {
          //...
        },
        success: function (response) {
          alert('수정: ' + newDates.startDate + ' ~ ' + newDates.endDate);
        }
      });

    },

    select: function (startDate, endDate, jsEvent, view) {

      $(".fc-body").unbind('click');
      $(".fc-body").on('click', 'td', function (e) {

        $("#contextMenu")
          .addClass("contextOpened")
          .css({
            display: "block",
            left: e.pageX,
            top: e.pageY
          });
        return false;
      });

      var today = moment();

      if (view.name == "month") {
        startDate.set({
          hours: today.hours(),
          minute: today.minutes()
        });
        startDate = moment(startDate).format('YYYY-MM-DD HH:mm');
        endDate = moment(endDate).subtract(1, 'days');

        endDate.set({
          hours: today.hours() + 1,
          minute: today.minutes()
        });
        endDate = moment(endDate).format('YYYY-MM-DD HH:mm');
      } else {
        startDate = moment(startDate).format('YYYY-MM-DD HH:mm');
        endDate = moment(endDate).format('YYYY-MM-DD HH:mm');
      }

      //날짜 클릭시 카테고리 선택메뉴
      var $contextMenu = $("#contextMenu");
      $contextMenu.on("click", "a", function (e) {
        e.preventDefault();

        //닫기 버튼이 아닐때
        if ($(this).data().role !== 'close') {
          newEvent(startDate, endDate, $(this).html());
        }

        $contextMenu.removeClass("contextOpened");
        $contextMenu.hide();
      });

      $('body').on('click', function () {
        $contextMenu.removeClass("contextOpened");
        $contextMenu.hide();
      });

    },

    //이벤트 클릭시 수정이벤트
    eventClick: function (event, jsEvent, view) {
      editEvent(event);
    },

    locale: 'ko',
    timezone: "local",
    nextDayThreshold: "09:00:00",
    allDaySlot: true,
    displayEventTime: true,
    displayEventEnd: true,
    firstDay: 0, //월요일이 먼저 오게 하려면 1
    weekNumbers: false,
    selectable: true,
    weekNumberCalculation: "ISO",
    eventLimit: true,
    views: {
      month: {
        eventLimit: 12
      }
    },
    eventLimitClick: 'week', //popover
    navLinks: true,
    // defaultDate: moment('2019-05'), //실제 사용시 삭제
    timeFormat: 'HH:mm',
    defaultTimedEventDuration: '01:00:00',
    editable: true,
    minTime: '00:00:00',
    maxTime: '24:00:00',
    slotLabelFormat: 'HH:mm',
    weekends: true,
    nowIndicator: true,
    dayPopoverFormat: 'MM/DD dddd',
    longPressDelay: 0,
    eventLongPressDelay: 0,
    selectLongPressDelay: 0
  });

  //addEvent.js

  var eventModal = $('#eventModal');

  var modalTitle = $('.modal-title');
  var editAllDay = $('#edit-allDay');
  var editTitle = $('#edit-title');
  var editStart = $('#edit-start');
  var editEnd = $('#edit-end');
  var editType = $('#edit-type');
  var editColor = $('#edit-color');
  var editDesc = $('#edit-desc');

  var addBtnContainer = $('.modalBtnContainer-addEvent');
  var modifyBtnContainer = $('.modalBtnContainer-modifyEvent');


  /* ****************
  *  새로운 일정 생성
  * ************** */
  var newEvent = function (start, end, eventType) {

      $("#contextMenu").hide(); //메뉴 숨김

      modalTitle.html('새로운 일정');
      editStart.val(start);
      editEnd.val(end);
      editType.val(eventType).prop("selected", true);

      addBtnContainer.show();
      modifyBtnContainer.hide();
      eventModal.modal('show');

      /******** 임시 RAMDON ID - 실제 DB 연동시 삭제 **********/
      var eventId = 1 + Math.floor(Math.random() * 1000);
      /******** 임시 RAMDON ID - 실제 DB 연동시 삭제 **********/

      //새로운 일정 저장버튼 클릭
      $('#save-event').unbind();
      $('#save-event').on('click', function () {

          var eventData = {
              _id: eventId,
              title: editTitle.val(),
              start: editStart.val(),
              end: editEnd.val(),
              description: editDesc.val(),
              type: editType.val(),
              username: '사나',
              backgroundColor: editColor.val(),
              textColor: '#ffffff',
              allDay: false
          };

          if (eventData.start > eventData.end) {
              alert('끝나는 날짜가 앞설 수 없습니다.');
              return false;
          }

          if (eventData.title === '') {
              alert('일정명은 필수입니다.');
              return false;
          }

          var realEndDay;

          if (editAllDay.is(':checked')) {
              eventData.start = moment(eventData.start).format('YYYY-MM-DD');
              //render시 날짜표기수정
              eventData.end = moment(eventData.end).add(1, 'days').format('YYYY-MM-DD');
              //DB에 넣을때(선택)
              realEndDay = moment(eventData.end).format('YYYY-MM-DD');

              eventData.allDay = true;
          }

          $("#calendar").fullCalendar('renderEvent', eventData, true);
          eventModal.find('input, textarea').val('');
          editAllDay.prop('checked', false);
          eventModal.modal('hide');

          //새로운 일정 저장
          $.ajax({
              type: "get",
              url: "",
              data: {
                  //.....
              },
              success: function (response) {
                  //DB연동시 중복이벤트 방지를 위한
                  //$('#calendar').fullCalendar('removeEvents');
                  //$('#calendar').fullCalendar('refetchEvents');
              }
          });
      });
  };

  //editEvent.js

    /* ****************
  *  일정 편집
  * ************** */
    var editEvent = function (event, element, view) {

    $('.popover.fade.top').remove();
    $(element).popover("hide");

    if (event.allDay === true) {
        editAllDay.prop('checked', true);
    } else {
        editAllDay.prop('checked', false);
    }

    if (event.end === null) {
        event.end = event.start;
    }

    if (event.allDay === true && event.end !== event.start) {
        editEnd.val(moment(event.end).subtract(1, 'days').format('YYYY-MM-DD HH:mm'))
    } else {
        editEnd.val(event.end.format('YYYY-MM-DD HH:mm'));
    }

    modalTitle.html('일정 수정');
    editTitle.val(event.title);
    editStart.val(event.start.format('YYYY-MM-DD HH:mm'));
    editType.val(event.type);
    editDesc.val(event.description);
    editColor.val(event.backgroundColor).css('color', event.backgroundColor);

    addBtnContainer.hide();
    modifyBtnContainer.show();
    eventModal.modal('show');

    //업데이트 버튼 클릭시
    $('#updateEvent').unbind();
    $('#updateEvent').on('click', function () {

        if (editStart.val() > editEnd.val()) {
            alert('끝나는 날짜가 앞설 수 없습니다.');
            return false;
        }

        if (editTitle.val() === '') {
            alert('일정명은 필수입니다.')
            return false;
        }

        var statusAllDay;
        var startDate;
        var endDate;
        var displayDate;

        if (editAllDay.is(':checked')) {
            statusAllDay = true;
            startDate = moment(editStart.val()).format('YYYY-MM-DD');
            endDate = moment(editEnd.val()).format('YYYY-MM-DD');
            displayDate = moment(editEnd.val()).add(1, 'days').format('YYYY-MM-DD');
        } else {
            statusAllDay = false;
            startDate = editStart.val();
            endDate = editEnd.val();
            displayDate = endDate;
        }

        eventModal.modal('hide');

        event.allDay = statusAllDay;
        event.title = editTitle.val();
        event.start = startDate;
        event.end = displayDate;
        event.type = editType.val();
        event.backgroundColor = editColor.val();
        event.description = editDesc.val();

        $("#calendar").fullCalendar('updateEvent', event);

        //일정 업데이트
        $.ajax({
            type: "get",
            url: "",
            data: {
                //...
            },
            success: function (response) {
                alert('수정되었습니다.')
            }
        });

    });

    // 삭제버튼
    $('#deleteEvent').on('click', function () {
        $('#deleteEvent').unbind();
        $("#calendar").fullCalendar('removeEvents', [event._id]);
        eventModal.modal('hide');

        //삭제시
        $.ajax({
            type: "get",
            url: "",
            data: {
                //...
            },
            success: function (response) {
                alert('삭제되었습니다.');
            }
        });
    });
  };

  //etcSetting.js

  //SELECT 색 변경
  $('#edit-color').change(function () {
    $(this).css('color', $(this).val());
  });

  //필터
  $('.filter').on('change', function () {
    $('#calendar').fullCalendar('rerenderEvents');
  });

  $("#type_filter").select2({
    placeholder: "선택..",
    allowClear: true
  });

  //datetimepicker
  $("#edit-start").datetimepicker({
    format: 'YYYY-MM-DD HH:mm'
  });

});
