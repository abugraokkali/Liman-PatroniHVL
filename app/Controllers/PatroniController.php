<?php
namespace App\Controllers;
use Liman\Toolkit\Shell\Command;

class PatroniController
{
	function init(){
		$etcd = extensionDb('etcdAdress');
		$scope = extensionDb('scopeName');
		$patroniName = extensionDb('patroniName');
        $patroniIp = $this->getIP();
        
		$init = "/etc/gopatroniyml init --etcd ". $etcd ." -i " . $patroniIp ." -n ". $patroniName ." --path /tmp/patroni.yml";
		$initScope = "/etc/gopatroniyml init --etcd ". $etcd ." -i ". $patroniIp ." -n ". $patroniName ." -s ". $scope ." --path /tmp/patroni.yml";
		
		if($scope != ""){
			shell_exec($initScope." > /tmp/errorLog");

		}else{
			shell_exec($init." > /tmp/errorLog");
		}
		
		$remote_path = '/tmp/patroni.yml';
		putFile('/tmp/patroni.yml', $remote_path);
		runCommand(sudo().'mv /tmp/patroni.yml /etc/');
        $this->restart();
        return respond("Successfully initalized.",200);
		
	}

	function reinit(){
		$etcd = extensionDb('etcdAdress');
		$scope = extensionDb('scopeName');
		$patroniName = extensionDb('patroniName');
        $patroniIp = $this->getIP();

		$init = "/tmp/gopatroniyml reinit --etcd ". $etcd ." -i " . $patroniIp ." -n ". $patroniName ." --path /tmp/patroni.yml";
		$initScope = "/tmp/gopatroniyml reinit --etcd ". $etcd ." -i ". $patroniIp ." -n ". $patroniName ." -s ". $scope ." --path /tmp/patroni.yml";
		
		if($scope != ""){
			shell_exec($initScope." 2>&1 /tmp/errorLog");
		}else{
			shell_exec($init." 2>&1 /tmp/errorLog");
		}
        $this->restart();
        return respond("Successfully reinitalized.",200);
	}

	function restart(){
        runCommand(sudo() . "systemctl restart patroni.service");
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

    function isYmlExists(){
        $command = 'test -e /etc/patroni.yml && echo 1 || echo 0';
        $flag = runCommand(sudo() . $command);

        if($flag){
            return respond(true,200);
        }
        else{
            return respond(false,201);
        }

    }

    function getIP(){
        $command = "hostname -I | awk '{print $1}'";
        $ip = runCommand(sudo().$command);
        return $ip;
    }

}