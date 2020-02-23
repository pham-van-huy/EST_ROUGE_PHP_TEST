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
    <button class="btn" id="btnAdd">Add new</button>
</div>
<div id="calendar"></div>
<?php
include_once ROOT_VIEW . 'TodoLists/_modal_create.php';
include_once ROOT_VIEW . '_footer.php';
?>