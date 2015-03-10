
<div class="panel panel-success">
    <div class="panel-heading">Administrator Panel</div>
    <div class="well well-sm">
        <p><h1><i class="fa fa-info-circle fa-sm"></i> Crime Pedia </h1></p>
    </div>

<div class="row-offcanvas" style="padding-right: 20px; padding-left: 20px;">
    
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Select <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#" id="mnuSelectAll">Select all</a></li>
            <li><a href="#" id="mnuSelectNone">Select none</a></li>
            <li><a href="#" id="mnuInvertSelection">Invert selection</a></li>
        </ul>
    </div>
    
    <div class="pull-right">
        <a type="button" id="btnAdd" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_add">
            <span class="" aria-hidden="true"><i class="fa fa-user-plus fa-sm"></i></span> 
            Add
        </a>
        <a type="button" id="btnEdit" class="btn btn-primary btn-sm disabled" data-toggle="modal" data-target="#modal_edit">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 
            Edit
        </a>
        <a type="button" id="btnDelete" class="btn btn-warning btn-sm disabled" data-toggle="modal" data-target="#modal_delete">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
            Delete
            <span id="badge_del" class="badge"></span>
        </a>
    </div>
    <div style="padding-bottom: 10px"></div> 
        <table id="crimes-table" class="table table-hover table-bordered ">
            <thead>
                <tr>
                    <th>Crime Name</th>
                    <th>Crime Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    
</div>
</div>

<script>
    var selected = [];
    
    $(document).ready(function () {
        
        $('#crimes-table').dataTable({
            "processing": true,
            "serverSide": true,
            
            "ajax": {
                "url": "<?php echo site_url("administrator/render_crime_pedia_tables") ?>",
                "type": "POST"
            },
            "columns": [
                {data: 'Crime_Name'},
                {data: 'Crime_Description'}
            ],
            "rowCallback": function( row, data ) {
                if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
                    $(row).addClass('success');
                }
            }
        });
        
        $('#crimes-table tbody').on('click', 'tr', function () {
            
            var id = this.id;
            var index = $.inArray(id, selected);

            if ( index === -1 ) {
                selected.push( id );
            } else {
                selected.splice( index, 1 );
            }

            $(this).toggleClass('success');
            updateControlButtonsState();
        } );
        
        $("#mnuSelectAll").on("click", function(){
            //$('#crimes-table').rows();
            //alert($('#crimes-table').DataTable().page.len());
            //$("tr[id*='row_']").addClass("success");
            selected = [];
            $("tr[id*='row_']").each(function( index ) {
                $( this ).addClass("success");
                selected.push( this.id );
                //console.log( index + ": " + $( this ).addClass("success") );
            });
            updateControlButtonsState();
        });
        $("#mnuSelectNone").on("click", function(){
            //$('#crimes-table').rows();
            selected = [];
            $("tr[id*='row_']").each(function( index ) {
                $( this ).removeClass("success")
                //console.log( index + ": " + $( this ).addClass("success") );
            });
            updateControlButtonsState();
        });
        $("#mnuInvertSelection").on("click", function(){
            selected = [];
            $("tr[id*='row_']").each(function( index ) {
                $( this ).toggleClass("success");
                if ($( this ).hasClass("success")){
                    selected.push(  this.id );
                }
                //console.log( index + ": " + $( this ).addClass("success") );
            });
            updateControlButtonsState();
            //console.log(selected);
        });
    });
    
    function updateControlButtonsState(){
        $(document).ready(function () {
            if(selected.length > 0){
                $("#badge_del").text(selected.length);
                $("#btnDelete").removeClass('disabled');
                $("#btnEdit").addClass('disabled');
            }else if (selected.length===0){
                $("#badge_del").text("");
                $("#btnDelete").addClass('disabled');
                $("#btnEdit").addClass('disabled');
            }
            if (selected.length===1){
                $("#btnEdit").removeClass('disabled');
            }
        });
    }
    
    function trim_id(strID){
        return parseInt(strID.replace("row_", ""));
    }
</script>

<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
            Delete selected crime(s)
        </h4>
      </div>
      <div class="modal-body">
          Are you sure you want to delete <span id="del_selected_count"></span> selected crime entry? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="btnDeleteConfirm">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
            Confirm delete
        </button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        
        $('#modal_delete').on('show.bs.modal', function (event) {
            $("#del_selected_count").text(selected.length);
        });
        
        $("#btnDeleteConfirm").on("click", function(){
            // Insert code to dismiss modal here
            
            var selectedUserIDs = [];
            for(var i=0; i<selected.length; i++){
                selectedUserIDs.push(trim_id(selected[i]));
            }
            
            $.post("<?php echo site_url("administrator/delete_crimes"); ?>",
                {
                    crime_id: selectedUserIDs
                },
                function(data, status){
                    if (status === "success"){
                        //$('#crimes-table').DataTable().ajax.reload();
                        for(var i=0; i<selected.length; i++){
                            $('#crimes-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#crimes-table').DataTable().draw(false);
                        
                        selected = [];
                        updateControlButtonsState();
                    }
                }
            );
            
            $('#modal_delete').modal('hide');
        });
        
    });
</script>

<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="modal_addLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_addLabel">
            <span class="" aria-hidden="true"></span><i class="fa fa-info-circle fa-lg"></i> 
            Add crime entry
        </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
            <div class="form-group">
              <label for="crime-add-crime-name" class="col-sm-2 control-label">Crime Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="crime-add-crime-name" >
              </div>
            </div>
           <div class="form-group">
              <label for="crime-add-description" class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10">
                <!-- <input type="text" class="form-control" id="crime-add-description" > -->
                 <textarea class="form-control" rows="6" id="crime-add-description"></textarea>
              </div>
            </div>
           
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn-add-crime-cancel"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-add-crime"><i class="fa fa-check-square-o fa-lg"></i> Add crime entry</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        $( '#crime-add-description' ).ckeditor();
        
        $('#btn-add-crime').on('click', function () {
        
            var crime_name = $("#crime-add-crime-name").val();
            var description = $("#crime-add-description").val();
            
            $.post("<?php echo site_url("administrator/add_crime_pedia"); ?>",
                {
                    crime_name: crime_name,
                    crime_description: description
                },
                function(data, status){
                    if (status === "success"){
                        $('#crimes-table').DataTable().ajax.reload();
                        /*for(var i=0; i<selected.length; i++){
                            $('#crimes-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#crimes-table').DataTable().draw(false);
                        */
                    }
                }
            );
        
            $("#crime-add-crime-name").val("");
            $("#crime-add-description").val("");
        
            $('#modal_add').modal('hide');
        });
        
        $('#btn-add-crime-cancel').on("click", function(){
            $("#crime-add-crime-name").val("");
            $("#crime-add-description").val("");
        
            $('#modal_add').modal('hide');
        });
    });
</script>



<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="modal_editLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_editLabel">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 
            Edit crime entry
        </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
            <div class="form-group">
              <label for="crime-edit-crime-name" class="col-sm-2 control-label">Crime Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="crime-edit-crime-name" >
              </div>
            </div>
           <div class="form-group">
              <label for="crime-edit-description" class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10">
                <!-- <input type="text" class="form-control" id="crime-add-description" > -->
                 <textarea class="form-control" rows="6" id="crime-edit-description"></textarea>
              </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-edit-crime"><span class="glyphicon glyphicon-save"></span> Save</button>
      </div>
    </div>
  </div>
</div>


<script>
    $(document).ready(function () {
        $( '#crime-edit-description' ).ckeditor();
        
        $('#modal_edit').on('show.bs.modal', function (event) {
            var selectedCrimeID = trim_id(selected[0]);
            
            $.post("<?php echo site_url("administrator/get_crime"); ?>",
                {
                    crime_id: selectedCrimeID
                },
                function(data, status){
                    if (status === "success"){
                        //console.log(data);
                        var jsondata = $.parseJSON(data);
                        
                        
                        $("#crime-edit-crime-name").val(jsondata.Crime_Name);
                        $("#crime-edit-description").val(jsondata.Crime_Description);
                        
                        //$('#crimes-table').DataTable().ajax.reload();
                        /*
                        for(var i=0; i<selected.length; i++){
                            $('#crimes-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#crimes-table').DataTable().draw(false);
                        
                        selected = [];
                        updateControlButtonsState();
                    */
                    }
                }
            );  
        });
        
        
        $('#btn-edit-crime').on('click', function () {
        
            var crime_name = $("#crime-edit-crime-name").val();
            var crime_description = $("#crime-edit-description").val();
            
            var selectedCrimeID = trim_id(selected[0]);
            
            $.post("<?php echo site_url("administrator/edit_crime"); ?>",
                {
                    crime_id: selectedCrimeID,
                    crime_name: crime_name,
                    crime_description: crime_description
                },
                function(data, status){
                    if (status === "success"){
                        $('#crimes-table').DataTable().ajax.reload();
                        /*for(var i=0; i<selected.length; i++){
                            $('#crimes-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#crimes-table').DataTable().draw(false);
                        */
                    }
                }
            );
        
            $("#crime-edit-crime-name").val("");
            $("#crime-edit-description").val("");
        
             $('#modal_edit').modal('hide');
        });
    });
</script>

<script>
$.fn.modal.Constructor.prototype.enforceFocus = function () {
    var $modalElement = this.$element;
    $(document).on('focusin.modal', function (e) {
        var $parent = $(e.target.parentNode);
        if ($modalElement[0] !== e.target && !$modalElement.has(e.target).length
            // add whatever conditions you need here:
            &&
            !$parent.hasClass('cke_dialog_ui_input_select') && !$parent.hasClass('cke_dialog_ui_input_text')) {
            $modalElement.focus()
        }
    })
};
</script>