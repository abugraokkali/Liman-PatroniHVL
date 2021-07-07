
<div class="table-responsive" id="clusterInfoTable"></div>
@component('modal-component',[
        "id" => "detailsModal",
        "title" => "Details",
        "footer" => [
            "text" => "OK",
            "class" => "btn-success",
            "onclick" => "hideDetailsModal()"
        ]
    ])
    
@endcomponent

<script>

     function clusterInfo(){
        var form = new FormData();

        request(API('cluster_info'), form, function(response) {
            $('#clusterInfoTable').html(response).find('table').DataTable(dataTablePresets('normal'));
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message,'error',2000);

        });
    }

    function getDetails(line){
        var form = new FormData();
        let api_url = line.querySelector("#api_url").innerHTML;
        form.append("api_url",api_url);

        request(API('get_details'), form, function(response) {
            message = JSON.parse(response)["message"];
            $('#detailsModal').modal("show");
            $('#detailsModal').find('.modal-body').html(
                "<pre>"+message+"</pre>"
            );
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message,'error',2000);

        });
    }
    function hideDetailsModal(){
        $('#detailsModal').modal("hide");

    }

    

    
</script>