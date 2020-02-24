const $ = document.querySelector.bind(document);
var Calendar = tui.Calendar;
var calendar = new Calendar('#calendar', {
    defaultView: 'month',
    taskView: true,
    useDetailPopup: true,
    disableClick: true,
    template: {
        popupEdit: function() {
            return 'Edit';
        }
    }
});

var tasks = [];
var colorStatus = {
    'Planning': '#9d9d9d',
    'Doing': '#00a9ff',
    'Complete': '#bbdc00'
};

calendar.on({
    beforeUpdateSchedule: function (e) {
        var datas = {
            url: '/api-update',
            title: e.schedule.title,
            status: e.schedule.raw.status,
            description: e.schedule.body,
            start: e.schedule.start,
            end: e.schedule.end,
        };
        $('.modal-name').innerText = 'Edit';
        $('#create-edit').setAttribute('data-id', e.schedule.raw.id)
        beforeOpenModal(datas);
    },
    beforeDeleteSchedule: function (e) {
        var id = e.schedule.raw.id;
        var ans = confirm('Are you sure delete task ' + e.schedule.title + '?');
        if (ans) {
            fetch('/api-destroy', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({id: id})
            }).then(function (res) {
                return res.json();
            }).then(function (data) {
                if (data.status == 200) {
                    var index = findIndexTask(data.data.id);

                    tasks.splice(index, 1);

                    renderCanlendar();
                }
            })
        }
    }
});

showRangeTime();

$('#show-today').onclick = function () {
    calendar.today();
    showRangeTime();
}

$('#prev-time').onclick = function () {
    calendar.prev();
    showRangeTime();
}

$('#next-time').onclick = function () {
    calendar.next();
    showRangeTime();
}

$('#type-show').onchange = function (el) {
    calendar.changeView(el.target.value, true);
    showRangeTime();
}

function showRangeTime() {
    var start = calendar.getDateRangeStart();
    var end = calendar.getDateRangeEnd();
    var rangeText = start.getFullYear() + '.' + (start.getMonth()+ 1) + '.' + start.getDate() + ' ~ '
    rangeText += end.getFullYear() + '.' + (end.getMonth()+ 1) + '.' + end.getDate();

    $('#range-time-show').textContent = rangeText;
    fetchData();
}

function fetchData() {
    var url = '/api-list?start=' + calendar.getDateRangeStart().toUTCString();
    url += '&end=' + calendar.getDateRangeEnd().toUTCString();
    fetch(url).then(function(res) {
        return res.json();
    }).then(function(json) {
        if (json.status == 200) {
            tasks = [];
            dataRes = json.data;

            dataRes.forEach(function (item, index) {
                var data = createItemCal(item);
                
                tasks.push(data);
            });
            
            renderCanlendar();
        }
    })
}

function renderCanlendar()
{
    calendar.clear();
    calendar.createSchedules(tasks);
}

//handler modal
var modal = $("#myModal");

$("#btnAdd").onclick = function() {
    var datas = {
        url: '/api-create',
        title: '',
        status: 'Planning',
        description: ''
    };
    $('.modal-name').innerText = 'Create';

    beforeOpenModal(datas);
}
$("#myBtnClose").onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

var today = new Date();
var picker = tui.DatePicker.createRangePicker({
    startpicker: {
        date: today,
        input: '#startpicker-input',
        container: '#startpicker-container'
    },
    endpicker: {
        date: today,
        input: '#endpicker-input',
        container: '#endpicker-container'
    },
    format: 'yyyy-MM-dd HH:mm A',
    timepicker: {
        layoutType: 'tab',
        inputType: 'spinbox'
    },
    selectableRanges: [
        [today, new Date(today.getFullYear() + 1, today.getMonth(), today.getDate())]
    ]
});

//fill data form form in modal

function beforeOpenModal(datas) {
    $('#create-edit').setAttribute('action', datas.url);
    $('#todoTitle').value = datas.title;
    $('#todoStatus').value = datas.status;
    $('#todoDescription').value = datas.description;
    var start = datas.start ? datas.start.toUTCString() : today;
    picker.setRanges([
        [new Date(start), new Date(today.getFullYear() + 1, today.getMonth(), today.getDate())],
        [today, new Date(today.getFullYear() + 1, today.getMonth(), today.getDate())]
    ])
    picker.setStartDate(new Date(start));
    var end = datas.end ? datas.end.toUTCString() : today;
    picker.setEndDate(new Date(end));

    $('#mes-error').innerHTML = ''
    modal.style.display = "block";
}

//submit form edit or create

$('#myBtnSubmit').onclick = function () {
    var url = $('#create-edit').getAttribute('action');
    var formData = new FormData();
    var start = picker.getStartDate().toISOString();
    var end = picker.getEndDate().toISOString();
    var id = $('#create-edit').getAttribute('data-id');


    var data = {
        title:  $('#todoTitle').value,
        status:  $('#todoStatus').value,
        start:  start,
        end:  end,
        description:  $('#todoDescription').value,
    };

    if (id !== null) {
        data.id = id;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(function (res) {
        return res.json();
    }).then(function (json) {
        if (json.status == 200) {
            var dataRes = createItemCal(json.data);

            if (id !== null) {
                var index = findIndexTask(id);
                tasks[index] = dataRes
            } else {
                tasks.push(dataRes);
            }

            renderCanlendar();
            modal.style.display = "none";
        } else if (json.status == 422) {
            showError(json.error);
        }
    });
}

//function show mess validate fail

function showError(errors)
{   
    var mes = '';
    for (var key in errors) {
        mes += '<p>' + errors[key] + '</p>';
    }
    $('#mes-error').innerHTML = mes;
}

function findIndexTask(id)
{
    var index = null;

    for (var i = tasks.length - 1; i >= 0; i--) {
        if (id == tasks[i].id) {
            index = i;
            break;
        } 
    }

    return index;
}

function createItemCal(data)
{
    data.calendarId = data.id;
    data.bgColor = colorStatus[data.status];
    data.color = 'white';
    data.category = 'time';
    data.body = data.description;
    data.raw = { status: data.status, id: data.id };

    return data;
}