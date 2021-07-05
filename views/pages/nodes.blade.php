@component('modal-component',[
        "id" => "addNodeModal",
        "title" => "Add New Node",
        "footer" => [
            "text" => "Add",
            "class" => "btn-success",
            "onclick" => "addNode()"
        ]
    ])

    @include('inputs', [
        "inputs" => [
            "IP Address" => "ip:text:Enter the ip address of the server you will add (Example : 192.168.1.10).",
        ]
    ])
@endcomponent

<button class="btn btn-success" onclick="showAddNodeModal()" style="float:left;">Add New Node</button>
<br />
<br />
<div class="table-responsive hbaTable" id="hbaTable"></div>


<script>
    function addNode(){
        var form = new FormData();
        form.append("ip",$('#addNodeModal').find('input[name=ip]').val());
        request(API('add'), form, function(response) {
            message = JSON.parse(response)["message"];
            $('#addNodeModal').modal("hide");
            showSwal(message,'success',2000);
            hbaTable();
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message,'error',2000);
        });
    }
    function showAddNodeModal(){
        $('#addNodeModal').modal("show");
    }
    
    function hbaTable(){
        var form = new FormData();
        request(API('hba'), form, function(response) {
            $('.hbaTable').html(response).find('table').DataTable(dataTablePresets('normal'));
            
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message,'error',2000);
        });
    }

    

    
</script>