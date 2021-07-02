<ul class="nav nav-tabs" role="tablist" style="margin-bottom: 15px;">
    <li class="nav-item">
        <a class="nav-link active"  onclick="hbaTable()" href="#addCluster" data-toggle="tab">Add Node</a>
    </li>
</ul>

<div class="tab-content">
    <div id="addCluster" class="tab-pane active">
        @include('pages.list')
    </div>
</div>

<script>
   if(location.hash === ""){
        hbaTable();
    }
    function init(){
        var form = new FormData();
        request(API('init'), form, function(response) {
            message = JSON.parse(response)["message"];
            successModal(message);
        }, function(response) {
            let error = JSON.parse(response);
            failureModal(error.message);
        });
    }
    function reinit(){
        var form = new FormData();
        request(API('reinit'), form, function(response) {
            message = JSON.parse(response)["message"];
            showSwal('{{__("Successfully reinitalized.")}}','success',2000);
            hbaTable();
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message,'error',2000);
        });
    }
    async function failureModal(message){
        const { value: accept } = await Swal.fire({
        type: 'error',
        title: 'Error !',
        text: message,
        confirmButtonColor: '#d33',
        confirmButtonText:
          'Continue <i class="fa fa-arrow-right"></i>',
      })
      if (accept) {
        //window.location.reload();
        reinit();
      }
    }
    async function successModal(message){
      const { value: accept } = await Swal.fire({
        type: 'success',
        title: 'Success !',
        text: message,
        confirmButtonText:
          'Continue <i class="fa fa-arrow-right"></i>',
      })
      if (accept) {
        window.location.reload();
      }
    }
</script>