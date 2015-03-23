<style>
.well {
    background-color: #f1f8e9;
}
</style>

<div class="panel panel-success">
    <div class="panel-heading">Administrator Panel</div>
    <!--<div class="panel-body">   
    //insert here codes for button ;) CRUD
    </div> -->
    <div class="well well-sm">
        <p><h1><i class="fa fa-ticket fa-sm"></i> User Reports </h1></p>
    </div>

    <div class="row-offcanvas" style="padding-right: 20px; padding-left: 20px;">
        <div role="tabpanel">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="" id="nav_all" role="tab" data-toggle="tab">All Reports</a></li>
                <li role="presentation"><a href="" id="nav_approved" role="tab" data-toggle="tab">Approved Reports</a></li>
                <li role="presentation"><a href="" id="nav_pending" role="tab" data-toggle="tab">Pending Reports <span id="badge_pending" class="badge"></span></a></li>
            </ul>
            <br>

            <!-- Tab panes -->
            <!--
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                <br/> </div>
                <div role="tabpanel" class="tab-pane" id="profile">Approved Reports <br><br></div>
                <div role="tabpanel" class="tab-pane" id="messages">Pending Reports <br><br></div>
            </div> -->
        </div>

        
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
        <a type="button" id="btnConfirm" class="btn btn-success btn-sm disabled" >
             <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 
            Confirm
            <span id="badge_confirm" class="badge"></span>
        </a>
        <a type="button" id="btnUnconfirm" class="btn btn-warning btn-sm disabled">
            <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> 
            Unconfirm
            <span id="badge_unconfirm" class="badge"></span>
        </a>
        <a type="button" id="btnDelete" class="btn btn-danger btn-sm disabled" data-toggle="modal" data-target="#modal_delete">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
            Delete
            <span id="badge_del" class="badge"></span>
        </a>
    </div>
    <div style="padding-bottom: 10px"></div> 
        <table id="incidents-table" class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Crime</th>
                    <th>Incident Description</th>
                    <!--<th>GPS Coordinates</th>-->
                    <th>Time</th>
                    <th>Author</th>
                    <th>Approved by</th>
                    <th>Confirmed</th>
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
    updatePendingCounter();
    
    $(document).ready(function () {
        
        $('#incidents-table').dataTable({
            "processing": true,
            "serverSide": true,
            
            "ajax": {
                "url": "<?php echo site_url("administrator/render_incidents_table") ?>",
                "type": "POST"
            },
            "columns": [
                {data: 'Crime'},
                {data: 'Incident_Description'},
                //{data: 'GPS_Coordinates'},
                {data: 'Time'},
                {data: 'Author'},
                {data: 'Approved_by'},
                {data: 'Confirmed'}
            ],
            "rowCallback": function( row, data ) {
                if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
                    $(row).addClass('success');
                    
                }
                /*
                var inlineToggleButton = "<a type=\"button\" class=\"btn btn-success btn-sm inline-confirm-button\" data-incidentid=\"" + trim_id(data.DT_RowId)  + "\" > " +
                                         "<span class=\"glyphicon glyphicon-thumbs-up\" aria-hidden=\"true\"></span> " +
                                         "Confirm" +
                                         "<span id=\"badge_confirm\" class=\"badge\"></span>" +
                                         "</a>";
                                         */
                var inlineToggleButton;
                var lel = $(row).find("td:last");
                
                switch(parseInt(lel.text())){
                    case 1:
                        inlineToggleButton = "<span class=\"label label-success\">Yes <span class=\"glyphicon glyphicon-thumbs-up\" aria-hidden=\"true\"></span></span> ";
                        break;
                    case 0:
                        inlineToggleButton = "<span class=\"label label-danger\">No <span class=\"glyphicon glyphicon-thumbs-down\" aria-hidden=\"true\"></span></span>";
                        break;
                }
                
                lel.html(inlineToggleButton);
                lel.addClass("text-center");
                
                // <h4><span class="label label-danger">No</span></h4>
                //console.log("row: " + $(row).val() + " data: " + $(data).val());
            }
        });
        
        $('#incidents-table tbody').on('click', 'tr', function () {
            
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
            //$('#incidents-table').rows();
            //alert($('#incidents-table').DataTable().page.len());
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
            //$('#incidents-table').rows();
            selected = [];
            $("tr[id*='row_']").each(function( index ) {
                $( this ).removeClass("success");
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
                $("#badge_confirm").text(selected.length);
                $("#badge_unconfirm").text(selected.length);
                
                $("#btnDelete").removeClass('disabled');
                $("#btnConfirm").removeClass('disabled');
                $("#btnUnconfirm").removeClass('disabled');
                //$("#btnEdit").addClass('disabled');
            }else if (selected.length===0){
                $("#badge_del").text("");
                $("#badge_confirm").text("");
                $("#badge_unconfirm").text("");
                
                $("#btnDelete").addClass('disabled');
                $("#btnConfirm").addClass('disabled');
                $("#btnUnconfirm").addClass('disabled');
            }
        });
    }
    
    function updatePendingCounter(){
        $(document).ready(function () {
            $.get("<?php echo site_url("administrator/get_pending_incident_count"); ?>", 
                function(data, status){
                    var jsondata = $.parseJSON(data);
                    var count = parseInt(jsondata.count);
                    if (count > 0)
                        $('#badge_pending').text(count);
                    else
                        $('#badge_pending').text("");
                }
            ); 
            
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
            Delete selected incident(s)
        </h4>
      </div>
      <div class="modal-body">
          Are you sure you want to delete <span id="del_selected_count"></span> selected incident reports? This action cannot be undone.
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
            
            var selectedIncidentIDs = [];
            for(var i=0; i<selected.length; i++){
                selectedIncidentIDs.push(trim_id(selected[i]));
            }
            
            $.post("<?php echo site_url("administrator/delete_incidents"); ?>",
                {
                    incident_id: selectedIncidentIDs
                },
                function(data, status){
                    if (status === "success"){
                        //$('#incidents-table').DataTable().ajax.reload();
                        for(var i=0; i<selected.length; i++){
                            $('#incidents-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#incidents-table').DataTable().draw(false);
                        
                        selected = [];
                        updateControlButtonsState();
                        updatePendingCounter();
                    }
                }
            );
            
            $('#modal_delete').modal('hide');
        });
        
        
        
        $("#btnConfirm").on("click", function(){
            var selectedIncidentIDs = [];
            for(var i=0; i<selected.length; i++){
                selectedIncidentIDs.push(trim_id(selected[i]));
            }
            
            $.post("<?php echo site_url("administrator/confirm_incidents"); ?>",
                {
                    incident_id: selectedIncidentIDs
                },
                function(data, status){
                    if (status === "success"){
                        //$('#incidents-table').DataTable().ajax.reload();
                        for(var i=0; i<selected.length; i++){
                            $('#incidents-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#incidents-table').DataTable().draw(false);
                        
                        selected = [];
                        updateControlButtonsState();
                        updatePendingCounter();
                    }
                }
            );
        });
        
        $("#btnUnconfirm").on("click", function(){
            var selectedIncidentIDs = [];
            for(var i=0; i<selected.length; i++){
                selectedIncidentIDs.push(trim_id(selected[i]));
            }
            
            $.post("<?php echo site_url("administrator/unconfirm_incidents"); ?>",
                {
                    incident_id: selectedIncidentIDs
                },
                function(data, status){
                    if (status === "success"){
                        //$('#incidents-table').DataTable().ajax.reload();
                        for(var i=0; i<selected.length; i++){
                            $('#incidents-table').DataTable().row( $("tr[id='row_" + i + "']") ).remove();
                        }
                        $('#incidents-table').DataTable().draw(false);
                        
                        selected = [];
                        updateControlButtonsState();
                        updatePendingCounter();
                    }
                }
            );
        });
        
        $('#nav_all').on('click', function(){
            updatePendingCounter();
            $('#incidents-table').DataTable().ajax.url("<?php echo site_url("administrator/render_incidents_table") ?>").load();
        });
        
        $('#nav_approved').on('click', function(){
            updatePendingCounter();
            $('#incidents-table').DataTable().ajax.url("<?php echo site_url("administrator/render_incidents_table_approved") ?>").load();
        });
        
        $('#nav_pending').on('click', function(){
            updatePendingCounter();
            $('#incidents-table').DataTable().ajax.url("<?php echo site_url("administrator/render_incidents_table_pending") ?>").load();
        });
        
    });
</script>