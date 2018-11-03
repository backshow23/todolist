<div class="container">
    <div class="row">
        <div id='calendar'></div>
            <div id='datepicker'></div>
            <div class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Create Work</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label class="col-xs-4" for="title">Event title</label>
                                    <span class="col-xs-4"><input class="form-control" type="text" name="work_name" id="title" /></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label class="col-xs-4" for="starts-at">Starts at</label>
                                    <span class="col-xs-4"><input class="form-control" type="text" name="starting_date" id="starts-at" /></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label class="col-xs-4" for="ends-at">Ends at</label>
                                    <span class="col-xs-4"><input class="form-control" type="text" name="ending_date" id="ends-at" /></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label class="col-xs-4" for="ends-at">Status</label>
                                    <span class="col-xs-4">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">Planning</option>
                                            <option value="2">Doing</option>
                                            <option value="3">Complete</option>
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" onclick="$('.unbind-event').unbind('click')" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary unbind-event" id="update-event">Save changes</button>
                            <button type="button" class="btn btn-primary unbind-event" id="create-event">Create new</button>
                            <button type="button" class="btn btn-primary unbind-event" id="delete-event">Delete work</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
    </div>
</div>
<script type="text/javascript">
function createWork(){
    $("#create-event").show();
    var title   = $('#title').val();
    var starts  = $('#starts-at').val();
    var ends    = $('#ends-at').val();
    var status  = $('#status').val();
    if (title!='') {
        $('#create-event').unbind('click');
        $.ajax({
            type:'POST',
            url:'<?php echo URL ?>/work/addwork',
            data:{create:'true',title:title,starts:starts,ends:ends,status:status},
            dataType:'json',
            success:function(response){
                if(typeof response.status != 'undefined' && response.status==200 ){
                    //alert('here')
                    $('#calendar').fullCalendar('refetchEvents');
                }else{
                    alert(response.message);
                }
            }
        });
        // Clear modal inputs
        $('.modal').find('input').val('');
        // hide modal
        $('.modal').modal('hide');
    }else{
        alert('Title is required')
    }
}
function delWork(id){
    if(confirm("Are you sure ?")){
        $("#delete-event").unbind('click');
        $.ajax({
            type:'POST',
            url:'<?php echo URL ?>/work/deleteWork',
            data:{del:'true',id:id},
            dataType:'json',
            success:function(response){
                if(typeof response.status != 'undefined' && response.status==200 ){
                    $('#calendar').fullCalendar('refetchEvents');
                }else{
                    alert(response.message);
                }
            }
        });
        // Clear modal inputs
        $('.modal').find('input').val('');
        // hide modal
        $('.modal').modal('hide');
    }
}
function updateWork(id){
    $("#update-event").unbind('click');
    var title   = $('#title').val();
    var starts  = $('#starts-at').val();
    var ends    = $('#ends-at').val();
    var status  = $('#status').val();
    $.ajax({
        type:'POST',
        url:'<?php echo URL ?>/work/updateWork',
        data:{update:'true',id:id,title:title,starts:starts,ends:ends,status:status},
        dataType:'json',
        success:function(response){
            if(typeof response.status != 'undefined' && response.status==200 ){
                $('#calendar').fullCalendar('refetchEvents');
            }else{
                alert(response.message);
            }
        }
    });
    // Clear modal inputs
    $('.modal').find('input').val('');
    // hide modal
    $('.modal').modal('hide');
}
$(document).ready(function() {
$('#calendar').fullCalendar({
    editable: true,
    header: {
        left:'prev,next today',
        center:'title',
        right: 'month,agendaWeek,agendaDay',
    },
    events: {
        url: '<?php echo URL.'/work/listwork'?>',
        cache: true
    },
    eventRender: function(event, element) {
        element.attr(
            {
                'data-status': event.status,
                'id'    :   'fc_'+event.id
            }
        );
    },
    timeFormat: 'H:mm',
    selectable: true,
    selectHelper: true,
    select: function(start, end) {
        $("#create-event").show();
        $("#delete-event").hide();
        $("#update-event").hide();
        $('.modal').find('input').val('');
        $('.modal').modal('show');
        $('#create-event').bind('click', function(e) {
            e.stopImmediatePropagation();
            createWork();
        });
    },
    eventClick: function(event, element) {
        var id = event.id;
        $("#create-event").hide();
        $("#delete-event").show();
        $("#update-event").show();
        var responseStart = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
        var responseEnd = moment(event.end).format('YYYY-MM-DD HH:mm:ss');
        $('#status option').prop('selected', 'selected').change();
        var statusSelected = $("#fc_"+id).attr('data-status');
        $('#status option').each(function(){
            if($(this).attr('value') == statusSelected){
                $(this).prop('selected', 'selected').change();
            }
        });
        $('.modal').modal('show');
        $('.modal').find('#title').val(event.title);
        $('.modal').find('#starts-at').val(responseStart);
        $('.modal').find('#ends-at').val(responseEnd);
        $("#delete-event").bind('click', function(e) {
            e.stopImmediatePropagation();
            delWork(id);
        });
        $("#update-event").bind('click', function(e) {
            e.stopImmediatePropagation();
            updateWork(id);
        });
    },
    eventDrop:function(event)
    {
        var id = event.id;
        var responseStart = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
        var responseEnd = moment(event.end).format('YYYY-MM-DD HH:mm:ss');
        $('#status option').prop('selected', 'selected').change();
        var statusSelected = $("#fc_"+id).attr('data-status');
        $('#status option').each(function(){
            if($(this).attr('value') == statusSelected){
                $(this).prop('selected', 'selected').change();
            }
        });
        $('.modal').find('#title').val(event.title);
        $('.modal').find('#starts-at').val(responseStart);
        $('.modal').find('#ends-at').val(responseEnd);
        
        updateWork(id);  
    }
});

// Bind the dates to datetimepicker.
// You should pass the options you need
$("#starts-at, #ends-at").datetimepicker({
	format: 'YYYY-MM-DD HH:mm:ss',
    minDate: moment(),
    sideBySide: true,
    keepOpen: true,
});

});
</script>
