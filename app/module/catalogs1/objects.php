<?php
// include catalog
include('class.php');
// config module
$params_category = array(
		'module'=>'catalogs_categories',
 		'table'=>'catalogs_categories',
		'table_translate'=>'catalogs_categories_translate',
		'site_language'=>SITE_LANGUAGE ,
		'is_translate'=>SITE_TRANSLATE ,
);
$oCatalogCategories = new Catalogs($params_category);
$params = array(
		'module'=>'catalogs',
 		'table'=>'catalogs',
		'parent_table'=> 'catalogs_categories',
		'parent_table_translate'=> 'catalogs_categories_translate',
		'parent_primary_key'=> 'id',
		'table_translate'=>'catalogs_translate',
		'site_language'=>SITE_LANGUAGE ,
		'is_translate'=>SITE_TRANSLATE 
);
$oCatalogModule = new Catalogs($params );
$params = array(
		'module'=>'catalogs',
 		'table'=>'catalogs_attrs',
		'parent_table'=> 'catalogs_categories',
		'parent_table_translate'=> 'catalogs_categories_translate',
		'parent_primary_key'=> 'id',
		'table_translate'=>'catalogs_attrs_translate',
		'site_language'=>SITE_LANGUAGE ,
		'is_translate'=>SITE_TRANSLATE 
);
$oCatalogAttr = new Catalogs($params );
?>