<?php
// $Id: subproducts.inc,v 1.3.2.1 2006/09/04 07:08:15 nedjo Exp $

/**
 * The first page of the variation generation wizard.
 *
 * In this page, we present a form for selecting attributes to use
 * in generating subproducts. For example, if a given product type
 * supported sizes 'small' and 'large' and colors 'pink' and 'white',
 * the UI would list these attributes as options. Users can also set
 * default stock amounts.
 *
 * @param $node
 *   A node object representing a parent product.
 */
function subproducts_generate_wizard1($node) {
  if (!$variations = subproducts_get_variations($node->ptype)) {
    $ptypes = product_get_ptypes();
    if (user_access('administer variations')) {
      $message = t('No variations defined.  To generate subproducts, first <a href="%link">define variations and attributes</a> for the product type %type.', array('%type' => $ptypes[$node->ptype], '%link' => url('admin/variation/' . $node->ptype)));
    }
    else {
      $message = t('No variations defined.  To generate subproducts, variations and attributes must be defined for the product type %type.', array('%type' => $ptypes[$node->ptype]));
    }
    drupal_set_message($message);
    return;
  }
  
  drupal_set_title(t('%title: select variations', array('%title' => $node->title)));

  $defaults =  variable_get('subproducts_' . $node->ptype . '_defaults', 0);

  $help = '<h2>' . t('%product: select default values for each of the following variations.', array('%product' => $node->title)) . '</h2>';
  $help .=  t('<p>On this page you can select which parameters to use for generating subproducts, and also set stock counts.</p>
    <p>Settings you put here for stock will be <em>summed</em> to give a default stock amount for each subproduct.
    </p>');
  $form['help'] = array('#type' => 'markup', '#value' => $help);

  foreach($variations as $variation) {
    $form['title'][$variation->vid] = array(
      '#type' => 'markup',
      '#value' => $variation->name
    );
    $form['settings']['#tree'] = TRUE;
    if (is_array($variation->attributes)) {
      foreach ($variation->attributes as $attribute) {
        $form['settings'][$variation->vid][$attribute->aid]['use'] = array(
          '#type' => 'checkbox',
          '#title' => '',
          '#return_value' => 1,
          '#default_value' => ($defaults && ($defaults[$variation->vid][$attribute->aid]['use'] == 0)) ? 0 : 1
        );
        $form['settings'][$variation->vid][$attribute->aid]['name'] = array(
          '#type' => 'markup',
          '#value' => $attribute->name
        );
        $form['settings'][$variation->vid][$attribute->aid]['surcharge'] = array(
          '#type' => 'markup',
          '#value' => $attribute->surcharge
        );
        $form['settings'][$variation->vid][$attribute->aid]['stock'] = array(
          '#type' => 'textfield',
          '#title' => '',
          '#default_value' => ($defaults && $defaults[$variation->vid][$attribute->aid]['stock']) ? $defaults[$variation->vid][$attribute->aid]['stock'] : 0,
          '#size' => 10,
          '#maxlength' => 10
        );
      }
    }
  }
  $form['default'] = array(
    '#type' => 'checkbox',
    '#title' => t('Save as default'),
    '#return_value' => 1,
    '#default_value' => 1,
    '#description' => t('Check to have these settings saved as defaults, which will be loaded the next time you generate subproducts.'),
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Review options'),
  );
  return drupal_get_form('subproducts_generate_wizard1', $form);
}

/**
 * Theme the form for the first page of the variation generation wizard.
 */
function theme_subproducts_generate_wizard1($form) {
  $header = array(
    array('data' => ''),
    array('data' => t('Parameter')),
    array('data' => t('Surcharge')),
    array('data' => t('# in stock'))
  );
  $rows = array();
  foreach (element_children($form['settings']) as $key) {
    $rows[] = array(
      array(
        'data' => '<h3>' . form_render($form['title'][$key]) . '</h3>',
        'align' => 'center',
        'colspan' => 4
      )
    );
    foreach (element_children($form['settings'][$key]) as $attribute) {
      $rows[] = array(
        array('data' => form_render($form['settings'][$key][$attribute]['use'])),
        array('data' => form_render($form['settings'][$key][$attribute]['name'])),
        array('data' => form_render($form['settings'][$key][$attribute]['surcharge'])),
        array('data' => form_render($form['settings'][$key][$attribute]['stock']))
      );
    }
  }
  $output = form_render($form['help']);
  $output .= theme('table', $header, $rows);
  $output .= form_render($form['default']);
  $output .= form_render($form['submit']);
  $output .= form_render($form);
  return $output;
}

/**
 * The second page of the variation generation wizard.
 *
 * On this page, we present list all the possible combinations 
 * (permutations) of the attributes selected on the prior page.
 * Users can finalize here which subproducts will be generated,
 * and set individual initial stock levels.
 *
 * @param $node
 *   A node object representing a parent product.
 */
function subproducts_generate_wizard2($node) {
  $edit = $_POST['edit'];

  $settings = $edit['settings'];

  if ($edit['default']) {
    variable_set('subproducts_' . $node->ptype . '_defaults', $settings);
  }

  $variations = subproducts_get_variations($node->ptype);
  
  drupal_set_title(t('%title: refine settings', array('%title' => $node->title)));

  $help = '<h2>' . t('%product: refine settings for each subproduct.', array('%product' => $node->title)) . '</h2>';
  $help .= '<p>' . t('Here is the full list of subproducts.  You can select which ones you wish to create and adjust their settings before generating.') . '</p>';
  $form['help'] = array('#type' => 'markup', '#value' => $help);

  $options = array();
  foreach($variations as $variation) {
    if (is_array($variation->attributes)) {
      $form['header'][$variation->vid] = array('#type' => 'markup', '#value' => $variation->name);
      foreach ($variation->attributes as $attribute) {
        // Only include this option in the array if it was selected in the previous screen.
        if ($settings[$variation->vid][$attribute->aid]['use']) {
          $options[$variation->vid][] = $attribute->aid;
        }
      }
    }
  }
  /*
  if(count($options) < count($variations)) {
    return t('Error.  You must select at least one option from each of the variations.');
  }
  */
  $permutations = subproducts_permute($options);
  foreach ($permutations as $permutation) {
    // Check if this permutation already exists.  If so, skip.
    if(subproducts_get_variations_subproduct($node->nid, $permutation)) {
      continue;
    }
    $price = $node->price;
    $stock = 0;
    $fields = array();
    foreach ($permutation as $key => $value) {
      $fields[] = $key . '|' . $value;
    }
    $fields = implode('||', $fields);
    $form['permutations']['#tree'] = TRUE;
    $form['permutations'][$fields]['use'] = array(
      '#type' => 'checkbox',
      '#title' => '',
      '#return_value' => 1,
      '#default_value' => 1
    );
    foreach ($permutation as $vid => $aid) {
      $form['name'][$fields][$aid] = array('#type' => 'markup', '#value' => $variations[$vid]->attributes[$aid]->name);
      $price += $variations[$vid]->attributes[$aid]->surcharge;
      $stock += $settings[$vid][$aid]['stock'];
    }
    // Set price and stock data.
    $form['price'][$fields] = array('#type' => 'markup', '#value' => $price);
    $form['permutations'][$fields]['price'] = array(
      '#type' => 'hidden',
      '#value' => $price
    );
    $form['permutations'][$fields]['stock'] = array(
      '#type' => 'textfield',
      '#title' => '',
      '#default_value' => $stock,
      '#size' => 10,
      '#maxlength' => 10
    );
  }
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Generate'),
  );
  return drupal_get_form('subproducts_generate_wizard2', $form);
}

/**
 * Theme the form for the second page of the variation generation wizard.
 */
function theme_subproducts_generate_wizard2($form) {
  $header = array(array('data' => ''));
  foreach (element_children($form['header']) as $key) {
    $header[] = array('data' => form_render($form['header'][$key]));
  }
  $header[] = array('data' => t('Price'));
  $header[] = array('data' => t('Stock'));
  $rows = array();
  $i = 0;
  foreach (element_children($form['permutations']) as $key) {
    $rows[$i] = array(
      array('data' => form_render($form['permutations'][$key]['use']))
    );
    foreach(element_children($form['name'][$key]) as $aid) {
      $rows[$i][] = array(
        'data' => form_render($form['name'][$key][$aid]),
        'align' => 'center'
      );
    }
    $rows[$i][] = array('data' => form_render($form['price'][$key]));
    $rows[$i][] = array('data' => form_render($form['permutations'][$key]['stock']));
    $i++;
  }
  $output = form_render($form['help']);
  $output .= theme('table', $header, $rows);
  $output .= form_render($form['submit']);
  $output .= form_render($form);
  return $output;
}

/**
 * The third stage of the variation generation wizard.
 *
 * Having received user input on which attribute combinations
 * to use for new subproducts, we generate the requested 
 * subproducts. 
 *
 * @param $node
 *   A node object representing a parent product.
 */
function subproducts_generate_wizard3($node) {
  $node->pparent = $node->nid;
//  $node->status = 0;
  $node->comment = 0;
  $node->voting = 0;
  $children = $node->children;
  $title = $node->title;
  // It's not clear where this array key comes from, but it generates errors and so needs to be unset.
  unset($node->path);
  $permutations = $_POST['edit']['permutations'];
  foreach ($permutations as $key => $value) {
    if ($value['use']) {
      $title_bits = array();
      $node->variations = array();
      $pairs = explode('||', $key);
      foreach ($pairs as $pair) {
        $item = explode('|', $pair);
        $node->variations[] = $item[1];
        $attribute = subproducts_get_attribute($item[1]);
        $title_bits[] = $attribute->name;
      }
      $node->title = $title . ' ' . implode(' ', $title_bits);
      $node->price = $value['price'];
      $node->stock = $value['stock'];
      unset($node->nid);
      unset($node->path);
      node_save($node);
    }
  }
  drupal_goto('node/' . $node->pparent);
}

/**
 * The first page of the base generation wizard.
 *
 * On this page, we present a form for selecting parent products to
 * base our subproducts on and which of their attributes to use.
 * For example, if our product were based on apparel products, we would
 * select from available parent apparel products and then from all
 * the available attributes that those apparel products come in.
 *
 * @param $node
 *   A node object representing a parent product.
 */
function subproducts_select_bases_wizard1($node) {
  drupal_set_title(t('%title: select base models', array('%title' => $node->title)));
  $help_products = '<h2>' . t('%product: select the base models to apply your product to.', array('%product' => $node->title)) . '</h2>';
  $help_products .=  t('<p>On this page you can select which products to use as the base models for your product.
    </p>');
  $form['help_products'] = array('#type' => 'markup', '#value' => $help_products);

  $ptypes = product_get_ptypes();
  $base_ptypes = subproducts_base_product_types($node->ptype);

  foreach ($base_ptypes as $ptype) {
    $form['title'][$ptype] = array('#type' => 'markup', '#value' => $ptypes[$ptype]);

    // For now, we're selecting only products that have children.  This requirement should
    // be relaxed to enable linking with non-variation products.
    $exists_message = FALSE;

    // Give administrators access to unpublished base products.
    $status = subproducts_admin_sql();

    $result = db_query(db_rewrite_sql("SELECT n.nid, n.title, p1.price FROM {node} n INNER JOIN {ec_product} p1 ON n.nid = p1.nid INNER JOIN {ec_product} p2 ON p1.nid = p2.pparent WHERE " . $status . " p1.pparent = 0 AND p1.ptype = '%s' ORDER BY n.sticky DESC, n.title DESC"), $ptype);
    while($product = db_fetch_object($result)) {

      if (db_num_rows(db_query("SELECT p1.* FROM {ec_product} p1 INNER JOIN {ec_product_base} b ON p1.nid = b.product INNER JOIN {ec_product} p2 ON b.base = p2.nid WHERE p1.pparent = %d AND p2.pparent = %d", $node->nid, $product->nid))) {
        $product->title .= '*';
        $exists_message = TRUE;
      }
      $form['products']['#tree'] = TRUE;
      $form['products'][$ptype][$product->nid]['use'] = array(
        '#type' => 'checkbox',
        '#title' => '',
        '#return_value' => 1,
        '#default_value' => 0
      );
      $form['products'][$ptype][$product->nid]['title'] = array(
        '#type' => 'markup',
        '#value' => $product->title
      );
      $form['products'][$ptype][$product->nid]['price'] = array(
        '#type' => 'markup',
        '#value' => $product->price
      );

    }
    if ($exists_message) {
      $form['message'] = array(
        '#type' => 'markup',
        '#value' => t('* There are already some subproducts with this base.')
      );
    }

    // Provide selection options for variation attributes.
    $variations = subproducts_get_variations($ptype);
    $options = array();
    foreach ($variations as $variation) {
      $options[] = $variation->name;
    }
    $help_attributes = '<h3>' . t('Select attributes') . '</h3>';
    $help_attributes .= '<p>' . t('Please select all the %options options you want your %ptype 
      to come in.  On the next screen you will have a chance to confirm the particular combinations you want.', array('%options' => implode(t(' and '), $options), '%ptype' => $ptypes[$ptype]));
    $form['help_attributes'][$ptype] = array('#type' => 'markup', '#value' => $help_attributes);

    // Find all the attributes that have been associated with products of this ptype, so we can later
    // present a list that includes only used attributes.
    $result = db_query("SELECT DISTINCT(a.aid) FROM {ec_product_attribute} a INNER JOIN {ec_product} p ON a.nid = p.nid INNER JOIN {node} n ON a.nid = n.nid WHERE " . $status . "p.ptype = '%s'", $ptype);
    $used_attributes = array();
    while($attribute = db_fetch_object($result)) {
      $used_attributes[] = $attribute->aid;
    }

    foreach($variations as $variation) {
      $form['variations'][$variation->vid] = array(
        '#type' => 'markup',
        '#value' => $variation->name
      );
      if (is_array($variation->attributes)) {
        foreach ($variation->attributes as $attribute) {
          if (in_array($attribute->aid, $used_attributes)) {
            $form['settings']['#tree'] = TRUE;
            $form['settings'][$ptype][$variation->vid][$attribute->aid]['use'] = array(
              '#type' => 'checkbox',
              '#title' => '',
              '#return_value' => 1,
              '#default_value' => 0
            );
            $form['settings'][$ptype][$variation->vid][$attribute->aid]['surcharge'] = array(
              '#type' => 'markup',
              '#value' => subproducts_surcharge_extra($attribute, TRUE)
            );
          }
        }
      }
    }
  }
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Review options')
  );
  return drupal_get_form('subproducts_select_bases_wizard1', $form);
}

/**
 * Theme the form for the first page of the base generation wizard.
 */
function theme_subproducts_select_bases_wizard1($form) {
  $output = form_render($form['help_products']);
  $header_products = array(
    array('data' => ''),
    array('data' => '<strong>' . t('Product') . '</strong>'),
    array('data' => '<strong>' . t('Base price') . '</strong>')
  );
  $header_attributes = array(
    array(
      'data' => '<h3>' . t('Variations') . '</h3>',
      'colspan' => count(element_children($form['variations']))
    )
  );
  $rows_products = array();
  $rows_attributes = array();
  foreach (element_children($form['products']) as $key) {
    $output .= '<hr /><h2>' . form_render($form['title'][$key]) . '</h2>';
    foreach (element_children($form['products'][$key]) as $product) {
      $rows_products[] = array(
        array('data' => form_render($form['products'][$key][$product]['use'])),
        array('data' => form_render($form['products'][$key][$product]['title'])),
        array('data' => form_render($form['products'][$key][$product]['price']))
      );
    }
    $output .= theme('table', $header_products, $rows_products);
    $cells = array();
    foreach (element_children($form['settings'][$key]) as $variation) {
      $vheader = array(
        array(
          'data' => '<h3>' .  form_render($form['variations'][$variation]) . '</h3>',
          'colspan' => 2
        )
      );
      $vrows = array();
      foreach (element_children($form['settings'][$key][$variation]) as $attribute) {
        $vrows[] = array(
          array('data' => form_render($form['settings'][$key][$variation][$attribute]['use'])),
          array('data' => form_render($form['settings'][$key][$variation][$attribute]['surcharge']))
        );
      }
      $cells[] = array(
        'data' => theme('table', $vheader, $vrows),
        'valign' => 'top'
      );
    }
    $rows_attributes[] = $cells;
    $output .= form_render($form['help_attributes'][$key]);
    $output .= theme('table', $header_attributes, $rows_attributes);
  }

  $output .= form_render($form['submit']);
  $output .= form_render($form);
  return $output;
}

/**
 * The second page of the base selection wizard.
 *
 * On this page, we allow users to refine the choices they made
 * on the previous page by selecting which specific attributes to use
 * for each selected apparel product.
 *
 * @param $node
 *   A node object representing a parent product.
 */
function subproducts_select_bases_wizard2($node) {
  $edit = $_POST['edit'];

  $products = $edit['products'];
  $settings = $edit['settings'];

  $ptypes = product_get_ptypes();
  $base_ptypes = subproducts_base_product_types($node->ptype);
  
  drupal_set_title(t('%title: refine settings', array('%title' => $node->title)));
  
  foreach ($base_ptypes as $ptype) {
    // Ensure that at least one product was selected from this type.
    $used = FALSE;
    foreach ($products[$ptype] as $product) {
      if ($product['use']) {
        $used = TRUE;
      }
    }
    if (!$used) {
      continue;
    }

    $variations = subproducts_get_variations($ptype);
    $options = array();
    foreach ($variations as $variation) {
      $options[] = $variation->name;
    }
    $form['title'][$ptype] = array('#type' => 'markup', '#value' => $ptypes[$ptype]);
    $form['product']['#tree'] = TRUE;
    foreach ($products[$ptype] as $nid => $use) {
      if ($use['use']) {
        $product = node_load($nid);
        $form['product'][$ptype][$nid] = array('#type' => 'markup', '#value' => $product->title);

        // Provide selection options for variation attributes.
        $help = '<h3>' . t('Select attributes') . '</h3>';
        $help .= '<p>' . t('Please select all the %options options you want the product
          to come in.', array('%options' => implode(t(' and '), $options)));
        $form['help'][$nid] = array('#type' => 'markup', '#value' => $help);

        // Give administrators access to unpublished base products.
        $status = subproducts_admin_sql();

        // Find all the attributes that have been associated with this product, so we can later
        // present a list that includes only used attributes.

        $result = db_query("SELECT DISTINCT(a.aid) FROM {ec_product_attribute} a INNER JOIN {ec_product} p ON a.nid = p.nid INNER JOIN {node} n ON a.nid = n.nid WHERE " . $status . " p.pparent = %d", $nid);
        $used_attributes = array();
        while($attribute = db_fetch_object($result)) {
          $used_attributes[] = $attribute->aid;
        }

        foreach($variations as $variation) {
          $form['variations'][$nid][$variation->vid] = array(
            '#type' => 'markup',
            '#value' => $variation->name
          );
          $form['bases']['#tree'] = TRUE;
          if (is_array($variation->attributes)) {
            foreach ($variation->attributes as $attribute) {
              if (in_array($attribute->aid, $used_attributes)) {
                $form['bases'][$nid][$variation->vid][$attribute->aid]['use'] = array(
                  '#type' => 'checkbox',
                  '#title' => '',
                  '#return_value' => 1,
                  '#default_value' => $settings[$ptype][$variation->vid][$attribute->aid]['use']
                );
                $form['bases'][$nid][$variation->vid][$attribute->aid]['surcharge'] = array(
                  '#type' => 'markup',
                  '#value' => subproducts_surcharge_extra($attribute, TRUE)
                );
              }
            }
          }
        }
      }
    }
  }

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Generate'),
  );
  return drupal_get_form('subproducts_select_bases_wizard2', $form);
}

/**
 * Theme the form for the second page of the base generation wizard.
 */
function theme_subproducts_select_bases_wizard2($form) {
  foreach (element_children($form['product']) as $ptype) {
    $output .= '<h2>' . form_render($form['title'][$ptype]) . '</h2>';
    foreach (element_children($form['product'][$ptype]) as $nid) {
      $output .= '<h2>' . form_render($form['product'][$ptype][$nid]) . '</h2>';
      $output .= form_render($form['help'][$nid]);
      $header = array(
        array(
          'data' => '<h3>' . t('Variations') . '</h3>',
          'colspan' => count(element_children($form['variations'][$nid]))
        )
      );
      $rows = array();
      $cells = array();
      foreach (element_children($form['bases'][$nid]) as $variation) {
        $vheader = array(
          array(
            'data' => '<h3>' . form_render($form['variations'][$nid][$variation]) . '</h3>',
            'colspan' => 2
          )
        );
        $vrows = array();
        foreach (element_children($form['bases'][$nid][$variation]) as $attribute) {
          $vrows[] = array(
            array('data' => form_render($form['bases'][$nid][$variation][$attribute]['use'])),
            array('data' => form_render($form['bases'][$nid][$variation][$attribute]['surcharge']))
          );
        }
        $cells[] = array(
          'data' => theme('table', $vheader, $vrows),
          'valign' => 'top'
        );
      }
      $rows[] = $cells;
      $output .= theme('table', $header, $rows);
    }
  }
  $output .= form_render($form['submit']);
  $output .= form_render($form);
  return $output;
}

/**
 * The third stage of the base selection wizard.
 *
 * Having received user input on which attribute combinations
 * to use for the selected base products, we generate the requested 
 * subproducts. 
 *
 * @param $node
 *   A node object representing a parent product.
 */
function subproducts_select_bases_wizard3($node) {
  $status = subproducts_admin_sql();
  // We copy the original node to retain its properties
  // before selectively removing some, which we don't want
  // in the subproducts.
  $parent = $node;
  $node->pparent = $parent->nid;
//  $node->status = 0;
  $node->comment = 0;
  $node->voting = 0;
  unset($node->price_type);
  unset($node->path);
  $bases = $_POST['edit']['bases'];
  foreach ($bases as $nid => $variations) {
    $options = array();
    foreach ($variations as $vid => $attributes) {
      $array[$vid] = array();
      foreach ($attributes as $aid => $use) {
        if ($use['use']) {
          $options[$vid][] = $aid;
        }
      }
    }
    // Find all the permutations.
    $permutations = subproducts_permute($options);
    // For each permutation, test if there is a product that has the required attributes,
    // and if so create a new child product with it as a base.
    foreach ($permutations as $permutation) {
      $joins = array();
      $wheres = array();
      foreach ($permutation as $index => $aid) {
        $joins[] = 'INNER JOIN {ec_product_attribute} a'. $index .' ON p.nid = a'. $index .'.nid';
        $wheres[] = 'a'. $index .'.aid = ' . $aid;
      }
      $result = db_query('SELECT DISTINCT(p.nid) FROM {ec_product} p INNER JOIN {node} n ON p.nid = n.nid ' . implode(' ', $joins) . ' WHERE ' . implode(' AND ', $wheres) . ' AND ' . $status . ' p.pparent = %d', $nid);
      if (db_num_rows($result)) {
        $match = db_fetch_object($result);
        // If we already have a subproduct with this base product, skip.
        if (count($node->children) && db_num_rows(db_query("SELECT * FROM {ec_product_base} WHERE product IN (%s) AND base = %d", implode(',', $node->children), $match->nid))) {
          continue;
        }
        $node->base = $match->nid;
        // Set values from the base product.
        $base = node_load($node->base);
        $node->title = $parent->title . ' ' . $base->title;
        if (isset($parent->price_type)) {
          $node->base_price = $base->price;
          subproducts_reset_price_base($node, $parent);
        }
        else {
          $node->price = $parent->price + $base->price;
        }
        $node->stock = $base->stock;
        // It's not clear where this array key comes from, but it generates errors and so needs to be unset.
        unset($node[0]);
        unset($node->nid);
        node_save($node);
      }
    }
  }
  // It's not clear where this array key comes from, but it generates errors and so needs to be unset.
  unset($parent[0]);
  // We resave the parent node to trigger any nodeapi hooks that might act on a product with subproducts.
  node_save($parent);
  // Display the parent page.
  drupal_goto('node/' . $parent->nid);
}
