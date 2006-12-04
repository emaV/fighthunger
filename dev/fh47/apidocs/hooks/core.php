<?php
/**
 * @file
 * These are the hooks that are invoked by the Drupal core.
 *
 * Core hooks are typically called in all modules at once using
 * module_invoke_all().
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Declare a block or set of blocks.
 *
 * Any module can export a block (or blocks) to be displayed by defining
 * the _block hook. This hook is called by theme.inc to display a block,
 * and also by block.module to procure the list of available blocks.
 *
 * As of 4.7, all block properties (except theme) can be set in hook_block's
 * 'view' operation. You can give your blocks an explicit weight, enable them,
 * limit them to given pages, etc. These settings will be registered when the
 * block is first loaded at admin/block, and from there can be changed manually
 * via block administration.
 *
 * Note that if you set a region that isn't available in a given theme, the
 * block will be registered instead to that theme's default region (the first
 * item in the _regions array).
 *
 * @param $op
 *   What kind of information to retrieve about the block or blocks.
 *   Possible values:
 *   - 'list': A list of all blocks defined by the module.
 *   - 'configure': A configuration form.
 *   - 'save': Save the configuration options.
 *   - 'view': Information about a particular block and default settings.
 * @param $delta
 *   Which block to return (not applicable if $op is 'list'). Although it is
 *   most commonly an integer starting at 0, this is not mandatory. For
 *   instance, aggregator.module uses string values for $delta
 * @param $edit
 *   If $op is 'save', the submitted form data from the configuration form.
 * @return
 *   If $op is 'list', return an array of arrays, each of which must define an
 *   'info' element describing the block. If $op is 'configure', optionally
 *   return a string containing the configuration form. If $op is 'save',
 *   return nothing, If $op is 'view', return an array which must define a
 *   'subject' element and a 'content' element defining the block indexed by
 *   $delta.
 *
 * The functions mymodule_display_block_1 and 2, as used in the example,
 * should of course be defined somewhere in your module and return the
 * content you want to display to your users. If the "content" element
 * is empty, no block will be displayed even if "subject" is present.
 *
 * After completing your blocks, do not forget to enable them in the
 * block admin menu.
 *
 * For a detailed usage example, see block_example.module.
 */
function hook_block($op = 'list', $delta = 0, $edit = array()) {
  if ($op == 'list') {
    $blocks[0] = array('info' => t('Mymodule block #1 shows ...'),
      'weight' => 0, 'enabled' => 1, 'region' => 'left');
    $blocks[1] = array('info' => t('Mymodule block #2 describes ...'),
      'weight' => 0, 'enabled' => 0, 'region' => 'right');
    return $blocks;
  }
  else if ($op == 'configure' && $delta == 0) {
    $form['items'] = array(
      '#type' => 'select',
      '#title' => t('Number of items'),
      '#default_value' => variable_get('mymodule_block_items', 0),
      '#options' => array('1', '2', '3'),
    );
    return $form;
  }
  else if ($op == 'save' && $delta == 0) {
    variable_set('mymodule_block_items', $edit['items']);
  }
  else if ($op == 'view') {
    switch($delta) {
      case 0:
        $block = array('subject' => t('Title of block #1'),
          'content' => mymodule_display_block_1());
        break;
      case 1:
        $block = array('subject' => t('Title of block #2'),
          'content' => mymodule_display_block_2());
        break;
    }
    return $block;
  }
}

/**
 * Act on comments.
 *
 * This hook allows modules to extend the comments system.
 *
 * @param $a1
 *   Dependent on the action being performed.
 *   - For "form", passes in the comment form.
 *   - For "validate","update","insert", passes in an array of form values submitted by the user.
 *   - For all other operations, passes in the comment the action is being performed on.
 * @param $op
 *   What kind of action is being performed. Possible values:
 *   - "insert": The comment is being deleted.
 *   - "update": The comment is being updated.
 *   - "view": The comment is being viewed. This hook can be used to add additional data to the comment before theming.
 *   - "form": The comment form is about to be shown. Modules may add fields to the form at this point.
 *   - "validate": The user has just finished editing the comment and is
 *     trying to preview or submit it. This hook can be used to check or
 *     even modify the node. Errors should be set with form_set_error().
 *   - "publish": The comment is being published by the moderator.
 *   - "unpublish": The comment is being unpublished by the moderator.
 *   - "delete": The comment is being deleted by the moderator.
 * @return
 *   Dependent on the action being performed.
 *   - For "form", an array of form elements to add to the comment form.
 *   - For all other operations, nothing.
 */
function hook_comment($a1, $op) {
  if ($op == 'insert' || $op == 'update') {
    $nid = $a1['nid'];
  }

  cache_clear_all_like(drupal_url(array('id' => $nid)));
}

/**
 * Perform periodic actions.
 *
 * Modules that require to schedule some commands to be executed at regular
 * intervals can implement hook_cron(). The engine will then call the hook
 * at the appropriate intervals defined by the administrator. This interface
 * is particularly handy to implement timers or to automate certain tasks.
 * Database maintenance, recalculation of settings or parameters, and
 * automatic mailings are good candidates for cron tasks.
 *
 * @return
 *   None.
 *
 * This hook will only be called if cron.php is run (e.g. by crontab).
 */
function hook_cron() {
  $result = db_query('SELECT * FROM {site} WHERE checked = 0 OR checked
    + refresh < %d', time());

  while ($site = db_fetch_array($result)) {
    cloud_update($site);
  }
}

/**
 * Add JOIN and WHERE statements to queries and decide whether the primary_field shall
 * be made DISTINCT. For node objects, primary field is always called nid. For taxonomy terms, it is tid
 * and for vocabularies it is vid. For comments, it is cid.
 * Primary table is the table where the primary object (node, file, term_node etc.) is.
 *
 * You shall return an associative array. Possible keys are 'join', 'where' and 'distinct'. The value of 'distinct' shall be 1 if you want that the primary_field made DISTINCT.
 *
 * @param $query
 *   Query to be rewritten.
 * @param $primary_table
 *   Name or alias of the table which has the primary key field for this query. Possible values are: comments, forum, node, term_data, vocabulary.
 * @param $primary_field
 *   Name of the primary field.
 * @param $args
 *   Array of additional arguments.
 * @return
 *   An array of join statements, where statements, distinct decision.
 */
function hook_db_rewrite_sql($query, $primary_table, $primary_field, $args) {
  switch ($primary_field) {
    case 'nid':
      // this query deals with node objects
      $return = array();
      if ($primary_table != 'n') {
        $return['join'] = "LEFT JOIN {node} n ON $primary_table.nid = n.nid";
      }
      $return['where'] = 'created >' . mktime(0, 0, 0, 1, 1, 2005);
      return $return;
      break;
    case 'tid':
      // this query deals with taxonomy objects
      break;
    case 'vid':
      // this query deals with vocabulary objects
      break;
  }
}

/**
 * Allows modules to declare their own form element types and specify their default values.
 *
 * @return
 *  An array of element types
 */
function hook_elements() {
  $type['filter_format'] = array('#input' => TRUE);
  return $type;
}

/**
 * Perform cleanup tasks.
 *
 * This hook is run at the end of each page request. It is often used for
 * page logging and printing out debugging information.
 *
 * Only use this hook if your code must run even for cached page views.
 * If you have code which must run once on all non cached pages, use hook_menu(!$may_cache)
 * instead. Thats the usual case. If you implement this hook and see an error like 'Call to
 * undefined function', it is likely that you are depending on the presence of a module which
 * has not been loaded yet. It is not loaded because Drupal is still in bootstrap mode.
 * The usual fix is to move your code to hook_menu(!$may_cache).
 *
 * @param $destination
 *   If this hook is invoked as part of a drupal_goto() call, then this argument
 *   will be a fully-qualified URL that is the destination of the redirect.
 *   Modules may use this to react appropriately; for example, nothing should
 *   be output in this case, because PHP will then throw a "headers cannot be
 *   modified" error when attempting the redirection.
 * @return
 *   None.
 */
function hook_exit($destination = NULL) {
  db_query('UPDATE {counter} SET hits = hits + 1 WHERE type = 1');
}

/**
 * Allow file downloads.
 *
 * @param $file
 *   String of the file's path.
 * @return
 *   If the user does not have permission to access the file, return -1. If the
 *   user has permission, return an array with the appropriate headers.
 */
function hook_file_download($file) {
  if (user_access('access content')) {
    if ($filemime = db_result(db_query("SELECT filemime FROM {fileupload} WHERE filepath = '%s'", file_create_path($file)))) {
      return array('Content-type:' . $filemime);
    }
  }
  else {
    return -1;
  }
}

/**
 * Define content filters.
 *
 * Content in Drupal is passed through all enabled filters before it is
 * output. This lets a module modify content to the site administrator's
 * liking.
 *
 * This hook contains all that is needed for having a module provide filtering
 * functionality.
 *
 * Depending on $op, different tasks are performed.
 *
 * A module can contain as many filters as it wants. The 'list' operation tells
 * the filter system which filters are available. Every filter has a numerical
 * 'delta' which is used to refer to it in every operation.
 *
 * Filtering is a two-step process. First, the content is 'prepared' by calling
 * the 'prepare' operation for every filter. The purpose of 'prepare' is to
 * escape HTML-like structures. For example, imagine a filter which allows the
 * user to paste entire chunks of programming code without requiring manual
 * escaping of special HTML characters like @< or @&. If the programming code
 * were left untouched, then other filters could think it was HTML and change
 * it. For most filters however, the prepare-step is not necessary, and they can
 * just return the input without changes.
 *
 * Filters should not use the 'prepare' step for anything other than escaping,
 * because that would short-circuits the control the user has over the order
 * in which filters are applied.
 *
 * The second step is the actual processing step. The result from the
 * prepare-step gets passed to all the filters again, this time with the
 * 'process' operation. It's here that filters should perform actual changing of
 * the content: transforming URLs into hyperlinks, converting smileys into
 * images, etc.
 *
 * An important aspect of the filtering system are 'input formats'. Every input
 * format is an entire filter setup: which filters to enable, in what order
 * and with what settings. Filters that provide settings should usually store
 * these settings per format.
 *
 * If the filter's behaviour depends on an extensive list and/or external data
 * (e.g. a list of smileys, a list of glossary terms) then filters are allowed
 * to provide a separate, global configuration page rather than provide settings
 * per format. In that case, there should be a link from the format-specific
 * settings to the separate settings page.
 *
 * For performance reasons content is only filtered once; the result is stored
 * in the cache table and retrieved the next time the piece of content is
 * displayed. If a filter's output is dynamic it can override the cache
 * mechanism, but obviously this feature should be used with caution: having one
 * 'no cache' filter in a particular input format disables caching for the
 * entire format, not just for one filter.
 *
 * Beware of the filter cache when developing your module: it is advised to set
 * your filter to 'no cache' while developing, but be sure to remove it again
 * if it's not needed. You can clear the cache by running the SQL query 'DELETE
 * FROM cache';
 *
 * @param $op
 *  Which filtering operation to perform. Possible values:
 *   - list: provide a list of available filters.
 *     Returns an associative array of filter names with numerical keys.
 *     These keys are used for subsequent operations and passed back through
 *     the $delta parameter.
 *   - no cache: Return true if caching should be disabled for this filter.
 *   - description: Return a short description of what this filter does.
 *   - prepare: Return the prepared version of the content in $text.
 *   - process: Return the processed version of the content in $text.
 *   - settings: Return HTML form controls for the filter's settings. These
 *     settings are stored with variable_set() when the form is submitted.
 *     Remember to use the $format identifier in the variable and control names
 *     to store settings per input format (e.g. "mymodule_setting_$format").
 * @param $delta
 *   Which of the module's filters to use (applies to every operation except
 *   'list'). Modules that only contain one filter can ignore this parameter.
  * @param $format
 *   Which input format the filter is being used in (applies to 'prepare',
 *   'process' and 'settings').
 * @param $text
 *   The content to filter (applies to 'prepare' and 'process').
 * @return
 *   The return value depends on $op. The filter hook is designed so that a
 *   module can return $text for operations it does not use/need.
 *
 * For a detailed usage example, see filter_example.module. For an example of
 * using multiple filters in one module, see filter_filter() and
 * filter_filter_tips().
 */
function hook_filter($op, $delta = 0, $format = -1, $text = '') {
  switch ($op) {
    case 'list':
      return array(0 => t('Code filter'));

    case 'description':
      return t('Allows users to post code verbatim using &lt;code&gt; and &lt;?php ?&gt; tags.');

    case 'prepare':
      // Note: we use the bytes 0xFE and 0xFF to replace < > during the filtering process.
      // These bytes are not valid in UTF-8 data and thus least likely to cause problems.
      $text = preg_replace('@<code>(.+?)</code>@se', "'\xFEcode\xFF'. codefilter_escape('\\1') .'\xFE/code\xFF'", $text);
      $text = preg_replace('@<(\?(php)?|%)(.+?)(\?|%)>@se', "'\xFEphp\xFF'. codefilter_escape('\\3') .'\xFE/php\xFF'", $text);
      return $text;

    case "process":
      $text = preg_replace('@\xFEcode\xFF(.+?)\xFE/code\xFF@se', "codefilter_process_code('$1')", $text);
      $text = preg_replace('@\xFEphp\xFF(.+?)\xFE/php\xFF@se', "codefilter_process_php('$1')", $text);
      return $text;

    default:
      return $text;
  }
}

/**
 * Provide tips for using filters.
 *
 * A module's tips should be informative and to the point. Short tips are
 * preferably one-liners.
 *
 * @param $delta
 *   Which of this module's filters to use. Modules which only implement one
 *   filter can ignore this parameter.
 * @param $format
 *   Which format we are providing tips for.
 * @param $long
 *   If set to true, long tips are requested, otherwise short tips are needed.
 * @return
 *   The text of the filter tip.
 *
 *
 */
function hook_filter_tips($delta, $format, $long = false) {
  if ($long) {
    return t('To post pieces of code, surround them with &lt;code&gt;...&lt;/code&gt; tags. For PHP code, you can use &lt;?php ... ?&gt;, which will also colour it based on syntax.');
  }
  else {
    return t('You may post code using &lt;code&gt;...&lt;/code&gt; (generic) or &lt;?php ... ?&gt; (highlighted PHP) tags.');
  }
}

/**
 * Insert closing HTML.
 *
 * This hook enables modules to insert HTML just before the \</body\> closing
 * tag of web pages. This is useful for including javascript code and for
 * outputting debug information.
 *
 * @param $main
 *   Whether the current page is the front page of the site.
 * @return
 *   The HTML to be inserted.
 */
function hook_footer($main = 0) {
  if (variable_get('dev_query', 0)) {
    print '<div style="clear:both;">'. devel_query_table() .'</div>';
  }
}

/**
 * Perform alterations before a form is rendered. One popular use of this hook is to
 * add form elements to the node form.
 *
 * @param $form_id
 *   String representing the name of the form itself. Typically this is the
 *   name of the function that generated the form.
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @return
 *   None.
 */
function hook_form_alter($form_id, &$form) {
  if (isset($form['type']) && $form['type']['#value'] .'_node_settings' == $form_id) {
    $form['workflow']['upload_'. $form['type']['#value']] = array(
      '#type' => 'radios',
      '#title' => t('Attachments'),
      '#default_value' => variable_get('upload_'. $form['type']['#value'], 1),
      '#options' => array(t('Disabled'), t('Enabled')),
    );
  }
}

/**
 * Provide online user help.
 *
 * By implementing hook_help(), a module can make documentation
 * available to the engine or to other modules. All user help should be
 * returned using this hook; developer help should be provided with
 * Doxygen/api.module comments.
 *
 * @param $section
 *   Drupal URL path (or: menu item) the help is being requested for, e.g.
 *   admin/node or user/edit. Recognizes special descriptors after a "#"
 *   sign. Some examples:
 *   - admin/modules#name
 *     The name of a module (unused, but there)
 *   - admin/modules#description
 *     The description found on the admin/system/modules page.
 *   - admin/help#modulename
 *     The module's help text, displayed on the admin/help page and through
 *     the module's individual help link.
 *   - user/help#modulename
 *     The help for a distributed authorization module (if applicable).
 *   - node/add#nodetype
 *     The description of a node type (if applicable).
 * @return
 *   A localized string containing the help text. Every web link, l(), or
 *   url() must be replaced with %something and put into the final t()
 *   call:
 *   $output .= 'A role defines a group of users that have certain
 *     privileges as defined in %permission.';
 *   $output = t($output, array('%permission' => l(t('user permissions'),
 *     'admin/user/permission')));
 *
 * For a detailed usage example, see page_example.module.
 */
function hook_help($section) {
  switch ($section) {
    case 'admin/help#block':
      return t('<p>Blocks are the boxes visible in the sidebar(s)
        of your web site. These are usually generated automatically by
        modules (e.g. recent forum topics), but you can also create your
        own blocks using either static HTML or dynamic PHP content.</p>');
      break;
    case 'admin/modules#description':
      return t('Controls the boxes that are displayed around the main content.');
      break;
  }
}

/**
 * Perform setup tasks.
 *
 * This hook is run at the beginning of the page request. It is typically
 * used to set up global parameters which are needed later in the request.
 *
 * Only use this hook if your code must run even for cached page views.
 * If you have code which must run once on all non cached pages, use hook_menu(!$may_cache)
 * instead. Thats the usual case. If you implement this hook and see an error like 'Call to
 * undefined function', it is likely that you are depending on the presence of a module which
 * has not been loaded yet. It is not loaded because Drupal is still in bootstrap mode.
 * The usual fix is to move your code to hook_menu(!$may_cache).
 *
 * @return
 *   None.
 */
function hook_init() {
  global $recent_activity;

  if ((variable_get('statistics_enable_auto_throttle', 0)) &&
    (!rand(0, variable_get('statistics_probability_limiter', 9)))) {

    $throttle = throttle_status();
    // if we're at throttle level 5, we don't do anything
    if ($throttle < 5) {
      $multiplier = variable_get('statistics_throttle_multiplier', 60);
      // count all hits in past sixty seconds
      $result = db_query('SELECT COUNT(timestamp) AS hits FROM
        {accesslog} WHERE timestamp >= %d', (time() - 60));
      $recent_activity = db_fetch_array($result);
      throttle_update($recent_activity['hits']);
    }
  }
}

/**
 * Define internal Drupal links.
 *
 * This hook enables modules to add links to many parts of Drupal. Links
 * may be added in nodes or in the navigation block, for example.
 *
 * @param $type
 *   An identifier declaring what kind of link is being requested.
 *   Possible values:
 *   - node: Links to be placed below a node being viewed.
 *   - comment: Links to be placed below a comment being viewed.
 * @param $node
 *   A node object passed in case of node links.
 * @param $teaser
 *   In case of node link: a 0/1 flag depending on whether the node is
 *   displayed with its teaser or its full form (on a node/nid page)
 * @return
 *   An array of the requested links.
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_link($type, $node = NULL, $teaser = FALSE) {
  $links = array();

  if ($type == 'node' && $node->type == 'book') {
    if (book_access('update', $node)) {
      $links[] = l(t('edit this page'), "node/$node->nid/edit",
        array('title' => t('Suggest an update for this book page.')));
    }
    if (!$teaser) {
      $links[] = l(t('printer-friendly version'), "book/print/$node->nid",
        array('title' => t('Show a printer-friendly version of this book page
        and its sub-pages.')));
    }
  }

  return $links;
}

/**
 * Define menu items and page callbacks.
 *
 * This hook enables modules to register paths which whose requests they wish to handle.
 * Depending on the type of registration requested by each path, a link is placed in the
 * the navigation block and/or an item appears in the menu administration page (q=admin/menu).
 *
 * This hook is also a good place to put code which should run exactly once
 * per page view. Put it in an if (!may_cache) block.
 *
 * @param $may_cache
 *   A boolean indicating whether cacheable menu items should be returned. The
 *   menu cache is per-user, so items can be cached so long as they are not
 *   dependent on the user's current location. See the local task definitions
 *   in node_menu() for an example of uncacheable menu items.
 * @return
 *   An array of menu items. Each menu item is an associative array that may
 *   contain the following key-value pairs:
 *   - "path": Required. The path to link to when the user selects the item.
 *   - "title": Required. The translated title of the menu item.
 *   - "callback": The function to call to display a web page when the user
 *     visits the path. If omitted, the parent menu item's callback will be used
 *     instead.
 *   - "callback arguments": An array of arguments to pass to the callback function.
 *   - "access": A boolean value that determines whether the user has access
 *     rights to this menu item. Usually determined by a call to user_access().
 *     If omitted and "callback" is also absent, the access rights of the parent
 *     menu item will be used instead.
 *   - "weight": An integer that determines relative position of items in the menu;
 *     higher-weighted items sink. Defaults to 0. When in doubt, leave this alone;
 *     the default alphabetical order is usually best.
 *   - "type": A bitmask of flags describing properties of the menu item.
 *     Many shortcut bitmasks are provided as constants in menu.inc:
 *     - MENU_NORMAL_ITEM: Normal menu items show up in the menu tree and can be
 *       moved/hidden by the administrator.
 *     - MENU_ITEM_GROUPING: Item groupings are used for pages like "node/add"
 *       that simply list subpages to visit.
 *     - MENU_CALLBACK: Callbacks simply register a path so that the correct
 *       function is fired when the URL is accessed.
 *     - MENU_DYNAMIC_ITEM: Dynamic menu items change frequently, and so should
 *       not be stored in the database for administrative customization.
 *     - MENU_SUGGESTED_ITEM: Modules may "suggest" menu items that the
 *       administrator may enable.
 *     - MENU_LOCAL_TASK: Local tasks are rendered as tabs by default.
 *     - MENU_DEFAULT_LOCAL_TASK: Every set of local tasks should provide one
 *       "default" task, that links to the same path as its parent when clicked.
 *     If the "type" key is omitted, MENU_NORMAL_ITEM is assumed.
 *
 * For a detailed usage example, see page_example.module.
 *
 */
function hook_menu($may_cache) {
  global $user;
  $items = array();

  if ($may_cache) {
    $items[] = array('path' => 'node/add/blog', 'title' => t('blog entry'),
      'access' => user_access('maintain personal blog'));
    $items[] = array('path' => 'blog', 'title' => t('blogs'),
      'callback' => 'blog_page',
      'access' => user_access('access content'),
      'type' => MENU_SUGGESTED_ITEM);
    $items[] = array('path' => 'blog/'. $user->uid, 'title' => t('my blog'),
      'access' => user_access('maintain personal blog'),
      'type' => MENU_DYNAMIC_ITEM);
    $items[] = array('path' => 'blog/feed', 'title' => t('RSS feed'),
      'callback' => 'blog_feed',
      'access' => user_access('access content'),
      'type' => MENU_CALLBACK);
  }
  return $items;
}


/**
 * Grant access to nodes.
 *
 * This hook is for implementation by node access modules. In addition to
 * managing entries in the node_access table in the database, such modules must
 * implement this hook to inform Drupal which "grants" the current user has.
 * For example, in a role-based access scheme, this function would return a
 * list of the roles the user belongs to.
 *
 * @param $user
 *   The user object whose grants are requested.
 * @param $op
 *   The node operation to be performed, such as "view", "update", or "delete".
 * @return
 *   An array whose keys are "realms" of grants such as "user" or "role", and
 *   whose values are linear lists of grant IDs.
 *
 * For a detailed example, see node_access_example.module.
 */
function hook_node_grants($user, $op) {
  $grants = array();
  if ($op == 'view') {
    if (user_access('access content')) {
      $grants[] = 0;
    }
    if (user_access('access private content')) {
      $grants[] = 1;
    }
  }
  if ($op == 'update' || $op == 'delete') {
    if (user_access('edit content')) {
      $grants[] = 0;
    }
    if (user_access('edit private content')) {
      $grants[] = 1;
    }
  }
  return array('example' => $grants);
}

/**
 * Act on nodes defined by other modules.
 *
 * Despite what its name might make you think, hook_nodeapi() is not
 * reserved for node modules. On the contrary, it allows modules to react
 * to actions affecting all kinds of nodes, regardless of whether that
 * module defined the node.
 *
 * @param &$node
 *   The node the action is being performed on.
 * @param $op
 *   What kind of action is being performed. Possible values:
 *   - "delete": The node is being deleted.
 *   - "insert": The node is being created (inserted in the database).
 *   - "load": The node is about to be loaded from the database. This hook
 *     can be used to load additional data at this time.
 *   - "prepare": The node is about to be shown on the add/edit form.
 *   - "search result": The node is displayed as a search result. If you
 *     want to display extra information with the result, return it.
 *   - "print": Prepare a node view for printing. Used for printer-friendly view in book_module
 *   - "update": The node is being updated.
 *   - "submit": The node passed validation and will soon be saved. Modules may
 *      use this to make changes to the node before it is saved to the database.
 *   - "update index": The node is being indexed. If you want additional
 *     information to be indexed which is not already visible through
 *     nodeapi "view", then you should return it here.
 *   - "validate": The user has just finished editing the node and is
 *     trying to preview or submit it. This hook can be used to check or
 *     even modify the node. Errors should be set with form_set_error().
 *   - "view": The node is about to be presented to the user. The module
 *     may change $node->body prior to presentation. This hook will be called
 *     after hook_view(), so the module may assume the node is filtered and
 *     now contains HTML.
 *   - "rss item": An RSS feed is generated. The module can return properties
 *     to be added to the RSS item generated for this node. See comment_nodeapi()
 *     and upload_nodeapi() for examples. The $node passed can also be modified
 *     to add or remove contents to the feed item.
 * @param $a3
 *   - For "view", passes in the $teaser parameter from node_view().
 *   - For "validate", passes in the $form parameter from node_validate().
 * @param $a4
 *   - For "view", passes in the $page parameter from node_view().
 * @return
 *   This varies depending on the operation.
 *   - The "execute", "insert", "update", "delete", "print' and "view" operations have no
 *     return value.
 *   - The "load" operation should return an array containing pairs
 *     of fields => values to be merged into the node object.
 *
 * If you are writing a node module, do not use this hook to perform
 * actions on your type of node alone. Instead, use the hooks set aside
 * for node modules, such as hook_insert() and hook_form().
 */
function hook_nodeapi(&$node, $op, $a3 = NULL, $a4 = NULL) {
  switch ($op) {
    case 'submit':
      if ($node->nid && $node->moderate) {
        // Reset votes when node is updated:
        $node->score = 0;
        $node->users = '';
        $node->votes = 0;
      }
      break;
    case 'insert':
    case 'update':
      if ($node->moderate && user_access('access submission queue')) {
        drupal_set_message(t('The post is queued for approval'));
      }
      elseif ($node->moderate) {
        drupal_set_message(t('The post is queued for approval. The editors will decide whether it should be published.'));
      }
      break;
  }
}

/**
 * Define user permissions.
 *
 * This hook can supply permissions that the module defines, so that they
 * can be selected on the user permissions page and used to restrict
 * access to actions the module performs.
 *
 * @return
 *   An array of permissions strings.
 *
 * Permissions are checked using user_access().
 *
 * For a detailed usage example, see page_example.module.
 */
function hook_perm() {
  return array('administer my module');
}

/**
 * Ping another server.
 *
 * This hook allows a module to notify other sites of updates on your
 * Drupal site.
 *
 * @param $name
 *   The name of your Drupal site.
 * @param $url
 *   The URL of your Drupal site.
 * @return
 *   None.
 */
function hook_ping($name = '', $url = '') {
  $feed = url('node/feed');

  $client = new xmlrpc_client('/RPC2', 'rpc.weblogs.com', 80);

  $message = new xmlrpcmsg('weblogUpdates.ping',
    array(new xmlrpcval($name), new xmlrpcval($url)));

  $result = $client->send($message);

  if (!$result || $result->faultCode()) {
    watchdog('error', 'failed to notify "weblogs.com" (site)');
  }

  unset($client);
}

/**
 * Define a custom search routine.
 *
 * This hook allows a module to perform searches on content it defines
 * (custom node types, users, or comments, for example) when a site search
 * is performed.
 *
 * Note that you can use form API to extend the search. You will need to use
 * hook_form_alter() to add any additional required form elements. You can
 * process their values on submission using a custom validation function.
 * You will need to merge any custom search values into the search keys
 * using a key:value syntax. This allows all search queries to have a clean
 * and permanent URL. See node_form_alter() for an example.
 *
 * @param $op
 *   A string defining which operation to perform:
 *   - 'name': the hook should return a translated name defining the type of
 *     items that are searched for with this module ('content', 'users', ...)
 *   - 'reset': the search index is going to be rebuilt. Modules which use
 *     hook_update_index() should update their indexing bookkeeping so that it
 *     starts from scratch the next time hook_update_index() is called.
 *   - 'search': the hook should perform a search using the keywords in $keys
 *   - 'status': if the module implements hook_update_index(), it should return
 *     an array containing the following keys:
 *     - remaining: the amount of items that still need to be indexed
 *     - total: the total amount of items (both indexed and unindexed)
 *
 * @param $keys
 *   The search keywords as entered by the user.
 *
 * @return
 *   An array of search results.
 *   Each item in the result set array may contain whatever information
 *   the module wishes to display as a search result.
 *   To use the default search result display, each item should be an
 *   array which can have the following keys:
 *   - link: the URL of the found item
 *   - type: the type of item
 *   - title: the name of the item
 *   - user: the author of the item
 *   - date: a timestamp when the item was last modified
 *   - extra: an array of optional extra information items
 *   - snippet: an excerpt or preview to show with the result
 *     (can be generated with search_excerpt())
 *   Only 'link' and 'title' are required, but it is advised to fill in
 *   as many of these fields as possible.
 *
 * The example given here is for node.module, which uses the indexed search
 * capabilities. To do this, node module also implements hook_update_index()
 * which is used to create and maintain the index.
 *
 * We call do_search() with the keys, the module name and extra SQL fragments
 * to use when searching. See hook_update_index() for more information.
 *
 * @ingroup search
 */
function hook_search($op = 'search', $keys = null) {
  switch ($op) {
    case 'name':
      return t('content');
    case 'reset':
      variable_del('node_cron_last');
      return;
    case 'search':
      $find = do_search($keys, 'node', 'INNER JOIN {node} n ON n.nid = i.sid '. node_access_join_sql() .' INNER JOIN {users} u ON n.uid = u.uid', 'n.status = 1 AND '. node_access_where_sql());
      $results = array();
      foreach ($find as $item) {
        $node = node_load(array('nid' => $item));
        $extra = node_invoke_nodeapi($node, 'search result');
        $results[] = array('link' => url('node/'. $item),
                           'type' => node_invoke($node, 'node_name'),
                           'title' => $node->title,
                           'user' => theme('username', $node),
                           'date' => $node->changed,
                           'extra' => $extra,
                           'snippet' => search_excerpt($keys, check_output($node->body, $node->format)));
      }
      return $results;
  }
}

/**
 * Format a search result.
 *
 * This hook allows a module to apply custom formatting to its search results.
 *
 * @param $item
 *   The search result to display, as returned in an array by hook_search().
 * @return
 *   A string containing an HTML representation of the search result.
 *
 * Default formatting can be had by omitting this hook and returning your
 * search results as arrays with the right keys. See hook_search().
 *
 */
function hook_search_item($item) {
  $output .= ' <b><u><a href="'. $item['link']
    .'">'. $item['title'] .'</a></u></b><br />';
  $output .= ' <small>' . $item['description'] . '</small>';
  $output .= '<br /><br />';

  return $output;
}

/**
 * Preprocess text for the search index.
 *
 * This hook is called both for text added to the search index, as well as
 * the keywords users have submitted for searching.
 *
 * This is required for example to allow Japanese or Chinese text to be
 * searched. As these languages do not use spaces, it needs to be split into
 * separate words before it can be indexed. There are various external
 * libraries for this.
 *
 * @param $text
 *   The text to split. This is a single piece of plain-text that was
 *   extracted from between two HTML tags. Will not contain any HTML entities.
 * @return
 *   The text after processing.
 */
function hook_search_preprocess($text) {
  // Do processing on $text
  return $text;
}

/**
 * Declare administrative settings for a module.
 *
 * This hook provides an administrative interface for controlling various
 * settings for this module. A menu item for the module under "administration >>
 * settings" will appear in the administrative menu when this hook is implemented.
 *
 * @return
 *   An array containing form items to place on the module settings
 *   page.
 *
 * The form items defined on the settings page will be saved with
 * variable_set(), and can be later retrieved with variable_get(). If you
 * need to store more complicated data (for example, in a separate table),
 * define your own administration page and link to it using hook_menu().
 *
 * NOTE: if you are using 'checkboxes' or a multiple select, you may wish to
 * include the array_filter element within your form:
 * $form['array_filter'] = array('#type' => 'value', '#value' => TRUE);
 */
function hook_settings() {
  $form['example_a'] = array(
    '#type' => 'textfield',
    '#title' => t('Setting A'),
    '#default_value' => variable_get('example_a', 'Default setting'),
    '#size' => 20,
    '#maxlength' => 255,
    '#description' => t('A description of this setting.'),
  );
  $form['example_b'] = array(
    '#type' => 'checkbox',
    '#title' => t('Setting B'),
    '#default_value' => variable_get('example_b', 0),
    '#description' => t('A description of this setting.'),
  );

  return $form;
}

/**
 * Act on taxonomy changes.
 *
 * This hook allows modules to take action when the terms and vocabularies
 * in the taxonomy are modified.
 *
 * @param $op
 *   What is being done to $object. Possible values:
 *   - "delete"
 *   - "insert"
 *   - "update"
 *   - "form"
 * @param $type
 *   What manner of item $object is. Possible values:
 *   - "term"
 *   - "vocabulary"
 * @param $object
 *   The item on which $op is being performed.
 * @return
 *   None for delete, insert and update.
 *   For operations "form", a $form object containing the form api elements that you wish
 *   to display on the form.
 */
function hook_taxonomy($op, $type, $object = NULL) {
  if ($type == 'vocabulary' && ($op == 'insert' || $op == 'update')) {
    if (variable_get('forum_nav_vocabulary', '') == ''
        && in_array('forum', $object['nodes'])) {
      // since none is already set, silently set this vocabulary as the navigation vocabulary
      variable_set('forum_nav_vocabulary', $object['vid']);
    }
  }
}

/**
 * Update Drupal's full-text index for this module.
 *
 * Modules can implement this hook if they want to use the full-text indexing
 * mechanism in Drupal.
 *
 * This hook is called every cron run if search.module is enabled. A module
 * should check which of its items were modified or added since the last
 * run. It is advised that you implement a throttling mechanism which indexes
 * at most 'search_cron_limit' items per run (see example below).
 *
 * You should also be aware that indexing may take too long and be aborted if
 * there is a PHP time limit. That's why you should update your internal
 * bookkeeping multiple times per run, preferably after every item that
 * is indexed.
 *
 * Per item that needs to be indexed, you should call search_index() with
 * its content as a single HTML string. The search indexer will analyse the
 * HTML and use it to assign higher weights to important words (such as
 * titles). It will also check for links that point to nodes, and use them to
 * boost the ranking of the target nodes.
 *
 * @ingroup search
 */
function hook_update_index() {
  $last = variable_get('node_cron_last', 0);
  $limit = (int)variable_get('search_cron_limit', 100);

  $result = db_query_range('SELECT n.nid, c.last_comment_timestamp FROM {node} n LEFT JOIN {node_comment_statistics} c ON n.nid = c.nid WHERE n.status = 1 AND n.moderate = 0 AND (n.created > %d OR n.changed > %d OR c.last_comment_timestamp > %d) ORDER BY GREATEST(n.created, n.changed, c.last_comment_timestamp) ASC', $last, $last, $last, 0, $limit);

  while ($node = db_fetch_object($result)) {
    $last_comment = $node->last_comment_timestamp;
    $node = node_load(array('nid' => $node->nid));

    // We update this variable per node in case cron times out, or if the node
    // cannot be indexed (PHP nodes which call drupal_goto, for example).
    // In rare cases this can mean a node is only partially indexed, but the
    // chances of this happening are very small.
    variable_set('node_cron_last', max($last_comment, $node->changed, $node->created));

    // Get node output (filtered and with module-specific fields).
    if (node_hook($node, 'view')) {
      node_invoke($node, 'view', false, false);
    }
    else {
      $node = node_prepare($node, false);
    }
    // Allow modules to change $node->body before viewing.
    node_invoke_nodeapi($node, 'view', false, false);

    $text = '<h1>'. drupal_specialchars($node->title) .'</h1>'. $node->body;

    // Fetch extra data normally not visible
    $extra = node_invoke_nodeapi($node, 'update index');
    foreach ($extra as $t) {
      $text .= $t;
    }

    // Update index
    search_index($node->nid, 'node', $text);
  }
}

/**
 * Act on user account actions.
 *
 * This hook allows modules to react when operations are performed on user
 * accounts.
 *
 * @param $op
 *   What kind of action is being performed. Possible values:
 *   - "categories": A set of user information categories is requested.
 *   - "delete": The user account is being deleted. The module should remove its
 *     custom additions to the user object from the database.
 *   - "form": The user account edit form is about to be displayed. The module
 *     should present the form elements it wishes to inject into the form.
 *   - "submit": Modify the account before it gets saved.
 *   - "insert": The user account is being added. The module should save its
 *     custom additions to the user object into the database and set the saved
 *     fields to NULL in $edit.
 *   - "login": The user just logged in.
 *   - "logout": The user just logged out.
 *   - "load": The user account is being loaded. The module may respond to this
 *     and insert additional information into the user object.
 *   - "register": The user account registration form is about to be displayed.
 *     The module should present the form elements it wishes to inject into the form.
 *   - "update": The user account is being changed. The module should save its
 *     custom additions to the user object into the database and set the saved
 *     fields to NULL in $edit.
 *   - "validate": The user account is about to be modified. The module should
 *     validate its custom additions to the user object, registering errors as
 *     necessary.
 *   - "view": The user's account information is being displayed. The module
 *     should format its custom additions for display.
 * @param &$edit
 *   The array of form values submitted by the user.
 * @param &$account
 *   The user object on which the operation is being performed.
 * @param $category
 *   The active category of user information being edited.
 * @return
 *   This varies depending on the operation.
 *   - "categories": A linear array of associative arrays. These arrays have keys:
 *     - "name": The internal name of the category.
 *     - "title": The human-readable, localized name of the category.
 *     - "weight": An integer specifying the category's sort ordering.
 *   - "submit": None.
 *   - "insert": None.
 *   - "update": None.
 *   - "delete": None.
 *   - "login": None.
 *   - "logout": None.
 *   - "load": None.
 *   - "form": A $form array containing the form elements to display.
 *   - "register": A linear array of form groups for the specified category. Each group
 *     is represented as an associative array with keys:
 *     - "title": The human-readable, localized name of the group.
 *     - "data": A string containing the form elements to display.
 *     - "weight": An integer specifying the group's sort ordering.
 *   - "validate": None.
 *   - "view": An associative array of strings to display, keyed by category name.
 */
function hook_user($op, &$edit, &$account, $category = NULL) {
  if ($op == 'form' && $category == 'account') {
    $form['comment_settings'] = array(
      '#type' => 'fieldset',
      '#title' => t('Comment settings'),
      '#collapsible' => TRUE,
      '#weight' => 4);
    $form['comment_settings']['signature'] = array(
      '#type' => 'textarea',
      '#title' => t('Signature'),
      '#default_value' => $edit['signature'],
      '#description' => t('Your signature will be publicly displayed at the end of your comments.'));
    return $form;
  }
}

/**
 * Register XML-RPC callbacks.
 *
 * This hook lets a module register callback functions to be called when
 * particular XML-RPC methods are invoked by a client.
 *
 * @return
 *   An array which maps XML-RPC methods to Drupal functions. Each array
 *   element is either a pair of method => function or an array with four
 *   entries:
 *   - The XML-RPC method name (for example, module.function).
 *   - The Drupal callback function (for example, module_function).
 *   - The method signature is an array of XML-RPC types. The first element
 *     of this array is the type of return value and then you should write a
 *     list of the types of the parameters. XML-RPC types are the following
 *     (See the types at http://www.xmlrpc.com/spec):
 *       - "boolean": 0 (false) or 1 (true).
 *       - "double": a floating point number (for example, -12.214).
 *       - "int": a integer number (for example,  -12).
 *       - "array": an array without keys (for example, array(1, 2, 3)).
 *       - "struct": an associative array or an object (for example,
 *          array('one' => 1, 'two' => 2)).
 *       - "date": when you return a date, then you may either return a
 *          timestamp (time(), mktime() etc.) or an ISO8601 timestamp. When
 *          date is specified as an input parameter, then you get an object,
 *          which is described in the function xmlrpc_date
 *       - "base64": a string containing binary data, automatically
 *          encoded/decoded automatically.
 *       - "string": anything else, typically a string.
 *   - A descriptive help string, enclosed in a t() function for translation purposes.
 *   Both forms are shown in the example.
 */
function hook_xmlrpc() {
  return array(
    'drupal.login' => 'drupal_login',
    array(
      'drupal.site.ping',
      'drupal_directory_ping',
      array('boolean', 'string', 'string', 'string', 'string', 'string'),
      t('Handling ping request'))
  );
}

/**
 * @} End of "addtogroup hooks".
 */
Navigation

    * API reference
          o Drupal 4.7
                + constants
                + files
                + functions
                + topics
          o Drupal 4.6
          o Drupal HEAD

