<div id="infoDiv" style="visibility:none;"></div>
<div id="errorDiv" style="visibility:none;"></div>
<pre id="patroniInfo">   </pre>
<button class="btn btn-info mb-2" id="btn" onclick="showInitModal()" style="float:left;margin-left:10px;visibility:none;"></button>

@component('modal-component',[
        "id" => "initModal",
        "title" => "Initilization",
    ])

    <form>
        <div class="form-group">
            <label for="patroniIpAdress">{{__('Patroni IP')}}</label>
            <input class="form-control" id="patroniIpAdress" aria-describedby="patroniIpAdressHelp" placeholder="{{__('Patroni IP')}}">
            <small id="patroniIpAdressHelp" class="form-text text-muted">{{__('Patroni Sunucunuzun IP adresini giriniz (192.168.1.10)')}}.</small>
        </div>
        <div class="form-group">
            <label for="etcdIpAdress">{{__('ETCD IP')}}</label>
            <input class="form-control" id="etcdIpAdress" aria-describedby="etcdIpAdressHelp" placeholder="{{__('ETCD IP')}}">
            <small id="etcdIpAdressHelp" class="form-text text-muted">{{__('ETCD sunucunuzun IP adresini giriniz.')}}</small>
        </div>
        <div class="form-group">
            <label for="patroniName">{{__('Patroni Adı')}}</label>
            <input class="form-control" id="patroniName" aria-describedby="patroniNameHelp" placeholder="{{__('Patroni Adı')}}">
            <small id="patroniNameHelp" class="form-text text-muted">{{__('Patroni adını giriniz.')}}</small>
        </div>
        <div class="form-group">
            <label for="patroniPassword">{{__('Parola')}}</label>
            <input type="password" class="form-control" id="patroniPassword" placeholder="{{__('Parola')}}">
            <small id="patroniPasswordHelp" class="form-text text-muted">{{__('Patroni parolasını giriniz.')}}</small>
        </div>
        <div class="form-group">
            <label for="scopeName">{{__('Scope adı')}}</label>
            <input class="form-control" id="scopeName" aria-describedby="scopeNameHelp" placeholder="{{__('Scope Adı')}}">
            <small id="scopeNameHelp" class="form-text text-muted">{{__('Scope adını giriniz. (Opsiyonel)')}}</small>
        </div>
    </form>

    <button class="btn btn-primary" onclick="initialize()" style="float:right;">{{__('initialize ')}}</button>
    
@endcomponent

<script>

    function showInitModal(){
      $('#initModal').modal("show");
    }

    var flag = false;
    function isYmlExists(){
        var form = new FormData();
        request(API('is_yml_exists'), form, function(response) {
            message = JSON.parse(response)["message"];
            $('#infoDiv').html(
                '<div class="alert alert-info d-flex align-items-center" role="alert">' +
                    '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>' +
                    '<i class="fas fa-icon mr-2"></i>' +
                    '<div>'+
                        '{{__("patroni.yml file detected on the server ! If you want to reinitialize, you can use the button.")}}'+
                    '</div>'+
                '</div>');
            
            let button = document.getElementById("btn");
            button.innerText = '{{__("Reinitalize")}}';
            button.style.visibility = "visible";
            flag = true;
            info();
            
        }, function(response) {
            $('#errorDiv').html(
                    '<div class="alert alert-danger d-flex align-items-center" role="alert">' +
                        '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#exclamation-triangle-fill"/></svg>' +
                        '<i class="fas fa-icon mr-2"></i>' +
                        '<div>'+
                            '{{__("Could not detect patroni.yml file on the server ! You need to initialize it.")}}'+
                        '</div>'+
                    '</div>');
            document.getElementById("addCluster_li").style.display = "none";
            let button = document.getElementById("btn");
            button.innerText = '{{__("Initalize")}}';
            button.style.visibility = "visible";
            flag = false;
        });
        
    }
    function initialize(){
        if(flag){
            reinit();
        }
        else{
            init();
        }
    }

    function info(){
        var form = new FormData();

        request(API('info'), form, function(response) {
            message = JSON.parse(response)["message"];
            $("#patroniInfo").text(message);

        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message,'error',2000);

        });
    }
    function init(){
        var form = new FormData();

        patroniIpAdress = document.getElementById("patroniIpAdress").value;
        etcdIpAdress = document.getElementById("etcdIpAdress").value;
        patroniName = document.getElementById("patroniName").value;
        patroniPassword = document.getElementById("patroniPassword").value;
        scopeName = document.getElementById("scopeName").value;

        form.append("patroniIpAdress",patroniIpAdress);
        form.append("etcdIpAdress",etcdIpAdress);
        form.append("patroniName",patroniName);
        form.append("patroniPassword",patroniPassword);
        form.append("scopeName",scopeName);


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

        patroniIpAdress = document.getElementById("patroniIpAdress").value;
        etcdIpAdress = document.getElementById("etcdIpAdress").value;
        patroniName = document.getElementById("patroniName").value;
        patroniPassword = document.getElementById("patroniPassword").value;
        scopeName = document.getElementById("scopeName").value;

        form.append("patroniIpAdress",patroniIpAdress);
        form.append("etcdIpAdress",etcdIpAdress);
        form.append("patroniName",patroniName);
        form.append("patroniPassword",patroniPassword);
        form.append("scopeName",scopeName);

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
