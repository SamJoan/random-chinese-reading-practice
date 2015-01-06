<?php

require "vendor/autoload.php";

function tpl_render($tpl_file, $tpl_vars) {
  $me = new Mustache_Engine;
  $tpl_file = var_filter($tpl_file);
  $tpl = file_get_contents("templates/" . $tpl_file . '.tpl');
  return $me->render($tpl, $tpl_vars);
}

function var_filter($var) {
  if(!ctype_alnum($var)) {
    throw new Exception('Variable contained non-alphanumeric characters.');
  }

  return $var;
}

function feed_data_page($category, $page) {
  $f = Feed::LoadRss("http://chinesereadingpractice.com/category/$category/feed/?paged=$page");
  $data = array();
  foreach($f->item as $item) {
    $data[] = $item;
  }

  return $data;
}

function feed_data_get($category) {
  $data = array();
  for($i = 0; $i < 100; $i++) {
    $data[$i] = feed_data_page($category, $i);
    if($i == 0) {
      $per_page = count($data[$i]);
    }
    $last_page = count($data[$i]) < $per_page;
    if($last_page) {
      break;
    }
  }
  echo sizeof($data);
  echo sizeof($data[0]);
  echo sizeof($data[7]);
}

if(!isset($_POST['category'])) {
  $tpl_file = "main";
  $tpl_vars = array();
} else {
  $valid_categories = array('beginner', 'intermediate', 'advanced');
  $category = var_filter($_POST['category']);
  if(!in_array($category, $valid_categories)) {
    throw new Exception("Invalid category.");
  }

  // https://github.com/gilbitron/PHP-SimpleCache
  $fd = feed_data_get($category);
}

if($tpl_file) {
  echo tpl_render($tpl_file, $tpl_vars);
}
