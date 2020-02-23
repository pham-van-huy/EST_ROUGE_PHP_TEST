<div id="myModal" class="myModal">
  <div class="modal-content">
    <p class="modal-name">Create</p>
    <div class="form-edit">
        <form id="create-edit" name="create-edit">
          <div class="form-group">
            <label for="todoTitle">Title</label>
            <input class="form-control" id="todoTitle" placeholder="Title" name="title" maxlength="50">
          </div>
          <div class="form-group">
            <label for="todoStatus">Status</label>
            <select class="form-control" id="todoStatus" name='status'>
              <option>Planning</option>
              <option>Doing</option>
              <option>Complete</option>
            </select>
          </div>
          <div class="form-group">
            <div class="tui-datepicker-input tui-datetime-input tui-has-focus">
                <input id="startpicker-input" type="text" aria-label="Date" name='start'>
                <span class="tui-ico-date"></span>
                <div id="startpicker-container" style="margin-left: -1px;"></div>
            </div>
            <span class="to">to</span>
            <div class="tui-datepicker-input tui-datetime-input tui-has-focus">
                <input id="endpicker-input" type="text" aria-label="Date" name="end">
                <span class="tui-ico-date"></span>
                <div id="endpicker-container" style="margin-left: -1px;"></div>
            </div>
          </div>
          <div class="form-group">
            <label for="todoDescription">Description</label>
            <textarea
                class="form-control"
                id="todoDescription"
                rows="3"
                maxlength="200"
                name="description"></textarea>
          </div>
        </form>
    </div>
    <div id="mes-error"></div>
    <div class="footer">
        <button class="btn btn-primary btn-sm" id="myBtnSubmit">Save</button>
        <button class="btn btn-sm" id="myBtnClose">Close</button>
    </div>
  </div>
</div>
