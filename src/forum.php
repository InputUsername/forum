<?php

namespace forum;

require_once('includes/config.php');
require_once('includes/smarty_setup.php');
require_once('includes/error_pages.php');
require_once('includes/subforum_functions.php');

require_once('includes/classes/database.class.php');
require_once('includes/classes/user.class.php');

/*********************
* Start the session
**********************/

session_start();

$loggedIn = !empty($_SESSION['currentUser']);

/************************
* Check GET parameters
*************************/

if (!isset($_GET['id'])) {
	$_GET['id'] = '0';
}

/************************
* Connect to database
*************************/

$db = new Database();
try {
	$db->connect(
		$config['mysql']['host'],
		$config['mysql']['user'],
		$config['mysql']['pass'],
		$config['mysql']['db']
	);
}
catch (DatabaseException $e) {
	databaseErrorPage($smarty, $e->getMessage(), $e->getCode());

	die();
}

/************************
* Query database
*************************/

$params = array(
	'i' => preg_replace('/[^\d]/', '', $_GET['id'])
);

// Query for the current subforum

try {
	$stmt = $db->prepare('SELECT * FROM subforums WHERE id=?', $params);
	$result = $db->executePrepared($stmt);
}
catch (DatabaseException $e) {
	databaseErrorPage($smarty, $e->getMessage());

	$db->disconnect();

	die();
}

$currentSubforum = $result->fetch_assoc();

// Query for the subforums list

try {
	// Get category IDs associated with top-level subforums

	$categoryNames = getCategoryNames($db);

	// Get subforums

	$stmt = $db->prepare('SELECT * FROM subforums WHERE parent_subforum_id=? ORDER BY category_id IS NULL, category_id, id', $params);
	$resultSubforums = $db->executePrepared($stmt);
}
catch (DatabaseException $e) {
	databaseErrorPage($smarty, $e->getMessage());

	$db->disconnect();

	die();
}

$subforums = generateSubforumsList($resultSubforums, $categoryNames);

// Query for the topics list

try {
	$stmt = $db->prepare('SELECT * FROM topics WHERE subforum_id=?', $params);
	$result = $db->executePrepared($stmt);
}
catch (DatabaseException $e) {
	databaseErrorPage($smarty, $e->getMessage());

	$db->disconnect();

	die();
}

$topics = array();

while ($row = $result->fetch_assoc()) {
	$topics[] = $row;
}

/************************
* Show subforum page
*************************/

$smarty->assign('pageTitle', 'Forum: ' . $currentSubforum['name']);
$smarty->assign('loggedIn', $loggedIn);
$smarty->assign('currentUser', $loggedIn ? $_SESSION['currentUser'] : NULL);

$smarty->assign('currentSubforum', $currentSubforum);
$smarty->assign('subforums', $subforums);
$smarty->assign('topics', $topics);

$smarty->display('forum.tpl');

$db->disconnect();
