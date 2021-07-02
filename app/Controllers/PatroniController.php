<?php
namespace App\Controllers;
use Liman\Toolkit\Shell\Command;

class PatroniController
{
	function init(){
		//sudo ./gopatroniyml reinit --etcd 192.168.1.67 --scope postgreskume (parola random)
		$etcd = extensionDb('etcdAdress');
		$scope = extensionDb('scopeName');

		$init = "bash -c 'DEBIAN_FRONTEND=noninteractive /tmp/gopatroniyml init --etcd " . $etcd . " > /tmp/outputOfGoPatroni 2>&1'";
		$initScope = "bash -c 'DEBIAN_FRONTEND=noninteractive /tmp/gopatroniyml init --etcd " . $etcd . " --scope " . $scope . " > /tmp/outputOfGoPatroni 2>&1'";
		
		if($scope != ""){
			runCommand(sudo() . $initScope);
		}else{
			runCommand(sudo() . $init);
		}
		
		$output = "cat /tmp/outputOfGoPatroni";
		$output = runCommand(sudo() . $output);

		if(strpos($output, 'If you want to reinitialize patroni.yml file') !== false) {
			return respond("There is already a patroni.yml file. Note that this action will overwrite patroni.yml file and reset it to its first initalized state !",201);
        }
		else{
			return respond("Successfully initalized.",200);
		}
	}

	function reinit(){
		$etcd = extensionDb('etcdAdress');
		$scope = extensionDb('scopeName');

		$reinit = "bash -c 'DEBIAN_FRONTEND=noninteractive /tmp/gopatroniyml reinit --etcd " . $etcd . " > /tmp/outputOfGoPatroni 2>&1'";
		$reinitScope = "bash -c 'DEBIAN_FRONTEND=noninteractive /tmp/gopatroniyml reinit --etcd " . $etcd . " --scope " . $scope . " > /tmp/outputOfGoPatroni 2>&1'";

		if($scope != ""){
			$output = runCommand(sudo() . $reinitScope);
		}else{
			$output = runCommand(sudo() . $reinit);
		}

		$this->activate();

		return respond("Successfully reinitalized.",200);
	}

	function add(){
		validate([
			'ip' => 'required|string',
		]);

		$ip = request('ip');
		$add = "/tmp/gopatroniyml --p " . $ip;
		runCommand(sudo() . $add);

		return respond("New node successfully added.",200);
	}

	function activate(){
        runCommand(sudo() . "systemctl restart patroni.service");
    }
}