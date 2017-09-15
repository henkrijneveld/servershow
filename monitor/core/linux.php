<?php
/**
 * User: henk
 * Date: 20-8-17
 * Time: 20:22
 */

class Linux
{
  const NA = 'N/A'; // This means: result could not be determined

  public function getHostName()
  {
    return (php_uname('n'));
  }

  public function getUptime()
  {
    $s = @shell_exec('cat /proc/uptime');
    if (!$s) {
      $s = @file_get_contents("/proc/uptime");
    }
    if (!$s) {
      $s = self::NA;
    } else {
      $s = (int)strtok($s, ".");
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
   * returns array of three for file created by webserver/php user:
   *  ownername and ownerid
   *  groupname and groupid
   *  groupmembers
   */
  public function getUserGroup()
  {
    if (($temp_file = @tempnam(sys_get_temp_dir(), 'TMP') ) === false ||
      (@file_put_contents($temp_file, "test") === false)) {
      $temp_file = dirname(__FILE__)."/tmp.tmp"; // virtualbox will make the owner always vagrant in shared dirs.
      @unlink($temp_file);
      @file_put_contents($temp_file, "test");
    }
    $ret = $this->_getFileUserGroup($temp_file);
    @unlink($temp_file);

    return ($ret);
  }


  /*
   * returns array of five for this script (linux.php)
   *  0:ownername and 1:ownerid
   *  2:groupname and 3:groupid
   *  4: groupmembers in array
   */
  public function getUserGroupThisFile()
  {
    $ret = $this->_getFileUserGroup(__FILE__);

    return ($ret);
  }

  private function _getFileUserGroup($file)
  {
    $ret = array();
    $ret[] = self::NA;
    $ret[] = self::NA;
    $ret[] = self::NA;
    $ret[] = self::NA;
    $ret[] = array();

    if (($ownerid = fileowner($file)) === false) {
      return $ret;
    }

    $ownerinfo = posix_getpwuid($ownerid);
    if ($ownerinfo["name"]) $ret[0] = $ownerinfo["name"];
    $ret[1] = $ownerid;

    $groupid = filegroup($file);
    $groupinfo = posix_getgrgid($groupid);
    if ($groupinfo["name"]) $ret[2] = $groupinfo["name"];
    $ret[3] = $groupid;

    if (isset($groupinfo["members"])) $ret[4] = $groupinfo["members"];

    return $ret;
  }

  /*
   * Cached number of cores in session, if it exists
   */
  public function getCores()
  {
    if (!($cores = CommonFunctions::getNameValue("cpucores"))) {
      $s = @shell_exec('cat /proc/cpuinfo');
      if (!$s) {
        $s = @file_get_contents("/proc/cpuinfo");
      }

      $cores = 0;
      if ($s) {
        $processors = preg_split('/\s?\n\s?\n/', trim($s));

        $processed = array(); // contains the physical id's from processors already used

        foreach ($processors as $processor) {
          $lines = preg_split('/\n/', $processor, -1, PREG_SPLIT_NO_EMPTY);
          $properties = array();

          foreach ($lines as $line) {
            list($key, $value) = preg_split('/\s*:\s*/', trim($line));
            $properties[strtolower($key)] = $value;
          }

          if ($properties['core id'] != 0) continue;

          if (!isset($processed[$properties['physical id']])) {
            $processed[$properties['physical id']] = true;
            if (isset($properties['siblings'])) {
              $cores += $properties['siblings'];
            } else {
              $cores += $properties['cpu cores'];
            }
          }
        }
      }
      CommonFunctions::setNameValue("cpucores", $cores);
    }

    return $cores ? $cores : self::NA;
  }


  /*
   * Return array:
   *  cputype, cpufreq, cpucache
   *  machinetype
   */
  public function getCPU()
  {
    $ret = false; // nothing found

    $s = @shell_exec('cat /proc/cpuinfo');
    if (!$s) {
      $s = @file_get_contents("/proc/cpuinfo");
    }

    if ($s) {
      $cores = preg_split('/\s?\n\s?\n/', trim($s));

      $ret = array();
      foreach ($cores as $core)
      {
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
    } else {
        $ret["cputype"] = self::NA;
        $ret["cpufreq"] = self::NA;
        $ret["cpucache"] = self::NA;
        $ret["machinetype"] = self::NA;
    }

    return($ret);
  }

  public function getVMType()
  {
    $ret = false;
    $g = glob("/dev/disk/by-id/ata-*")[0];
    if ($g) {
      $g = basename($g);
      if (strpos($g, "VBOX")) {
        $ret = "Virtualbox";
      }
      if (strpos($g, "QEMU")) {
        $ret = "KVM, QEMU";
      }
    }

    return $ret;
  }

  /*
   * Return array:
   *  0: memtotal, 1: memfreetotal, 2: memfreecached, 3: memfreebuffers
   *
   * memfreecached and memfreebuffers are part of memfree total
   */
  public function getMem()
  {
    $ret = array(self::NA, self::NA, self::NA, self::NA); // nothing found

    $s = @shell_exec('cat /proc/meminfo');
    if (!$s) {
      $s = @file_get_contents("/proc/meminfo");
    }

    if ($s) {
      $data = explode("\n", $s);
      $meminfo = array();
      foreach ($data as $line) {
        list($key, $val) = explode(":", $line);
        $meminfo[$key] = (int) trim($val);
      }
      $ret[0] = $meminfo["MemTotal"];
      $ret[1] = $meminfo["MemFree"] + $meminfo["Cached"] + $meminfo["Buffers"];
      $ret[2] = $meminfo["Cached"];
      $ret[3] = $meminfo["Buffers"];
    }

    return($ret);
  }

  public function getPortOpen($port)
  {
    $ret = false;

    if ($fp = @fsockopen("127.0.0.1", $port, $errno, $errstr, 2) ) {
      $ret = is_resource($fp);
      fclose($fp);
    }

    return $ret;
  }

  /*
   * Get diskinfo for the file
   * [0]: total space
   * [1]: free space
   */
  public function getDiskInfo($file)
  {
    $ret = array();
    $ret[] = disk_total_space($file);
    $ret[] = disk_free_space($file);

    return $ret;

  }

  /*
   * Network. Return array
   * One row for every interface
   * row[0] interface name
   * row[1] TX bytes
   * row[2] RX bytes
   *
   */

  public function getNetwork()
  {
    $ret = array();
    $interfaces = glob("/sys/class/net/*")  ;
    foreach ($interfaces as $interface) {
      $retinterface = array();
      $retinterface[] = basename($interface);
      $retinterface[] = file_get_contents($interface . "/statistics/tx_bytes");
      $retinterface[] = file_get_contents($interface . "/statistics/rx_bytes");
      $ret[] = $retinterface;
    }

    if (count($ret) == 0) {
      $s = @file_get_contents("/proc/net/dev");
//      $s = @file_get_contents("redhatnetwork.test");
      if ($s) {
        // redhat
        $lines = explode("\n", $s);
        $lines = array_slice($lines, 2);
        foreach($lines as $line) {
          $newinterface = array();
          $parts = preg_split('/[:\s]+/', $line);
          if (isset($parts[1])) {
            $newinterface[] = $parts[1];
            if (isset($parts[10])) $newinterface[] = $parts[10];
            if (isset($parts[2])) $newinterface[] = $parts[2];
            $ret[] = $newinterface;
          }
        }
      }
    }

    return $ret;
  }

  /*
   * Load averages in fractions
   * [0]: 1 minute
   * [1]: 5 minutes
   * [2]: 15 minutes
   */
  public function getLoad()
  {
    $ret = array(self::NA, self::NA, self::NA);
    $load = @sys_getloadavg();
    if ($numcores = intval($this->getCores())) {
      for ($time = 0; $time < 3; $time++) {
        $ret[$time] = $load[$time] / $numcores;
      }
    }

    return $ret;
  }

}


