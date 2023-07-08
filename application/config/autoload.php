<?php

require_once 'application/config/AutoLoader.php';

AutoLoader::register_class("QueryType", "application/models/queryBuilder/queryType.enum.sql.php");
AutoLoader::register_class("Dashboardable", "application/Controller/dashboardable.int.php");
AutoLoader::register_class("Config", "application/config/configuration.php");

AutoLoader::register_class_location("Ctrl", "application/Controller/#.ctrl.php");
AutoLoader::register_class_location("Model", "application/models/#.model.php");
AutoLoader::register_class_location("Item", "application/views/items/#.item.php");
AutoLoader::register_class_location("TableView", "application/views/tables/#.table.php");
AutoLoader::register_class_location("View", "application/views/#.view.php");
AutoLoader::register_class_location("", "application/models/queryBuilder/#.sql.php");
AutoLoader::register_class_location("", "application/models/queryBuilder/condition/#.sql.php");
AutoLoader::register_class_location("", "application/libs/#.php");


spl_autoload_register(
	function($class) {
		if (AutoLoader::get_path($class) !== null) {
			require_once AutoLoader::get_path($class);
			return true;
		}
		return false;
	}
);