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
        <p><h1><i class="fa fa-map-marker fa-sm"></i> Crime Map </h1></p>
    </div>
    <div class="row-offcanvas" style="padding-right: 20px; padding-left: 20px;">
        <input id="pac-input" class="controls" type="text" placeholder="Search Box">
        <div id="googleMap" style="width:auto;height:500px;margin-bottom: 30px;"></div>
        
        <!--
        <div class="row" style="margin-left: 15px">
            <div class="col-sm-12"><h4 class="bg-primary">Incident Report</h4></div>
            <div class="col-sm-2"><strong>Crime</strong></div><div class="col-sm-10">Rape</div>
            <div class="col-sm-2"><strong>Description</strong></div><div class="col-sm-10">Rape</div>
            <div class="col-sm-2"><strong>Time</strong></div><div class="col-sm-10">March 13, 2015 8:13 AM</div>
            <div class="col-sm-2"><strong>Reported by</strong></div><div class="col-sm-10">root</div>
        </div>-->
        
    </div>
</div>

<div class="modal fade" id="modal_plot_crime" tabindex="-1" role="dialog" aria-labelledby="modal_plot_crimeLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_plot_crimeLabel">
            <span class="" aria-hidden="true"></span><i class="fa fa-map-marker fa-lg"></i> 
            Add Crime Incident
        </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
            <div class="form-group">
              <label for="crime-marker-crime" class="col-sm-2 control-label">Crime</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="crime-marker-crime" data-role="tagsinput"  >
              </div>
            </div>
            <div class="form-group">
                <label for="crime-marker-description" class="col-sm-2 control-label">Narrative</label>
              <div class="col-sm-10">
                 <textarea class="form-control" rows="6" id="crime-marker-description"></textarea>
              </div>
            </div>
           <div class="form-group">
              <label for="crime-marker-time" class="col-sm-2 control-label">Date/Time</label>
              <div class="col-sm-10">
                <!--<input type="text" class="form-control" id="crime-marker-time" > -->
                  
                    <div class='input-group date' id='crime-marker-time'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                  
              </div>
            </div>
           
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn-add-incident-cancel"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-add-incident"><i class="fa fa-check-square-o fa-lg"></i> Add incident</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_plot_outpost" tabindex="-1" role="dialog" aria-labelledby="modal_plot_outpostLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_plot_outpostLabel">
            <span class="" aria-hidden="true"></span><i class="fa fa-university fa-lg"></i> 
            Add police outpost
        </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
            <div class="form-group">
              <label for="police-marker-name" class="col-sm-2 control-label">Outpost Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="police-marker-name" >
              </div>
            </div>
           <div class="form-group">
              <label for="police-marker-descriptionn" class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10">
                <!-- <input type="text" class="form-control" id="crime-add-description" > -->
                 <textarea class="form-control" rows="6" id="police-marker-description"></textarea>
              </div>
            </div>
           
           
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn-add-outpost-cancel"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-add-outpost"><i class="fa fa-check-square-o fa-lg"></i> Add police outpost</button>
      </div>
    </div>
  </div>
</div>


<script>
    function showPlotCrimeModal(){
        $('#crime-marker-crime').tagsinput('removeAll');
        $('#crime-marker-crime').val("");
        $('#crime-marker-description').val("");
        $('#crime-marker-time').data("DateTimePicker").maxDate(moment().add(1, 'minutes'));
        $('#crime-marker-time').data("DateTimePicker").defaultDate(moment());
        $('#modal_plot_crime').modal('show');
        
    }
    function showPlotOutpostModal(){
        //alert('Insert modal popup code here.');
        $("#police-marker-name").val("");
        $('#police-marker-description').val("");
        $('#modal_plot_outpost').modal('show');
        
    }
</script>

<script>
    $(document).ready(function () {
        
        
        $('#crime-marker-time').datetimepicker({
            defaultDate: moment(),
            maxDate: moment().add(1, 'minutes')
        });
        
        $('#crime-edit-time').datetimepicker({
            defaultDate: moment(),
            maxDate: moment().add(1, 'minutes')
        });
        
        $('#btn-add-incident').on('click', function(){
            var crime_id = $("#crime-marker-crime").val();
            var description = $('#crime-marker-description').val();
            var crime_coordinates = [selectorMarker.getPosition().lat(), selectorMarker.getPosition().lng()];
            var crime_time = $('#crime-marker-time').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
            
            $.post("<?php echo site_url("administrator/plot_crime_incident"); ?>",
                {
                    crime_id: crime_id,
                    description: description,
                    time: crime_time,
                    coordinates: crime_coordinates
                },
                function(data, status){
                    if (status === "success"){
                       selectorInfoWindow.close();
                       selectorMarker.setMap(null);
                       showMarkers(crimeMap);
                    }
                }
            );
            
            $('#modal_plot_crime').modal('hide');
            //alert("Latitude: " + selectorMarker.getPosition().lat() + " Longitude: " + selectorMarker.getPosition().lng());
        });
        
        $('#btn-add-incident-cancel').on('click', function(){
            $('#modal_plot_crime').modal('hide');
        });
    });
</script>


<script>
    $(document).ready(function () {
  
        $('#btn-add-outpost').on('click', function(){
            var outpost_name = $("#police-marker-name").val();
            var description = $('#police-marker-description').val();
            var crime_coordinates = [selectorMarker.getPosition().lat(), selectorMarker.getPosition().lng()];
            
            
            $.post("<?php echo site_url("administrator/plot_police_outposts"); ?>",
                {
                    outpost_name: outpost_name,
                    outpost_desc: description,
                    coordinates: crime_coordinates
                },
                function(data, status){
                    if (status === "success"){
                       selectorInfoWindow.close();
                       selectorMarker.setMap(null);
                       showMarkers(crimeMap);
                    }
                }
            );
            
            $('#modal_plot_outpost').modal('hide');
            //alert("Latitude: " + selectorMarker.getPosition().lat() + " Longitude: " + selectorMarker.getPosition().lng());
        });
        
        $('#btn-add-outpost-cancel').on('click', function(){
            $('#modal_plot_outpost').modal('hide');
        });
    });
</script>

<script>
    
    
    var substringMatcher = function findMatches(q, cb) {
        var matches;

        // an array that will be populated with substring matches
        matches = [];

        console.log("query: " + q);

        var servResponse = '';
        //var herp = "herp";
         $.get("<?php echo site_url("administrator/get_crime_suggestions"); ?>", 
             {
                 crime_suggest: q
             },function(data, status){ 
                 servResponse = data;
                 //herp = "derp";
                console.log(servResponse);
                matches = $.parseJSON(servResponse);
                cb(matches);
        });
    };
    
    $('#crime-marker-crime').tagsinput({
        maxTags: 1,
        freeInput: false,
        trimValue: true,
        itemValue: 'value',
        itemText: 'text',
        typeaheadjs: {
            name: 'crime_names',
            displayKey: 'text',
            //valueKey: 'value',
            source: substringMatcher
        }
    });
    
</script>


<script>
    var currentCrimeMarkerClickedId = 0;
    var currentOutpostMarkerClickedId = 0;
    
    function showEditIncidentModal(incidentID){
        //alert('Add code to edit incident here');
        //$('#modal_edit_crime').modal('show');
        currentCrimeMarkerClickedId = incidentID;
        $.get("<?php echo site_url("administrator/get_incident_info"); ?>",{
            incident_id: incidentID
            },
            function(data, status){
                if (status === "success"){
                    var jsonResponse = $.parseJSON(data);
                    //console.log(jsonResponse);
                    $('#crime-edit-crime').tagsinput('add', {value: jsonResponse.Crime_ID, text:jsonResponse.Crime_Name});
                    $('#crime-edit-description').val(jsonResponse.Incident_Description);
                    $('#crime-edit-time').data("DateTimePicker").date(moment(jsonResponse.Time, "YYYY-MM-DD HH:mm:ss"));
                    $('#modal_edit_crime').modal('show');
                }
            }
        );
    }
    
    function showDeleteIncidentModal(incidentID){
        //alert('Add code to delete incident here');
        currentCrimeMarkerClickedId = incidentID;
        $('#modal_delete_incident').modal('show');
    }
    
    function showEditOutpostModal(outpostID){
        //alert('Add code to edit outpost here');
        currentOutpostMarkerClickedId = outpostID;
        $('#modal_edit_outpost').modal('show');
        
        $.get("<?php echo site_url("administrator/get_outpost_info"); ?>",{
            outpost_id: outpostID
            },
            function(data, status){
                if (status === "success"){
                    var jsonResponse = $.parseJSON(data);
                    console.log(jsonResponse);
                    $("#police-edit-name").val(jsonResponse.Outpost_Name);
                    $('#police-edit-description').val(jsonResponse.Outpost_Description);
                }
            }
        );
    }
    
    function showDeleteOutpostModal(outpostID){
        //alert('Add code to delete outpost here');
        currentOutpostMarkerClickedId = outpostID;
        $('#modal_delete_outpost').modal('show');
    }
    
</script>

<div class="modal fade" id="modal_delete_incident" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
            Delete crime report
        </h4>
      </div>
      <div class="modal-body">
          Are you sure you want to delete <span id="del_selected_count"></span> crime report? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="btnDeleteIncidentConfirm">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
            Confirm delete
        </button>
      </div>
    </div>
  </div>
</div>

<!-- LELELELELLE -->

<script>
    $(document).ready(function () {
       $('#btnDeleteIncidentConfirm').on('click', function(){
           //alert('Delete incident confirmed');
           
            var selectedIncidentIDs = [];
            selectedIncidentIDs.push(currentCrimeMarkerClickedId);
            //for(var i=0; i<selected.length; i++){
                //selectedIncidentIDs.push(trim_id(selected[i]));
            //}
            
            $.post("<?php echo site_url("administrator/delete_incidents"); ?>",
                {
                    incident_id: selectedIncidentIDs
                },
                function(data, status){
                    if (status === "success"){
                        //$('#incidents-table').DataTable().ajax.reload();
                        showMarkers(crimeMap);
                    }
                }
            );
            
             $('#modal_delete_incident').modal('hide');
       });
    });
</script>


<div class="modal fade" id="modal_delete_outpost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
            Delete police outpost
        </h4>
      </div>
      <div class="modal-body">
          Are you sure you want to delete <span id="del_selected_count"></span> police outpost? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="btnDeleteOutpostConfirm">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
            Confirm delete
        </button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
       $('#btnDeleteOutpostConfirm').on('click', function(){
           //alert('Delete incident confirmed');
           
            var selectedOutpostIDs = [];
            selectedOutpostIDs.push(currentOutpostMarkerClickedId);
            //for(var i=0; i<selected.length; i++){
                //selectedIncidentIDs.push(trim_id(selected[i]));
            //}
            
            $.post("<?php echo site_url("administrator/delete_outposts"); ?>",
                {
                    outpost_id: selectedOutpostIDs
                },
                function(data, status){
                    if (status === "success"){
                        //$('#incidents-table').DataTable().ajax.reload();
                        showMarkers(crimeMap);
                    }
                }
            );
            
             $('#modal_delete_outpost').modal('hide');
       });
       
    });
</script>


<div class="modal fade" id="modal_edit_crime" tabindex="-1" role="dialog" aria-labelledby="modal_edit_crimeLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_edit_crimeLabel">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            Edit Crime Incident
        </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
            <div class="form-group">
              <label for="crime-edit-crime" class="col-sm-2 control-label">Crime</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="crime-edit-crime" data-role="tagsinput"  >
              </div>
            </div>
            <div class="form-group">
                <label for="crime-edit-description" class="col-sm-2 control-label">Narrative</label>
              <div class="col-sm-10">
                 <textarea class="form-control" rows="6" id="crime-edit-description"></textarea>
              </div>
            </div>
           <div class="form-group">
              <label for="crime-edit-time" class="col-sm-2 control-label">Date/Time</label>
              <div class="col-sm-10">
                <!--<input type="text" class="form-control" id="crime-edit-time" > -->
                  
                    <div class='input-group date' id='crime-edit-time'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                  
              </div>
            </div>
           
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn-edit-incident-cancel" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-edit-incident"><i class="fa fa-check-square-o fa-lg"></i> Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    
    $('#crime-edit-crime').tagsinput({
        maxTags: 1,
        freeInput: false,
        trimValue: true,
        itemValue: 'value',
        itemText: 'text',
        typeaheadjs: {
            name: 'crime_names',
            displayKey: 'text',
            //valueKey: 'value',
            source: substringMatcher
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('#btn-edit-incident').on('click', function(){
            var incident_id = currentCrimeMarkerClickedId;
            var crime_id = $("#crime-edit-crime").val();
            var description = $('#crime-edit-description').val();
            var crime_time = $('#crime-edit-time').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
            
            $.post("<?php echo site_url("administrator/update_crime_incident"); ?>",
                {
                    incident_id: incident_id,
                    crime_id: crime_id,
                    description: description,
                    time: crime_time
                },
                function(data, status){
                    if (status === "success"){
                       //selectorInfoWindow.close();
                       //selectorMarker.setMap(null);
                       //showMarkers(crimeMap);
                       markersInfoWindow.close();
                    }
                }
            );
            
            $('#modal_edit_crime').modal('hide');
            //alert("Latitude: " + selectorMarker.getPosition().lat() + " Longitude: " + selectorMarker.getPosition().lng());
        });
        
        $('#btn-edit-incident-cancel').on('click', function(){
            $('#modal_edit_crime').modal('hide');
        });
    });
</script>


<div class="modal fade" id="modal_edit_outpost" tabindex="-1" role="dialog" aria-labelledby="modal_edit_outpostLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_edit_outpostLabel">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 
            Edit police outpost
        </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
            <div class="form-group">
              <label for="police-edit-name" class="col-sm-2 control-label">Outpost Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="police-edit-name" >
              </div>
            </div>
           <div class="form-group">
              <label for="police-edit-descriptionn" class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10">
                <!-- <input type="text" class="form-control" id="crime-add-description" > -->
                 <textarea class="form-control" rows="6" id="police-edit-description"></textarea>
              </div>
            </div>
           
           
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn-edit-outpost-cancel" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <button type="button" class="btn btn-primary" id="btn-edit-outpost"><i class="fa fa-check-square-o fa-lg"></i> Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
  
        $('#btn-edit-outpost').on('click', function(){
            
            var outpost_id = currentOutpostMarkerClickedId;
            var outpost_name = $("#police-edit-name").val();
            var description = $('#police-edit-description').val();
            
            $.post("<?php echo site_url("administrator/update_police_outpost"); ?>",
                {
                    outpost_id: outpost_id,
                    outpost_name: outpost_name,
                    outpost_desc: description
                },
                function(data, status){
                    if (status === "success"){
                       //selectorInfoWindow.close();
                       //selectorMarker.setMap(null);
                       //showMarkers(crimeMap);
                       markersInfoWindow.close();
                    }
                }
            );
            
            $('#modal_edit_outpost').modal('hide');
            //alert("Latitude: " + selectorMarker.getPosition().lat() + " Longitude: " + selectorMarker.getPosition().lng());
        });
        
        $('#btn-edit-outpost-cancel').on('click', function(){
            $('#modal_edit_outpost').modal('hide');
        });
    });
</script>