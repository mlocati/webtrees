<?php
namespace Fisharebest\Webtrees;

/**
 * webtrees: online genealogy
 * Copyright (C) 2015 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

use PDOException;

// Create tables, if not already present

try {
	Database::updateSchema(WT_ROOT . WT_MODULES_DIR . 'gedcom_news/db_schema/', 'NB_SCHEMA_VERSION', 3);
} catch (PDOException $ex) {
	// The schema update scripts should never fail.  If they do, there is no clean recovery.
	FlashMessages::addMessage($ex->getMessage(), 'danger');
	header('Location: ' . WT_BASE_URL . 'site-unavailable.php');
	throw $ex;
}

return new FamilyTreeNewsModule(__DIR__);
