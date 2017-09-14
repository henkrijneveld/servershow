<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 21-8-17
 * Time: 15:25
 */
include_once "../lib/common.php";
CommonFunctions::validateSession();

$l = new Linux();

$response = new stdClass();

$response->hwcpucores = $l->getCores();


$cpu = $l->getCPU();
if ($cpu) {
  $response->hwcputype = isset($cpu["cputype"])?$cpu["cputype"]:"N/A";
  $response->hwcpufreq = isset($cpu["cpufreq"])?$cpu["cpufreq"]:"N/A";
  $response->hwcpucache = isset($cpu["cpucache"])?$cpu["cpucache"]:"N/A";
  $response->hwmachinetype = $cpu["machinetype"];
}

$mem = $l->getMem();
$response->hwmemtotal = $mem[0] != Linux::NA ? ($mem[0]/1000)." MB":$mem[0];
$response->hwmemfree = $mem[1] != Linux::NA ? ($mem[1]/1000)." MB":$mem[1];
$response->hwmemcached = $mem[2] != Linux::NA ? ($mem[2]/1000)." MB":$mem[2];
$response->hwmemfreebuffers = $mem[3] != Linux::NA ? ($mem[4]/1000)." MB":$mem[3];

echo json_encode($response);
