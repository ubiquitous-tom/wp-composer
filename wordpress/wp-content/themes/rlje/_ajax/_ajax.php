<?php

/**
 * Add ajax methods to WordPress in order to use it in javascript code.
 */

// Identify the section where it should process the data sent.
$action = !empty($_POST['action']) ? $_POST['action'] : null;

/**
 * Do action if it's set.
 */
if(!empty($action)) {

  $token = (!empty($_POST['token'])) ? $_POST['token'] : null;

  /**
  * This ajax method get the content by page.
  */
  if('paginate' === $action) {
    $content = (!empty($_POST['content'])) ? $_POST['content'] : null;
    $page    = (!empty($_POST['page']))    ? $_POST['page'] : null;

    if(isset($content, $page, $token)) {
      if($token === wp_create_nonce( 'atv#contentPage@token_nonce' )) {
        header('Content-Type: application/json');
        echo json_encode(rljeApiWP_getContentPageItems($content, $page));
        exit();
      }
    }
  }

  /**
  * This ajax method check if the current user is Active.
  */
  if('isUserActive' === $action) {
    if(!empty($token) && $token === wp_create_nonce( 'atv#episodePlayer@token_nonce' )) {
      header('Content-Type: application/json');
      echo json_encode(array(
          'isActive' => rljeApiWP_isUserActive($_COOKIE["ATVSessionCookie"])
      ));
      exit();
    }
  }
}

require_once(get_404_template());

