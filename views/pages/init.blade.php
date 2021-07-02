<div id="infoDiv" style="visibility:none;"></div>
<div id="errorDiv" style="visibility:none;"></div>
<button class="btn btn-info mb-2" id="btn" style="float:left;margin-left:10px;visibility:none;"></button>


<script>
    function isYmlExists(){
        var form = new FormData();
        request(API('is_yml_exists'), form, function(response) {
            message = JSON.parse(response)["message"];
            $('#infoDiv').html(
                '<div class="alert alert-info d-flex align-items-center" role="alert">' +
                    '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>' +
                    '<i class="fas fa-icon mr-2"></i>' +
                    '<div>'+
                        '{{__("Sunucuda patroni.yml dosyası tespit edildi ! Reinitialize etmek isterseniz butonu kullanabilirsiniz.")}}'+
                    '</div>'+
                '</div>');

            let button = document.getElementById("btn");
            button.onclick = function() {reinit()};
            button.innerText = '{{__("Reinitalize")}}';
            button.style.visibility = "visible";
            
        }, function(response) {
            $('#errorDiv').html(
                    '<div class="alert alert-danger d-flex align-items-center" role="alert">' +
                        '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#exclamation-triangle-fill"/></svg>' +
                        '<i class="fas fa-icon mr-2"></i>' +
                        '<div>'+
                            '{{__("Sunucuda patroni.yml dosyası tespit edilemedi ! Initialize etmeniz gerekiyor.")}}'+
                        '</div>'+
                    '</div>');
            document.getElementById("addCluster_li").style.display = "none";
            let button = document.getElementById("btn");
            button.onclick = function() {init()};
            button.innerText = '{{__("Initalize")}}';
            button.style.visibility = "visible";
        });
        
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
            successModal(message);
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message,'error',2000);
        });
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
        window.location.reload();
      }
    }
    
</script>
