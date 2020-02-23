<?php
$title = 'Todo List';
include_once ROOT_VIEW . '_header.php';
?>
<div id="menu">
    <div class="form-group">
	  <select class="form-control" id="type-show">
	    <option value="day">Date</option>
	    <option value="week">Week</option>
	    <option value="month" selected="">Month</option>
	  </select>
	</div>
	<button type="button" class="btn btn-default" id="show-today">Today</button>
	<button type="button" class="btn btn-default" id="prev-time"><</button>
	<button type="button" class="btn btn-default" id="next-time">></button>
	<span id="range-time-show"></span>
</div>
<div id="calendar"></div>
<button id="myBtnOpen">Open Modal</button>
<div id="myModal" class="myModal">
  <div class="modal-content">
    <p>Some text in the Modal..</p>
  	<div class="footer">
	  	<button class="btn btn-primary">Save</button>
	  	<button class="btn" id="myModal">Close</button>
  	</div>
  </div>
</div>
<?php
include_once ROOT_VIEW . '_footer.php';
?>