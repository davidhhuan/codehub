<?php
/*
exporter是基于rdiff-backup的git增量导出工具，系统需要安装php
使用方法：
	php git-export.php git@git.ijie.com:local.git -o local -b a4b27b54d0d8aba482e573dfbb69bf734972c98c -e a4b27b54d0d8aba482e573dfbb69bf734972c98c
	-o：指定输出目录
	-b：指定开始版本号
	-e：指定结束版本号
*/
$current_dir = getcwd();
$args_num = $argc;
$args_list = $argv;
$project = '';
$rev_begin = '';
$rev_end = '';

if(isset($args_list[1])) $git_url = $args_list[1];
for($i = 0; $i < $args_num; $i++){
	switch($args_list[$i]){
		case '-o':
			$out_file = $args_list[$i+1];
			break;
		case '-b':
			$rev_begin = $args_list[$i+1];
			break;
		case '-e':
			$rev_end = $args_list[$i+1];
			break;
	}
}

if(!isset($git_url)) die("You must special git url!");
if(!isset($out_file)) die("You must special output file!");

$git_info = explode(':', $git_url);
if(isset($git_info[1])){
	$project_info = explode('.', $git_info[1]);
	$project = $project_info[0];
}
if(empty($project)) die("Error, please check your params");

$day = date('Y-m-d');
$home_dir = $_SERVER['HOME'];
$tmp_base_dir = $home_dir.'/git_export/'.$project.'/release-'.$day;
$tmp_dir = $tmp_base_dir;
$i = 1;
while(file_exists($tmp_dir.'/out/')&&is_dir($tmp_dir.'/out/')){
	$tmp_dir = $tmp_base_dir.'-'.$i;
	$i++;
}
$begin_dir = $tmp_dir.'/begin';
$end_dir = $tmp_dir.'/end';
//$bak_dir = $tmp_dir.'/bak';
$out_dir = $tmp_dir.'/out/';
$diff_log = $tmp_dir.'/diff.log';
$release_log = $tmp_dir.'/release.log';

if(!is_dir($tmp_dir)) mkdir($tmp_dir, 0755, true);
if(!is_dir($begin_dir)) mkdir($begin_dir, 0755, true);
if(!is_dir($end_dir)) mkdir($end_dir, 0755, true);
//if(!is_dir($bak_dir)) mkdir($bak_dir, 0755, true);
if(!is_dir($out_dir)) mkdir($out_dir, 0755, true);

file_put_contents($release_log, "Url: ".$git_url."\n"."Output: ".$out_file."\n"."Revision Begin: ".$rev_begin."\n"."Revision End: ".$rev_end."\n");

chdir($end_dir);
exec("git clone $git_url .");
exec("git commit -a -m 'git-exporter'");
if(isset($rev_end)) exec("git checkout $rev_end");
recurse_copy($end_dir, $begin_dir);
chdir($begin_dir);
exec("git commit -a -m 'git-exporter'");
if(isset($rev_begin)) exec("git checkout $rev_begin");
chdir($tmp_dir);
if(empty($rev_begin)&&empty($rev_end)){
	recurse_copy($end_dir, $out_dir);
	recurse_copy($out_dir, $out_file);
	die('done');
}

//exec("rdiff-backup $begin_dir $bak_dir");
//exec("rdiff-backup --compare-hash-at-time $end_dir $bak_dir 2>$diff_log");
echo "diff -rq $begin_dir $end_dir 2>$diff_log";
exec("diff -rq $begin_dir $end_dir>$diff_log");

if(!is_file($diff_log)) die("error, please check you params");

$lines = file($diff_log);
$src_dir = $end_dir.'/';
foreach($lines as $line){
	$new_flag = false;
	if(!preg_match('/and (\/[\w\W]*?)differ/', $line, $matches)){
		if(!preg_match('/in (\/[\w\W]*?)[\n\r]/', $line, $matches)) continue;
		$new_flag = true;
		$matches[1] = str_replace(': ', '/', $matches[1]);
	}
	$file_path = trim($matches[1]);
	$src_file_path = $file_path;
	$file_path = str_replace($end_dir.'/', '', $file_path);
	$file_path_info = explode('/', $file_path);
	$dir_path = $out_dir.dirname($file_path);
	
	if(in_array($file_path_info[0], array('.', '.buildpath', '.project', '.settings', '.git'))) continue;
	if(in_array('.git', $file_path_info)) continue;
	if(!is_dir($dir_path)) mkdir($dir_path, 0755, true);
	$src_file_path = $src_dir.$file_path;
	$target_file_path = $out_dir.$file_path;
	
	echo $src_file_path.'=>'.$target_file_path."\n";
	if(is_dir($src_file_path)&&$new_flag) recurse_copy($src_file_path,$target_file_path);
	if(is_file($src_file_path)) copy($src_file_path, $target_file_path);
}

recurse_copy($out_dir,$out_file);

echo 'done';
function recurse_copy($src,$dst) {
    $dir = opendir($src);
    if(!is_dir($dst)) mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);

	return true;
} 

function rrmdir($directory, $empty = false) {
    if(substr($directory,-1) == "/") {
        $directory = substr($directory,0,-1);
    }

    if(!file_exists($directory) || !is_dir($directory)) {
        return false;
    } elseif(!is_readable($directory)) {
        return false;
    } else {
        $directoryHandle = opendir($directory);
       
        while ($contents = readdir($directoryHandle)) {
            if($contents != '.' && $contents != '..') {
                $path = $directory . "/" . $contents;
               
                if(is_dir($path)) {
                    rrmdir($path);
                } else {
                    unlink($path);
                }
            }
        }
       
        closedir($directoryHandle);

        if($empty == false) {
            if(!rmdir($directory)) {
                return false;
            }
        }
       
        return true;
    }
} 
