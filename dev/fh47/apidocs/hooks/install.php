<?php
/**
 * @file
 * Documentation for the update system.
 *
 * The update system is used by modules to provide database updates which are
 * run with update.php.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Install the current version of the database schema.
 *
 * The hook will be called the first time a module is installed, and the
 * module's schema version will be set to the module's greatest numbered update
 * hook. Because of this, anytime a hook_update_N() is added to the module, this
 * function needs to be updated to reflect the current version of the database
 * schema.
 *
 * Table names in the CREATE queries should be wrapped with curly braces so that
 * they're prefixed correctly, see db_prefix_tables() for more on this.
 *
 * Note that since this function is called from a full bootstrap, all functions
 * (including those in modules enabled by the current page request) are
 * available when this hook is called. Use cases could be displaying a user
 * message, or calling a module function necessary for initial setup, etc.
 *
 * Implementations of this hook should be placed in a mymodule.install file in
 * the same directory at mymodule.module.
 */
function hook_install() {
  switch ($GLOBALS['db_type']) {
    case 'mysql':
    case 'mysqli':
      db_query("CREATE TABLE {event} (
                  nid int(10) unsigned NOT NULL default '0',
                  event_start int(10) unsigned NOT NULL default '0',
                  event_end int(10) unsigned NOT NULL default '0',
                  timezone int(10) NOT NULL default '0',
                  PRIMARY KEY (nid),
                  KEY event_start (event_start)
                ) TYPE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 */;"
      );
      break;

    case 'pgsql':
      db_query("CREATE TABLE {event} (
                  nid int NOT NULL default '0',
                  event_start int NOT NULL default '0',
                  event_end int NOT NULL default '0',
                  timezone int NOT NULL default '0',
                  PRIMARY KEY (nid)
                );"
      );
      break;
  }
}

/**
 * Perform a single update. For each patch which requires a database change add
 * a new hook_update_N() which will be called by update.php.
 *
 * The database updates are numbered sequentially starting with 1 in each
 * module. The first is mymodule_update_1().
 *
 * A good rule of thumb is to remove updates older than two major releases of
 * Drupal. Never renumber update functions.
 *
 * Whenever possible implement both PostgreSQL and MySQL at the same time. If
 * PostgreSQL updates are added later, add a new update function which only does
 * the PostgreSQL update. Be sure to use comments to describe which updates are
 * the same if they do get separated.
 *
 * Implementations of this hook should be placed in a mymodule.install file in
 * the same directory at mymodule.module. Drupal core's updates are implemented
 * using the system module as a name and stored in database/updates.inc.
 *
 * The following examples serve as a quick guide to MySQL to PostgreSQL conversion.
 * Usually (but not always!) you will use following SQL statements:
 *
 * - Adding a key (an index)
 *   - MySQL: ALTER TABLE {$table} ADD KEY $column ($column)
 *   - PostgreSQL: CREATE INDEX {$table}_$column_idx ON {$table}($column)  // Please note the _idx "extension"
 * - Adding a primary key
 *   - MySQL: ALTER TABLE {$table} ADD PRIMARY KEY $column ($column)
 *   - PostgreSQL: ALTER TABLE {$table} ADD PRIMARY KEY ($column)
 * - Dropping a primary key
 *   - MySQL: ALTER TABLE {$table} DROP PRIMARY KEY
 *   - PostgreSQL:ALTER TABLE {$table} DROP CONSTRAINT {$table}_pkey
 * - Dropping a column
 *   - MySQL: ALTER TABLE {$table} DROP $column
 *   - Postgres: ALTER TABLE {$table} DROP $column
 * - Dropping an index
 *   - MySQL: ALTER TABLE {$table} DROP INDEX $index
 *   - Postgres:
 *     - DROP INDEX {$table}_$column_idx                            // When index was defined by CREATE INDEX
 *     - ALTER TABLE {$table} DROP CONSTRAINT {$table}_$column_key  // In case of UNIQUE($column)
 * - Adding a column
 *   - MySQL: $ret = update_sql("ALTER TABLE {vocabulary} ADD tags tinyint(3) unsigned default '0' NOT NULL");
 *   - Postgres: db_add_column($ret, 'vocabulary', 'tags', 'smallint', array('default' => 0, 'not null' => TRUE));
 * - Changing a column
 *   - MySQL: $ret[] = update_sql("ALTER TABLE {locales_source} CHANGE location location varchar(255) NOT NULL default ''");
 *   - Postgres: db_change_column($ret, 'locales_source', 'location', 'location', 'varchar(255)', array('not null' => TRUE, 'default' => "''"));
 */
function hook_update_N() {
  $ret = array();

  switch ($GLOBALS['db_type']) {
    case 'pgsql':
      db_add_column($ret, 'contact', 'weight', 'smallint', array('not null' => TRUE, 'default' => 0));
      db_add_column($ret, 'contact', 'selected', 'smallint', array('not null' => TRUE, 'default' => 0));
      break;

    case 'mysql':
    case 'mysqli':
      $ret[] = update_sql("ALTER TABLE {contact} ADD COLUMN weight tinyint(3) NOT NULL DEFAULT 0");
      $ret[] = update_sql("ALTER TABLE {contact} ADD COLUMN selected tinyint(1) NOT NULL DEFAULT 0");
      break;
  }

  return $ret;
}

/**
 * @} End of "addtogroup hooks".
 */