<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 20-8-17
 * Time: 20:22
 */

class Linux
{
  const NA = 'N/A';

  public function getHostName()
  {
    return (php_uname('n'));
  }

  public function getUptime()
  {
    $s = shell_exec('cat /proc/uptime');
    if (!$s) {
      $s = @file_get_contents("/proc/uptime");
    }
    if ($s) {
      $s = CommonFunctions::makereadableseconds($s);
    } else {
      $s = self::NA;
    }

    return ($s);
  }

  public function getOS()
  {
    return (php_uname("s"));
  }

  public function getRelease()
  {
    return (php_uname("r"));
  }

  public function getVersion()
  {
    return (php_uname("v"));
  }

  public function getMachineType()
  {
    return (php_uname("m"));
  }

  /*
   * returns array of three:
   *  ownername and ownerid
   *  groupname and groupid
   *  groupmembers
   */
  public function getUserGroup()
  {
    $temp_file = tempnam(sys_get_temp_dir(), 'TMP');
    file_put_contents($temp_file, "test");
    $ownerid = fileowner($temp_file);
    $groupid = filegroup($temp_file);
    $ownerinfo = posix_getpwuid($ownerid);
    $ownername = $ownerinfo && isset($ownerinfo["name"])?$ownerinfo["name"]:"";
    $groupinfo = posix_getgrgid($groupid);
    $groupname = $groupinfo && isset($groupinfo["name"])?$groupinfo["name"]:"";
    $groupmembers = $groupinfo && isset($groupinfo["members"])?implode(" ", $groupinfo["members"]):"";
    unlink($temp_file);
    $ret = array();
    $ret[] = $ownername." (".$ownerid.")";
    $ret[] = $groupname." (".$groupid.")";
    $ret[] = $groupmembers;

    return ($ret);
  }

  /*
   * Return array:
   *  cpucores, cputype, cpufreq, cpucache
   *  machinetype
   */
  public function getCPU()
  {
    $ret = false; // nothing found

    $s = shell_exec('cat /proc/cpuinfo');
    if (!$s) {
      $s = @file_get_contents("/proc/cpuinfo");
    }
    if ($s) {
      $cores = preg_split('/\s?\n\s?\n/', trim($s));
      $numcores = 0;

      $ret = array();
      foreach ($cores as $core)
      {
        $numcores++;
        $details = preg_split('/\n/', $core, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($details as $detail)
        {
          list($key, $value) = preg_split('/\s*:\s*/', trim($detail));

          switch (strtolower($key))
          {
            case 'model name':
            case 'cpu model':
            case 'cpu':
            case 'processor':
              $ret["cputype"] = $value;
              break;

            case 'cpu mhz':
            case 'clock':
              $ret["cpufreq"] = $value.' MHz';
              break;

            case 'cache size':
            case 'l2 cache':
              $ret["cpucache"] = $value;
              break;
          }
        }
      }
      $ret["cpucores"] = $numcores;

      if (strpos($s, "hypervisor") === false) {
        $ret["machinetype"] = "bare metal or undetected";
      } else {
        $ret["machinetype"] = "virtual machine";
      }

      $s = shell_exec('cat /proc/1/cgroup');
      if (!$s) {
        $s = @file_get_contents("/proc/1/cgroup");
      }
      if ($s) {
        if (strpos($s, "/lxc/") || strpos($s, "/docker/")) {
          $ret["machinetype"] = "docker container";
        }
      }

      $s = $this->getVMType();
      if ($s) {
        $ret["machinetype"] .= " (".$s.")";
      }
    }

    return($ret);
  }

  public function getVMType()
  {
    $ret = false;
    $g = glob("/dev/disk/by-id/ata-*")[0];
    if ($g) {
      $g = basename($g[0]);
      $g = substr($g, 4);
      if (strpos($g, "VBOX")) {
        $ret = "Virtualbox";
      }
    }

    return $ret;
  }

  /*
   * Return array:
   *  memtotal, memfree
   */
  public function getMem()
  {
    $ret = false; // nothing found

    $s = shell_exec('cat /proc/meminfo');
    if (!$s) {
      $s = @file_get_contents("/proc/meminfo");
    }
    if ($s) {
      $ret = array();
      $data = explode("\n", $s);
      $meminfo = array();
      foreach ($data as $line) {
        list($key, $val) = explode(":", $line);
        $meminfo[$key] = (int) trim($val);
      }
      $ret["memtotal"] = $meminfo["MemTotal"];
      $ret["memfree"] = $meminfo["MemFree"] + $meminfo["Cached"] + $meminfo["Buffers"];
    }

    return($ret);
  }


}


