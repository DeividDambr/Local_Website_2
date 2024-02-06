$(document).ready(function () {
    $('#dalykaiTable').DataTable();
    $('#dalykaiInfoTable').DataTable();
});

var i = false;

$('#switchTable').on('click', function(){
    if(!i){
        $('#dalykaiTableContainer').show();
        $('#dalykaiInfoTableContainer').hide();
        $('#groupAdd').show();
        $('#entryAdd').hide();
        $('#entryTitle').hide();
        $('#groupTitle').show();
        i = true;
    }
    else{
        $('#dalykaiTableContainer').hide();
        $('#dalykaiInfoTableContainer').show();
        $('#groupAdd').hide();
        $('#entryAdd').show();
        $('#entryTitle').show();
        $('#groupTitle').hide();
        i = false;
    }
});