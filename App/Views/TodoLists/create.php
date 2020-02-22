<?php
$title = 'Todo List Create';
include_once ROOT_VIEW . '_header.php';
?>

	<?php
foreach ($data as $index => $todo) {
    echo <<<EOH
				<div class="item_todo">
					<p class="todo_name">{$todo->name}</p>
					<p class="todo_start">{$todo->startDate}</p>
					<p class="todo_end">{$todo->endDate}</p>
					<p class="todo_status">{$todo->status}</p>
				</div>
EOH;
}
?>

<?php
include_once ROOT_VIEW . '_footer.php';
?>