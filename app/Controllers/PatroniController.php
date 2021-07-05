<?php
namespace App\Controllers;
use Liman\Toolkit\Shell\Command;

class PatroniController
{
	function init(){

		validate([
			'patroniIpAdress' => 'required|string',
			'etcdIpAdress' => 'required|string',
			'patroniName' => 'required|string',
			'patroniPassword' => 'required|string'

		]);

		$patroniIp = request("patroniIpAdress");
        $etcd = request("etcdIpAdress");
        $patroniName = request("patroniName");
        $password = request("patroniPassword");
        $scope = request("scopeName");

		$init = "/etc/gopatroniyml init --etcd ". $etcd ." -i " . $patroniIp ." -n ". $patroniName ." --password ". $password ." --path /tmp/patroni.yml";
		$initScope = "/etc/gopatroniyml init --etcd ". $etcd ." -i ". $patroniIp ." -n ". $patroniName ." --password ". $password." -s ". $scope ." --path /tmp/patroni.yml";
		
		if($scope != ""){
			shell_exec($initScope." > /tmp/output.txt");
			shell_exec("echo ".$initScope." > /tmp/commandLog.txt");

		}else{
			shell_exec($init." > /tmp/output.txt");
			shell_exec("echo ".$init." > /tmp/commandLog.txt");


		}
		
		$remote_path = '/tmp/patroni.yml';
		putFile('/tmp/patroni.yml', $remote_path);
		runCommand(sudo().'mv /tmp/patroni.yml /etc/');
        $this->restart();
        return respond("Successfully initalized.",200);
		
	}

	function reinit(){

		validate([
			'patroniIpAdress' => 'required|string',
			'etcdIpAdress' => 'required|string',
			'patroniName' => 'required|string',
			'patroniPassword' => 'required|string'

		]);

		$patroniIp = request("patroniIpAdress");
        $etcd = request("etcdIpAdress");
        $patroniName = request("patroniName");
        $password = request("patroniPassword");
        $scope = request("scopeName");

		$init = "/etc/gopatroniyml reinit --etcd ". $etcd ." -i " . $patroniIp ." -n ". $patroniName ." --password ". $password ." --path /tmp/patroni.yml";
		$initScope = "/etc/gopatroniyml reinit --etcd ". $etcd ." -i ". $patroniIp ." -n ". $patroniName ." --password ". $password." -s ". $scope ." --path /tmp/patroni.yml";
		
		if($scope != ""){
			shell_exec($initScope." > /tmp/output.txt");
			shell_exec("echo ".$initScope." > /tmp/commandLog.txt");

		}else{
			shell_exec($init." > /tmp/output.txt");
			shell_exec("echo ".$init." > /tmp/commandLog.txt");
		}

		$remote_path = '/tmp/patroni.yml';
		putFile('/tmp/patroni.yml', $remote_path);
		runCommand(sudo().'mv /tmp/patroni.yml /etc/');
        $this->restart();
        return respond("Successfully reinitalized.",200);
	}

	function info(){
		$output = runCommand(sudo() . "patronictl -c /etc/patroni.yml list");
		return respond($output,200);

	}

	function restart(){
        runCommand(sudo() . "systemctl restart patroni.service");
    }
    
	function add(){
		validate([
			'ip' => 'required|string',
		]);

		$ip = request('ip');
		$add = "/etc/gopatroniyml --appPatNode " . $ip. " --path /tmp/patroni.yml";
		shell_exec($add);
		
		$remote_path = '/tmp/patroni.yml';
		putFile('/tmp/patroni.yml', $remote_path);
		runCommand(sudo().'mv /tmp/patroni.yml /etc/');
        $this->restart();

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