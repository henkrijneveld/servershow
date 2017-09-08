<?php
/**
 * Created by PhpStorm.
 * User: henk
 * Date: 4-9-17
 * Time: 9:52
 */
?>
<h2>Resources</h2>



<table>
  <tr>
    <td>Total disk space:</td>
    <td id="rstotaldisk">-</td>
  </tr>

  <tr>
    <td>Free disk space:</td>
    <td id="rsfreedisk">-</td>
  </tr>
</table>


<div id="diskouter">
  <div id="diskinner">
free&nbsp;
  </div>
</div><br/><br/>

<table>
  <tr>
    <td>Memory Total:</td>
    <td id="rsmemtotal">-</td>
  </tr>

  <tr>
    <td>Memory Free:</td>
    <td id="rsmemfree">-</td>
  </tr>
</table>

<div id="memouter">
  <div id="meminner">
    free&nbsp;
  </div>
</div><br/><br/>

<table id="loadavg">
  <tr>
    <td>Load 1 minute:</td><td><div id="avg1outer">
        <div id="avg1inner">

        </div>
      </div></td><td id="rsload1">-</td>
  </tr>
  <tr>
    <td>Load 5 minutes:</td><td><div id="avg5outer">
        <div id="avg5inner">

        </div>
      </div></td><td id="rsload5">-</td>
  </tr>
  <tr>
    <td>Load 15 minute:</td><td><div id="avg15outer">
        <div id="avg15inner">

        </div>
      </div></td><td id="rsload15">-</td>
  </tr>
</table>
