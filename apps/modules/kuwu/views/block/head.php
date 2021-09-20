<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide fade" id="confirmDelete" style="display: none;">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 id="myModalLabel">Confirm Delete</h3>
    </div>
    <div class="modal-body">
        <input type="hidden" name="cid" id="cid"><input type="hidden" name="langid" id="langid">
        <input type="hidden" name="getto" id="getto">
        <p class="msgojk1">…</p>
    </div>
    <div class="modal-footer">
        <button aria-hidden="true" data-dismiss="modal" class="btn">No</button>
        <button data-dismiss="modal" class="btn btn-primary" id="aepYes">Yes</button>
    </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide fade" id="confirmUnPubish" style="display: none;">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 id="myModalLabel">Confirm</h3>
    </div>
    <div class="modal-body">
        <input type="hidden" name="cid" id="ucid"><input type="hidden" name="langid" id="ulangid">
        <input type="hidden" name="getto" id="ugetto">
        <p class="umsgojk1">…</p>
    </div>
    <div class="modal-footer">
        <button aria-hidden="true" data-dismiss="modal" class="btn">No</button>
        <button data-dismiss="modal" class="btn btn-primary" id="uYes">Yes</button>
    </div>
</div>
<div id="new-task" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Add new category</h3>
    </div>   
    <form action="#" class='new-task-form form-horizontal form-bordered'>
        <div class="modal-body nopadding">
            <div class="control-group">
                <label for="task-name" class="control-label">Category Name</label>
                <div class="controls">
                    <input type="text" name="categorys" id="categorys">
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="Add Category" id="addCatbtn">
            </div>
        </div>
    </form>
</div>    
<div id="modal-alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="alertModalLabel"></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" id="alertok">Ok</button>
    </div>
</div>
