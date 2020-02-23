const $ = document.querySelector.bind(document);
var Calendar = tui.Calendar;
var calendar = new Calendar('#calendar', {
  defaultView: 'month',
  taskView: true,
  // useCreationPopup: true,
  useDetailPopup: true,
  // isReadOnly: true,
  template: {
    popupEdit: function() {
      return 'Edit';
    }
  }
});

var colorStatus = {
	'Planning': '#9d9d9d',
	'Doing': '#00a9ff',
	'Complete': '#bbdc00'
}

calendar.on({
	beforeUpdateSchedule: function (e) {
		console.log(2)
	},
	beforeDeleteSchedule: function (e) {
		console.log(2)
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

function showRangeTime()
{
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
		tasks = [];
		
		json.forEach(function (item, index) {
			item.calendarId = String(index + 1);
			item.bgColor = colorStatus[item.status];
			item.color = 'white';
			item.category = 'time';
			tasks.push(item);
		});
		calendar.clear();
		calendar.createSchedules(tasks);
	})
}

//handler modal
var modal = $("#myModal");
var btnOpen = $("#myBtnOpen");
var btnClose = $("#myBtnClose");

btnOpen.onclick = function() {
  modal.style.display = "block";
}
btnClose.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}