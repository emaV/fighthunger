<?php
// $Id: node.php,v 1.24.2.3 2006/11/01 16:08:42 jvandyk Exp $

/**
 * @file
 * These hooks are defined by node modules, modules that define a new kind
 * of node.
 *
 * If you don't need to make a new node type but rather extend the existing
 * ones, you should instead investigate using hook_nodeapi().
 *
 * Node hooks are typically called by node.module using node_invoke().
 */

/**
 * @addtogroup hooks
 * @{
 */


/**
 * Define the human-readable name of a node type.
 *
 * This is a hook used by node modules. This hook is required of modules
 * that define a node type. It is called to determine the names of the module's
 * nodes.
 *
 * @return
 *   An array of information on the module's nodes. The array contains a
 *   sub-array for each node with the node name as the key. Each sub-array has
 *   two elements, 'name' and 'base'.
 *
 * The 'name' value is a human-readable name for the node and while the 'base'
 * value tells Drupal how a module's functions map to hooks (i.e. if the base
 * is example_foo then example_foo_insert will be called when inserting the
 * node).
 *
 * To prevent namespace conflicts, each node type defined by a module
 * should be prefixed by the name of the module and an underscore.
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_node_info() {
  return array(
    'project_project' => array('name' => t('project'), 'base' => 'project_project'),
    'project_issue' => array('name' => t('issue'), 'base' => 'project_issue')
  );
}

/**
 * Define access restrictions.
 *
 * This hook allows node modules to limit access to the node types they
 * define.
 *
 * @param $op
 *   The operation to be performed. Possible values:
 *   - "create"
 *   - "delete"
 *   - "update"
 *   - "view"
 * @param $node
 *   The node on which the operation is to be performed, or, if it does
 *   not yet exist, the type of node to be created.
 * @return
 *   TRUE if the operation may be performed; FALSE if the operation may not be
 *   returned; NULL to not override the settings in the node_access table.
 *
 * The administrative account (user ID #1) always passes any access check,
 * so this hook is not called in that case. If this hook is not defined for
 * a node type, all access checks will fail, so only the administrator will
 * be able to see content of that type. However, users with the "administer
 * nodes" permission may always view and edit content through the
 * administrative interface.
 *
 * For a detailed usage example, see node_example.module.
 *
 * @ingroup node_access
 */
function hook_access($op, $node) {
  global $user;

  if ($op == 'create') {
    return user_access('create stories');
  }

  if ($op == 'update' || $op == 'delete') {
    if (user_access('edit own stories') && ($user->uid == $node->uid)) {
      return TRUE;
    }
  }
}

/**
 * Respond to node deletion.
 *
 * This is a hook used by node modules. It is called to allow the module
 * to take action when a node is being deleted from the database by, for
 * example, deleting information from related tables.
 *
 * @param &$node
 *   The node being deleted.
 * @return
 *   None.
 *
 * To take action when nodes of any type are deleted (not just nodes of
 * the type defined by this module), use hook_nodeapi() instead.
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_delete(&$node) {
  db_query('DELETE FROM {mytable} WHERE nid = %d', $node->nid);
}

/**
 * This is a hook used by node modules. It is called after validation has succeeded and before insert/update.
 * It is used to for actions which must happen only if the node is to be saved. Usually, $node is
 * changed in some way and then the actual saving of that change is left for the insert/update hooks.
 *
 * @param &$node
 *   The node being saved.
 * @return
 *   None.
 *
 * For a detailed usage example, see fileupload.module.
 */
function hook_submit(&$node) {
  // if a file was uploaded, move it to the files directory
  if ($file = file_check_upload('file')) {
    $node->file = file_save_upload($file, file_directory_path(), false);
  }
}

/**
 * This is a hook used by node modules. It is called after load but before the node is shown on the add/edit form.
 *
 * @param &$node
 *   The node being saved.
 * @return
 *   None.
 *
 * For a usage example, see image.module.
 */
function hook_prepare(&$node) {
  if ($file = file_check_upload($field_name)) {
    $file = file_save_upload($field_name, _image_filename($file->filename, NULL, TRUE));
    if ($file) {
      if (!image_get_info($file->filepath)) {
        form_set_error($field_name, t('Uploaded file is not a valid image'));
        return;
      }
    }
    else {
      return;
    }
    $node->images['_original'] = $file->filepath;
    _image_build_derivatives($node, true);
    $node->new_file = TRUE;
}
}


/**
 * Display a node editing form.
 *
 * This hook, implemented by node modules, is called to retrieve the form
 * that is displayed when one attempts to "create/edit" an item. This form is
 * displayed at the URI http://www.example.com/?q=node/<add|edit>/nodetype.
 *
 * @param &$node
 *   The node being added or edited.
 * @param &$param
 *   The hook can set this variable to an associative array of attributes
 *   to add to the enclosing \<form\> tag.
 * @return
 *   An array containing the form elements to be displayed in the node
 *   edit form.
 *
 * The submit and preview buttons, taxonomy controls, and administrative
 * accoutrements are displayed automatically by node.module. This hook
 * needs to return the node title, the body text area, and fields
 * specific to the node type.
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_form(&$node, &$param) {
  $form['title'] = array(
    '#type'=> 'textfield',
    '#title' => t('Title'),
    '#required' => TRUE,
  );
  $form['body'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#rows' => 20,
    '#required' => TRUE,
  );
  $form['field1'] = array(
    '#type' => 'textfield',
    '#title' => t('Custom field'),
    '#default_value' => $node->field1,
    '#maxlength' => 127,
  );
  $form['selectbox'] = array(
    '#type' => 'select',
    '#title' => t('Select box'),
    '#default_value' => $node->selectbox,
    '#options' => array(
      1 => 'Option A',
      2 => 'Option B',
      3 => 'Option C',
    ),
    '#description' => t('Please choose an option.'),
  );

  return $form;
}

/**
 * Respond to node insertion.
 *
 * This is a hook used by node modules. It is called to allow the module
 * to take action when a new node is being inserted in the database by,
 * for example, inserting information into related tables.
 *
 * @param $node
 *   The node being inserted.
 * @return
 *   None.
 *
 * To take action when nodes of any type are inserted (not just nodes of
 * the type(s) defined by this module), use hook_nodeapi() instead.
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_insert($node) {
  db_query("INSERT INTO {mytable} (nid, extra)
    VALUES (%d, '%s')", $node->nid, $node->extra);
}

/**
 * Load node-type-specific information.
 *
 * This is a hook used by node modules. It is called to allow the module
 * a chance to load extra information that it stores about a node, or
 * possibly replace already loaded information - which can be dangerous.
 *
 * @param $node
 *   The node being loaded. At call time, node.module has already loaded
 *   the basic information about the node, such as its node ID (nid),
 *   title, and body.
 * @return
 *   An object containing properties of the node being loaded. This will
 *   be merged with the passed-in $node to result in an object containing
 *   a set of properties resulting from adding the extra properties to
 *   the passed-in ones, and overwriting the passed-in ones with the
 *   extra properties if they have the same name as passed-in properties.
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_load($node) {
  $additions = db_fetch_object(db_query('SELECT * FROM {mytable} WHERE nid = %s', $node->nid));
  return $additions;
}

/**
 * Respond to node updating.
 *
 * This is a hook used by node modules. It is called to allow the module
 * to take action when an edited node is being updated in the database by,
 * for example, updating information in related tables.
 *
 * @param $node
 *   The node being updated.
 * @return
 *   None.
 *
 * To take action when nodes of any type are updated (not just nodes of
 * the type(s) defined by this module), use hook_nodeapi() instead.
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_update($node) {
  db_query("UPDATE {mytable} SET extra = '%s' WHERE nid = %d",
    $node->extra, $node->nid);
}

/**
 * Verify a node editing form.
 *
 * This is a hook used by node modules. It is called to allow the module
 * to verify that the node is in a format valid to post to the site. It
 * can also be used to make changes to the node before submission, such
 * as node-type-specific formatting. Errors should be set with
 * form_set_error().
 *
 * @param &$node
 *   The node to be validated.
 *
 * To validate nodes of all types (not just nodes of the type(s) defined by
 * this module), use hook_nodeapi() instead.
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_validate(&$node) {
  if ($node) {
    if ($node->end && $node->start) {
      if ($node->start > $node->end) {
        form_set_error('time', t('An event may not end before it starts.'));
      }
    }
  }
}

/**
 * Display a node.
 *
 * This is a hook used by node modules. It allows a module to define a
 * custom method of displaying its nodes, usually by displaying extra
 * information particular to that node type.
 *
 * @param &$node
 *   The node to be displayed.
 * @param $teaser
 *   Whether we are to generate a "teaser" or summary of the node, rather than
 *   display the whole thing.
 * @param $page
 *   Whether the node is being displayed as a standalone page. If this is
 *   TRUE, the node title should not be displayed, as it will be printed
 *   automatically by the theme system. Also, the module may choose to alter
 *   the default breadcrumb trail in this case.
 * @return
 *   None. The passed-by-reference $node parameter should be modified as
 *   necessary so it can be properly presented by theme('node', $node). This
 *   means, for instance, that content should be passed through the filter
 *   system by calling check_output() on appropriate fields or by sending the
 *   node through node_prepare().
 *
 * For a detailed usage example, see node_example.module.
 */
function hook_view(&$node, $teaser = FALSE, $page = FALSE) {
  if ($page) {
    $breadcrumb = array();
    $breadcrumb[] = array('path' => 'example', 'title' => t('example'));
    $breadcrumb[] = array('path' => 'example/'. $node->field1,
      'title' => t('%category', array('%category' => $node->field1)));
    $breadcrumb[] = array('path' => 'node/'. $node->nid);
    menu_set_location($breadcrumb);
  }

  $node = node_prepare($node, $teaser);
}

/**
 * @} End of "addtogroup hooks".
 */ 