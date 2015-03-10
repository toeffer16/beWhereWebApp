
<div class="panel panel-success">
    <div class="panel-heading">Administrator Panel</div>
    <!--<div class="panel-body">   
    //insert here codes for button ;) CRUD
    </div> -->
    <div class="well well-sm">
        <p><h1><i class="fa fa-users fa-sm"></i> User Management</h1></p>
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
        <table id="users-table" class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Account Type</th>
                    <th>First Name</th>
                    <th>Last Name</th>
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
        
        $('#users-table').dataTable({
            "processing": true,
            "serverSide": true,
            
            "ajax": {
                "url": "<?php echo site_url("administrator/render_user_tables") ?>",
                "type": "POST"
            },
            "columns": [
                {data: 'Username'},
                {data: 'Type'},
                {data: 'First_Name'},
                {data: 'Last_Name'}
            ],
            "rowCallback": function( row, data ) {
                if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
                    $(row).addClass('success');
                }
            }
        });
        
        $('#users-table tbody').on('click', 'tr', function () {
            
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
            //$('#users-table').rows();
            //alert($('#users-table').DataTable().page.len());
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
            //$('#users-table').rows();
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
            Delete selected user(s)
        </h4>
      </div>
      <div class="modal-body">
          Are you sure you want to delete <span id="del_selected_count"></span> selected user accounts? This action cannot be undone.
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
            
            $.post("<?php echo site_url("administrator/delete_users"); ?>",
                {
                    user_id: selectedUserIDs
                },
                function(data, status){
                    if (status === "success"){
                        //$('#users-table').DataTable().ajax.reload();
                        for(var i=0; i<selected.length; i++){
                            $('#users-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#users-table').DataTable().draw(false);
                        
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_addLabel">
            <span class="" aria-hidden="true"></span><i class="fa fa-user-plus fa-lg"></i> 
            Add user account
        </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
            <div class="form-group">
              <label for="user-add-username" class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="user-add-username" >
              </div>
            </div>
            <div class="form-group">
              <label for="user-add-password" class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="user-add-password">
              </div>
            </div>
           <div class="form-group">
              <label for="user-add-firstname" class="col-sm-2 control-label">First Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="user-add-firstname" >
              </div>
            </div>
           <div class="form-group">
              <label for="user-add-lastname" class="col-sm-2 control-label">Last Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="user-add-lastname" >
              </div>
            </div>
           <div class="form-group">
              <label for="user-add-type" class="col-sm-2 control-label">Account Type</label>
              <div class="col-sm-10">
                <select class="form-control" id="user-add-type">
                    <option value='admin'>Admin</option>
                    <option value='contributor' selected>Contributor</option>
                    <option value='member'>Member</option>
                  </select>
              </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn-add-user-cancel" ><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-add-user"><i class="fa fa-check-square-o fa-lg"></i> Add user</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        $('#btn-add-user').on('click', function () {
        
            var username = $("#user-add-username").val();
            var password = $("#user-add-password").val();
            var firstname = $("#user-add-firstname").val();
            var lastname = $("#user-add-lastname").val();
            var type = $("#user-add-type").val();
            
            $.post("<?php echo site_url("administrator/add_user"); ?>",
                {
                    username: username,
                    password: password,
                    first_name: firstname,
                    last_name: lastname,
                    type: type
                },
                function(data, status){
                    if (status === "success"){
                        $('#users-table').DataTable().ajax.reload();
                        /*for(var i=0; i<selected.length; i++){
                            $('#users-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#users-table').DataTable().draw(false);
                        */
                    }
                }
            );
        
            $("#user-add-username").val("");
            $("#user-add-password").val("");
            $("#user-add-firstname").val("");
            $("#user-add-lastname").val("");
            $("#user-add-type").val('contributor');
        
            $('#modal_add').modal('hide');
        });
        
        $('#btn-add-user-cancel').on("click", function(){
            $("#user-add-username").val("");
            $("#user-add-password").val("");
            $("#user-add-firstname").val("");
            $("#user-add-lastname").val("");
            $("#user-add-type").val('contributor');
            $('#modal_add').modal('hide');
        });
    });
</script>



<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="modal_editLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_editLabel">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 
            Edit user account
        </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
            <div class="form-group">
              <label for="modal-edit-username" class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="user-edit-username" >
              </div>
            </div>
            <div class="form-group">
              <label for="modal-edit-password" class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="user-edit-password">
              </div>
            </div>
           <div class="form-group">
              <label for="modal-edit-firstname" class="col-sm-2 control-label">First Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="user-edit-firstname" >
              </div>
            </div>
           <div class="form-group">
              <label for="modal-edit-lastname" class="col-sm-2 control-label">Last Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="user-edit-lastname" >
              </div>
            </div>
           <div class="form-group">
              <label for="modal-edit-type" class="col-sm-2 control-label">Account Type</label>
              <div class="col-sm-10">
                <select class="form-control" id="user-edit-type">
                    <option value='admin'>Admin</option>
                    <option value='contributor' selected>Contributor</option>
                    <option value='member'>Member</option>
                  </select>
              </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-edit-user"><span class="glyphicon glyphicon-save"></span> Save</button>
      </div>
    </div>
  </div>
</div>


<script>
    
    $(document).ready(function () {
        
        $('#modal_edit').on('show.bs.modal', function (event) {
            var selectedUserID = trim_id(selected[0]);
            
            $.post("<?php echo site_url("administrator/get_user"); ?>",
                {
                    user_id: selectedUserID
                },
                function(data, status){
                    if (status === "success"){
                        //console.log(data);
                        var jsondata = $.parseJSON(data);
                        
                        
                        $("#user-edit-username").val(jsondata.Username);
                        $("#user-edit-password").val("");
                        $("#user-edit-firstname").val(jsondata.First_name);
                        $("#user-edit-lastname").val(jsondata.Last_name);
                        $("#user-edit-type").val(jsondata.Type);
                        
                        //$('#users-table').DataTable().ajax.reload();
                        /*
                        for(var i=0; i<selected.length; i++){
                            $('#users-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#users-table').DataTable().draw(false);
                        
                        selected = [];
                        updateControlButtonsState();
                    */
                    }
                }
            );  
        });
        
        
        $('#btn-edit-user').on('click', function () {
        
            var username = $("#user-edit-username").val();
            var password = $("#user-edit-password").val();
            var firstname = $("#user-edit-firstname").val();
            var lastname = $("#user-edit-lastname").val();
            var type = $("#user-edit-type").val();
            
            var selectedUserID = trim_id(selected[0]);
            
            $.post("<?php echo site_url("administrator/edit_user"); ?>",
                {
                    user_id: selectedUserID,
                    username: username,
                    password: password,
                    first_name: firstname,
                    last_name: lastname,
                    type: type
                },
                function(data, status){
                    if (status === "success"){
                        $('#users-table').DataTable().ajax.reload();
                        /*for(var i=0; i<selected.length; i++){
                            $('#users-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#users-table').DataTable().draw(false);
                        */
                    }
                }
            );
        
            $("#user-edit-username").val("");
            $("#user-edit-password").val("");
            $("#user-edit-firstname").val("");
            $("#user-edit-lastname").val("");
            $("#user-edit-type").val('contributor');
        
             $('#modal_edit').modal('hide');
        });
    });
</script>