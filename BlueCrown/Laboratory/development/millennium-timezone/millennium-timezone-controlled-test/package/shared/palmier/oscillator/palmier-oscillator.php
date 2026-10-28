<?php
declare(strict_types=1);
/** Include-safe Palmier oscillator engine; direct requests can return JSON or a small HTML readout. */
if (!defined('BZJ_OSCILLATOR_TTL')) define('BZJ_OSCILLATOR_TTL', 12345);
if (!defined('BZJ_OSCILLATOR_LOG_FILE')) define('BZJ_OSCILLATOR_LOG_FILE', __DIR__ . '/palmier-oscillator.log');
if (!function_exists('bzj_osc_now')) { function bzj_osc_now(): int { return time(); } }
if (!function_exists('bzj_osc_day_of_year')) { function bzj_osc_day_of_year(?int $t = null): int { return (int)gmdate('z', $t ?? time()) + 1; } }
if (!function_exists('bzj_osc_year_phase')) { function bzj_osc_year_phase(?int $t = null): float { $t=$t??time(); $y=(int)gmdate('Y',$t); $s=gmmktime(0,0,0,1,1,$y); $e=gmmktime(23,59,59,12,31,$y); return ($t-$s)/max(1,$e-$s); } }
if (!function_exists('bzj_osc_smooth')) { function bzj_osc_smooth(float $x): float { return (1-cos(M_PI*$x))/2; } }
if (!function_exists('bzj_osc_palmier_variance')) {
 function bzj_osc_palmier_variance(?int $t=null): float {
  $d=bzj_osc_day_of_year($t??time()); $anchors=[46=>1,74=>0,105=>1,151=>0,182=>0,213=>0,258=>1,288=>0,319=>1,365=>0]; ksort($anchors); $keys=array_keys($anchors);
  for($i=0;$i<count($keys);$i++){ $a=$keys[$i]; $b=$keys[($i+1)%count($keys)]; $va=$anchors[$a]; $vb=$anchors[$b]; if($b<$a)$b+=365; $dm=$d<$a?$d+365:$d; if($dm>=$a&&$dm<=$b){$f=($dm-$a)/max(1e-9,$b-$a);return round(($va+($vb-$va)*bzj_osc_smooth($f))*8.03787,5);} } return 0.0;
 }
}
if (!function_exists('bzj_osc_solar_variance')) { function bzj_osc_solar_variance(?int $t=null): float { $d=bzj_osc_day_of_year($t??time()); $p=(($d-3+365)%365)/365; return round((0.5-0.5*cos(2*M_PI*$p))*3.356,5); } }
if (!function_exists('bzj_osc_lunar_variance')) { function bzj_osc_lunar_variance(?int $t=null): float { $t=$t??time(); $days=bzj_osc_year_phase($t)*365.2422; $p=fmod($days,29.530588)/29.530588; return round((0.5-0.5*cos(2*M_PI*$p))*10.98,5); } }
if (!function_exists('bzj_osc_passover_variance')) { function bzj_osc_passover_variance(?int $t=null): float { $t=$t??time(); $y=(int)gmdate('Y',$t); $d=bzj_osc_day_of_year($t); $m=$y%19; $s=84+(int)round(($m/19)*31); $e=90+(int)round(($m/19)*36); if($d<$s||$d>$e)return 0.0; return round(sin(M_PI*(($d-$s)/max(1,$e-$s)))*8.03787,5); } }
if (!function_exists('bzj_osc_build_data')) {
 function bzj_osc_build_data(?int $t=null): array { $t=$t??time(); $lv=bzj_osc_lunar_variance($t);$sv=bzj_osc_solar_variance($t);$pv=bzj_osc_palmier_variance($t);$ps=bzj_osc_passover_variance($t);$osc=round(((min(1,$lv/10.98)+min(1,$sv/3.356)+min(1,$pv/8.03787)+min(1,$ps/8.03787))/4)*8.03787,5); return ['timestamp'=>$t,'datetime'=>gmdate('Y-m-d H:i:s',$t),'timezone'=>'UTC','lunar_variance'=>$lv,'solar_variance'=>$sv,'palmier_variance'=>$pv,'passover_variance'=>$ps,'palmier_max'=>8.03787,'oscillation'=>$osc,'oscillation_index'=>round($osc/100,10)]; }
}
if (!function_exists('bzj_osc_get_data')) {
 function bzj_osc_get_data(bool $force=false): array { $now=time();$last=null;if(is_file(BZJ_OSCILLATOR_LOG_FILE)){ $lines=@file(BZJ_OSCILLATOR_LOG_FILE,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);if($lines){for($i=count($lines)-1;$i>=0;$i--){$v=json_decode($lines[$i],true);if(is_array($v)&&isset($v['timestamp'])){$last=$v;break;}}}} if(!$force&&$last&&$now-(int)$last['timestamp']<BZJ_OSCILLATOR_TTL)return $last;$data=bzj_osc_build_data($now);$dir=dirname(BZJ_OSCILLATOR_LOG_FILE);if(!is_dir($dir))@mkdir($dir,0775,true);@file_put_contents(BZJ_OSCILLATOR_LOG_FILE,json_encode($data,JSON_UNESCAPED_SLASHES).PHP_EOL,FILE_APPEND|LOCK_EX);return $data; }
}
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath((string)$_SERVER['SCRIPT_FILENAME']) === __FILE__) { $data=bzj_osc_get_data(isset($_GET['force'])); if(($_GET['oscillation']??'')==='live'||($_GET['format']??'')==='json'){header('Content-Type: application/json; charset=UTF-8');echo json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);exit;} ?><!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Palmier Oscillator</title><style>body{font:16px Arial;background:#18192b;color:#fff;padding:24px}dt{font-weight:bold;margin-top:12px}dd{margin:4px 0}</style><h1>Palmier Oscillator</h1><dl><?php foreach($data as $k=>$v):?><dt><?=htmlspecialchars((string)$k,ENT_QUOTES,'UTF-8')?></dt><dd><?=htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8')?></dd><?php endforeach;?></dl><a href="?oscillation=live">JSON endpoint</a></html><?php }
