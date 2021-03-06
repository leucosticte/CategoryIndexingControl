<?php
/**
 * CategoryIndexingControl extension to MediaWiki
 *
 * @file
 * @ingroup Extensions
 * @author Nathan Larson
 * @version 1.0.0
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @link http://www.mediawiki.org/wiki/Extension:CategoryIndexingControl Documentation
 * @link http://www.mediawiki.org/wiki/Extension_talk:CategoryIndexingControl Support
 * @link https://github.com/Inclumedia/CategoryIndexingControl Source code
 */

// Prevent direct calls
if ( !defined( 'MEDIAWIKI' ) ) {
        die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

// Extension credits that will show up on Special:version
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'CategoryIndexingControl',
	'version' => '1.0.0',
	'author' => 'Nathan Larson',
	'url' => 'https://www.mediawiki.org/wiki/Extension:CategoryIndexingControl',
	'descriptionmsg' => 'categoryindexingcontrol-desc',
);

// Wherabouts of files
$wgExtensionMessagesFiles['CategoryIndexingControl'] = __DIR__ . '/CategoryIndexingControl.i18n.php';

// Register hooks
$wgHooks['OutputPageBeforeHTML'][] = 'noIndexCategories';

// Extension's function
$wgNoIndexCategories = array();
$wgNoIndexCategoriesRan = false;
function noIndexCategories ( &$out, &$text ) {
	global $wgTitle, $wgParser, $wgNoIndexCategories, $wgNoIndexCategoriesRan;
	if ( $wgNoIndexCategoriesRan ) {
		return true;
	}
	$wgNoIndexCategoriesRan = true;
	$newNoIndexCategories = array();
	foreach( $wgNoIndexCategories as $category ) {
		$newNoIndexCategories[] = str_replace( ' ', '_', $category );
	}
	$wgNoIndexCategories = $newNoIndexCategories;
	$action = $out->getPageTitleActionText();
	if(
		$action !== 'edit'
		&& $action !== 'history'
		&& $action !== 'delete'
		&& $action !== 'watch'
	) {
		if ( array_intersect ( array_keys ( $wgTitle->getParentCategories() ),
			$wgNoIndexCategories ) ) {
			$out->addMeta( 'robots', 'noindex' );
		}
	}
}
