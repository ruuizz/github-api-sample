/**
 * Initates Datatable
 * @param {*} datatable_settings 
 */
function githubRepoBundleInit(datatable_settings)
{
    $('#php-repos').initDataTables(datatable_settings, {
        searching: true,
    })
    
    .then(function(datatable) {
        datatable.on('draw', function() {
        
        })
    });
}

/**
 * Calls route to trigger search and store repositories
 * Interacts with DOM elements for UX
 */
function refreshData() {
    $("#refresh-button").click(function(e) {
        e.preventDefault();
        var path = Routing.generate('ajax-refresh-data'); 
        $('#refresh-button').hide();
        $('#refresh-data').show();

        makeProgress();

        $.ajax({
            type: "GET",
            url: path,
            success: function(result) {
                $('#refresh-button').show();
                $('#refresh-data').hide();
                $('#php-repos .dataTable').DataTable().ajax.reload();

            },
            error: function(result) {
                console.log(result);
            }
        });
    }); 
}

/**
 * Calls route for repor details
 */
function showDetails(id) {
    var path = Routing.generate('ajax-repo-details', { data: id });
    var context = $('.repo-details-'+id).data('context');
    $('#appModal .modal-body').html('<div id="refresh-data" class="spinner-border" role="status" style="margin:auto;display:block;"> <span class="sr-only">Loading...</span> </div>'); 

    $.ajax({
        type: 'POST',
        url: path,
        data: context, 
        success: function(data, dataType) {
            $('#appModal .modal-body').html(data);
        },
        error: function(result) {
            console.log('error');
            console.log(result);
        }
    });
}

/**
 * Progress bar
 */
function makeProgress(){
    i = 0;
    progress = 0;
    e = "#refresh-data-progress";
    $(e).show();

    (function theLoop (i, progress, e) {
        setTimeout(function () {
        $(e).css("width", progress + "%");
          progress++
          if (--i) {                 

            if(progress == 100){
                $(e).css("width", "0%");
                $(e).hide();
                return;
            }

            theLoop(i, progress, e);  
          }
        }, 500);
      })( i, progress, e);
}
