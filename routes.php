<?php

return [
    "index" => "HomeController@index",

    "get_hostname" => "HostnameController@get",
    "set_hostname" => "HostnameController@set",

    "is_yml_exists" => "PatroniController@isYmlExists",
    "init" => "PatroniController@init",
    "reinit" => "PatroniController@reinit",
    "hba" => "TableController@hbaTable",
    "add" => "PatroniController@add",
    "info" => "PatroniController@info",
    "cluster_info" => "PatroniController@clusterInfo",
    "get_details" => "PatroniController@getDetails"


];
