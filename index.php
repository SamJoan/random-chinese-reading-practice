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
    $page_data = feed_data_page($category, $i);
    $data = array_merge($data, $page_data);
    if($i == 0) {
      $per_page = count($page_data);
    }
    $last_page = count($page_data) < $per_page;
    if($last_page) {
      break;
    }
  }

  return $data;
}

function feed_random_get($data) {
  $nb = rand(0, count($data) -1);
  $random = $data[$nb];

  return $random;
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

  $cache = new Gilbitron\Util\SimpleCache();
  $cache->cache_path = 'cache/';
  $cache->cache_time = 7800;

  $data_label = 'cache-'.$category;

  if($data = $cache->get_cache($data_label)){
    $data = json_decode($data);
  } else {
    $data = feed_data_get($category);
    $cache->set_cache($data_label, json_encode($data));
  }

  $random = feed_random_get($data);
  header("Location: $random->link");
  exit();
}

if(@$tpl_file) {
  echo tpl_render($tpl_file, $tpl_vars);
}
