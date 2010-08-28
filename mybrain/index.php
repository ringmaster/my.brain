<?php
session_start();
if (!isset($_SESSION['logged']) || !$_SESSION['logged']){
    header("Location: login.php");
	exit();
}
require_once('init.php');
require_once('lib/model/entry.php');
require_once('lib/model/entryList.php');
require_once('lib/dao/entryDAO.php');
require_once('lib/dao/entryListDAO.php');

// retrieve memo from db and remove slashes that were added when inserting into db
$memo = $db->getMemo();
$content['memo'] = stripslashes($memo["content"]);
//$datetime_memo = $memo["update_date"];
$content['memo_date'] = $memo["update_date"];

// retrieve entry lists from db
$listDAO = new EntryListDAO($db);
$entry_lists = $listDAO->getAll();

// retrieve entries from db and relate them to entry lists
$entryDAO = new EntryDAO($db);
foreach($entry_lists as $entry_list){
	$entries = $entryDAO->getByList($entry_list->getId());
	$entry_list->setEntries($entries);
	$entry_list->setEntriesTags();
}

// build an entrylist with the remaining entries (orphans)
$list_orphans = new EntryList("", "Orphan entries", 1, 5, 0);
$list_orphans->setMainTags(array());
$entries_orphans = $entryDAO->getOrphans();
if (!empty($entries_orphans)){
	$list_orphans->setEntries($entries_orphans);
	$list_orphans->setEntriesTags();
}
$entry_lists[] = $list_orphans;

$content['entry_lists'] = $entry_lists;

// build the view
require('view.php');
?>


