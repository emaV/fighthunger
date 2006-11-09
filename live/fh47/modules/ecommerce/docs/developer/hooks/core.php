<?php
// $Id: core.php,v 1.9.2.1 2006/06/03 07:03:00 sime Exp $

/**
 * @file
 * These are the hooks that are invoked by the Drupal ecommerce package.
 *
 * Core hooks are typically called in all modules at once using
 * module_invoke_all().
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Manipulate the checkout process, including injecting form pages.
 *
 * Checkoutapi can be implemented by any module. It can be used to insert a
 * page into into the checkout process and even validate/save the form data.
 * Another feature of the API is the ability to push data onto the final review
 * page before final checkout. Note that the order of the form pages is controlled
 * via http://example.com/index.php?q=admin/store/checkout
 *
 * @param &$txn
 *   The transaction object for an order. This keeps growing in data from screen
 *   to screen.
 * @param $op
 *   What kind of action is being performed. Possible values:
 *   - "form": Inject a form page into the checkout process. Don't forget to add
 *     a submit button.
 *   - "save": The injected form page has been submitted. Save your data in
 *     this hook. IMPORTANT: $txn->screen must be incremented here in order to
 *     go to the next screen!
 *        $txn->screen++;
 *   - "validate": The customer has just finished editing the form page and is
 *     trying to submit it. This hook can be used to check or even modify the
 *     transaction object. Errors should be set with form_set_error().
 *   - "review": The last page of the checkout process is being viewed before
 *     the order is placed.
 *   - "review_validate": validation for review page.  Called within the form
 *     validate hook so has same requirements.
 *   - "review_save": save section of the review page.  Called within the form
 *     submit hook and has same requirements.
 * @param $arg3
 *   - Optional parameter to pass along.
 * @param $arg4
 *   - Optional parameter to pass along.
 * @return
 *   This varies depending on the operation.
 *   - The "save" and "validate" operations have no return value.
 *   - The "form" returns a form array to build the modules checkout screen.
 *   - The "review" operation should return a form array.  The themeing is done
 *     by theme_hook_review_form
 *   - The "review_validate" operations have no return value.
 *   - The "review_submit" operations have no return value.
 */
function hook_checkoutapi(&$txn, $op, $arg3 = NULL, $arg4 = NULL) {
  $output = '';
  switch ($op) {
    case 'form':
      if ($form = payment_view_methods()) {
        drupal_set_title(t('Please select a payment method'));
        $form[] = array(
          '#type' => 'submit',
          '#value' => t('Continue'),
          );
        return $form;
      }
      else {
        foreach (payment_get_methods() as $module) {
          if (module_invoke($module, 'paymentapi', $edit, 'display name')) {
            $txn->payment_method = $module;
            break;
          }
        }
        return false;
      }

    case 'validate':
      if (!$txn->payment_method) {
        form_set_error('payment_method', t('Please choose a payment method.'));
      }
      break;

    case 'save':
      $txn->screen++;
      break;

    case 'review':
      $form['payment'] = array('#value' => module_invoke($txn->payment_method, 'paymentapi', $txn, 'display name'));
      return $form;

    case 'review_validate':
      break;

    case 'review_save':
      break;
  }
}

/**
 * Add a payment method.
 *
 * The customer can choose one of several payment methods on checkout if more
 * than one method is enabled.
 *
 * @param &$txn
 *   The transaction object for an order. This keeps growing in data from screen
 *   to screen.
 * @param $op
 *   What kind of action is being performed. Possible values:
 *   - "display name": The name of the method displayed to customers.  This is purely
 *     for display purposes and my be descriptive.
 *   - "on checkout": Called after the customer selects their payment method.  This
 *     can be used for any specific validation the payment method needs to impose.
 *     Be sure to call form_set_error() to raise a warning to the system.
 *   - "form": Called durring the checkout process.  This is used to display options to
 *     the user for choosing between payment options.  Nothing wil be displayed if only
 *     one payment option exists.
 *   - "update/insert": Called after the user has submitted the payment page so
 *     any additional payment details can be stored in the database.
 *   - "payment page": Display the form for accepting credit card information or
 *     redirect to a third party payment processor.
 *   - "delete": Called when the transaction is being deleted.
 * @param $arg3
 *   - Optional parameter to pass along.
 * @return
 *   This varies depending on the operation.
 *   - The "display name" operations return a string.
 *   - The "on checkout" operation should use form_set_error() if validation fails.
 *   - The "form" operation should return a valid form array to be merged with the 
 *     full form on the payment checkout screen.
 *   - The "update/insert/delete" and "on checkout" operations have no return value.
 *   - The "payment page" operations should return a HTML string.
 */
function hook_paymentapi(&$txn, $op, $arg3 = '') {

  switch ($op) {
    case 'display name':
      return t('PayPal');

    case 'on checkout':
      paypal_verify_checkout($txn);
      break;

    case 'form':
      break;

    case 'update':
    case 'insert':
      paypal_save($txn);
      break;

    case 'payment page':
      if ($txn->gross > 0) {
        return paypal_goto($txn);
      }
      break;

    case 'delete':
      paypal_delete($txn);
      break;
    }
}

/**
 * Add a product
 *
 * The 
 * @param &$node
 *   The node object for the product.
 * @param $op
 *   What kind of action is being performed.  Possible values:
 *   - "wizard_select": 
 *   - "cart add item":  called when trying to add a product to a shopping cart.  This
 *     allows the product to limit the addition of items.  This is optional as a null
 *     return will be treated as true and the item will be added.
 *   - "attributes":
 *   - "adjust_price": called to provide a price adjustment to the product.  No changes
 *      to node->price should be made.
 *   -  "transaction": I can only guess.  the only place I see this is coupon.module
 *   -  "on payment completion": called on payment completion
 *   -  "subproduct_types": called by subproduct to get a list of supported subproducts
 * @param $arg3
 *   The "adjust_price" operation passes a current price here.
 *   Possible values for attributes operation:
 *   - "in_stock"
 * @param $arg4
 * @return
 *   This value varies depending on the operation.
 *   - The "wizard_select" operation should return an array of product types provided by
 *     the module.  The key should uniquely identify the type and the value should be a 
 *     translated description.
 *   - The "cart add item" operation should return a bool value.  True or NULL will add
 *     to cart and false will redirect.  You must provide you own drupal_set_message
 *     failure message.
 *   - The "ajust_price" operation returns a new price.
 *   - The 'insert'
 *
 */
function hook_productapi(&$node, $op, $arg3 = NULL, $arg4 = NULL) {

  switch($op) {
    case 'wizard_select':
      return array('coupon' => t('Gift Certificate'));

    case 'cart add item':
      break;

    case 'attributes':
      return array('in_stock', 'no_quantity', 'no_discounts');

    case 'transaction':
      break;

    case 'adjust_prices':
      if (!((float)$node->price)) {
        return $node->gc_price;
      }
      break;
    case 'subproduct_types':
      return array('sandwich');
    case 'fields':
      break;

    case 'validate':
      break;

    case 'load':
      break;

    case 'insert':
      break;

    case 'update':
      break;

    case 'delete':
      break;
  }
}

function hook_ecommerceapi() {
}

/**
 * Create a special for products.
 *
 * If you have products which have a special/discount site wide before the
 * checkout process begins. This can be used for things like timed specials,
 * or discounts based upon the users roles.
 *
 * @param $node
 *  This is the node for the product that we need to know the specials
 *  for.
 *
 * @param $specials
 *  Because this hook can be used by multiple modules at the same time it
 *  is possible that if a special has already been placed on an order the
 *  it may affect the current special.
 *
 * @param $txn
 *  If the special is going to be added to a transaction such as a cart 
 *  item, or an invoice then the $txn will be past so that it can be used to 
 *  determine the correct user or other information.
 * 
 * @return
 *  This will return an array which has a list of the specials by the key
 *  which will appear in misc, and the actual value of the discount. This
 *  will usually be a negative value.
 * 
 */
function hook_product_specials($node, $specials, $txn) {
  return array('special' => -5);
}

/**
 * Add email data to be used within ecommerce modules.
 * 
 * This hook allows us to add ecommerce emails. Also, this can be used
 * in conjunction with store_email_form method that renders email
 * customization and preview forms.
 * 
 * @param $messageid
 *  A string that identifies the message. We need to check if this match
 *  the messages we're additing, using an if() or switch() statement.
 * 
 * @return
 *  Return an indexed array describing the message data (body, subject and variables).
 */
function hook_store_email_text($messageid) {
  if ($messageid == 'shipping_notification') {
    return array(
      'subject' => t("Your %site order has shipped (#%txnid)"),
      'body' => t("Hello %first_name,\n\nWe have shipped the following item(s) from Order #%txnid, received  %order_date.\n\nItems(s) Shipped:\n%items\n%shipping_to\nQuestions about your order? Please contact us at %email.\n\nThanks for shopping at %site.  We hope to hear from you again real soon!\n\n%uri"),
      'variables' => array('%order_date', '%txnid', '%billing_name', '%first_name', '%user_data', '%billing_to', '%shipping_to', '%items', '%email', '%site', '%uri', '%uri_brief', '%date')
    );
  }
}

/**
 * Alter one or many ecommerce emails data.
 * 
 * With this hook we can alter ecommerce emails, the parameters goes as
 * reference, so we can alter eighter the message data (body, subject and
 * variables) and the message variables before sending the email.
 * 
 * @param $messageid
 *  A string that identifies the message. We need to check if this match
 *  the messages we're altering, using an if() or switch() statement.
 * 
 * @param $message
 *  An indexed array describing with the message data (body, subject and variables).
 * 
 * @param $variables
 *  An indexed array with the variables available for this message and its
 *  respective values.
 * 
 * @return
 *  This hook has no return value.
 */
function hook_store_email_alter($messageid, &$message, &$variables) {
  if ($messageid == 'shipping_notification') {
    $message->subject = t("Your %site order has shipped");
    $variables['%site'] = 'drupal.org'; 
  }
}

/**
 * Send a ecommerce email using other mechanism than the default.
 * 
 * This hook abstracts the sending email process. This can be used
 * to send a html/mime email or to put the message in a queue.
 * 
 * @param $from
 *  The email address to be used as sender.
 * 
 * @param $to
 *  The recipient where to send this email.
 * 
 * @param $subject
 *  The subject of this email message.
 * 
 * @param $body
 *  The parsed message body string.
 *  
 * @param $headers
 *  Optional email headers to be added to the email.
 * 
 * @return
 *  This should return TRUE indicating that the message was sucessful delivered,
 *  FALSE if an error occurred while delivering the message and none if this
 *  implementation doesn't override the email delivering process.
 */
function hook_store_email_send($from, $to, $subject, $body, $headers) {
  return mail($to, $subject, $body, $headers);
}

/**
 * @} End of "addtogroup hooks".
 */

?>
