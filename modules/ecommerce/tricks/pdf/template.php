<?php
// $Id: template.php,v 1.1 2006/05/13 06:42:42 gordon Exp $

function phptemplate_store_invoice($txn, $print_mode = TRUE, $trial = FALSE) {
  if (!$print_mode && !preg_match('/\/pdf$/i', $_GET['q'])) {
    $path = url($_GET['q'] .'/pdf');
    $output.= <<<EOF
To view this invoice please click <a href="{$path}">here</a>
EOF;
    return $output;
  }
  else {
    require_once(path_to_theme() .'/pdf/class.ezpdf.php');

    $pdf =& new Cezpdf();
    phptemplate_build_pdf_invoice($pdf, $txn, $trial);
    $pdf->ezStream(array('Content-Disposition' => 'invoice.pdf'));
    exit();
  }
}

function phptemplate_build_pdf_invoice(&$pdf, $txn, $trail) {
  $pdf->selectFont(phptemplate_pdf_fontdir() .'/Helvetica.afm');

  $pdf->addText(96, 756, 14, variable_get('site_name', 'drupal') .' - Invoice');
  foreach (array('billing', 'shipping') as $type) {
    $address = explode("\n", store_format_address($txn, $type));
    $ypos = 672;
    $xpos = ($type == 'billing' ? 96 : 336);
    foreach ($address as $value) {
      $pdf->addText($xpos, $ypos, 12, $value);
      $ypos-= $pdf->getFontHeight(12);
    }
  }

  $pdf->setLineStyle(1);
  $pdf->rectangle(96, 288, 432, 312);

  $pdf->line(336, 600, 336, 288);
  $pdf->line(384, 600, 384, 288);

  $y2 = 288 - $pdf->getFontHeight(11);
  $pdf->line(432, 600, 432, $y2);
  $pdf->line(432, $y2, 528, $y2);
  $pdf->line(528, $y2, 528, 288);

  //$pdf->setLineStyle(2);
  $ypos = 600-$pdf->getFontHeight(11);
  $pdf->line(96, $ypos, 528, $ypos);

  $text = '<b>DESCRIPTION</b>';
  $tw = $pdf->getTextWidth(11, $text);

  $ypos = (600 - $pdf->getFontHeight(11)) + 2;
  $xpos = ((336 - 96)/2) - ($tw/2) + 96;
  $pdf->addText($xpos, $ypos, 10, $text);

  $text = '<b>QTY</b>';
  $tw = $pdf->getTextWidth(11, $text);
  $xpos = ((384 - 336)/2) - ($tw/2) + 336;
  $pdf->addText($xpos, $ypos, 10, $text);

  $text = '<b>PRICE</b>';
  $tw = $pdf->getTextWidth(11, $text);
  $xpos = ((432 - 384)/2) - ($tw/2) + 384;
  $pdf->addText($xpos, $ypos, 10, $text);

  $text = '<b>AMOUNT</b>';
  $tw = $pdf->getTextWidth(11, $text);
  $xpos = ((528 - 432)/2) - ($tw/2) + 432;
  $pdf->addText($xpos, $ypos, 10, $text);

  $ypos = 288 - $pdf->getFontHeight(10);
  phptemplate_pdf_print_amount($pdf, 528, $ypos, '<b>'.payment_format($txn->gross) .'</b>');

  $ypos = 600-$pdf->getFontHeight(11);
  foreach ($txn->items as $item) {
    $ypos-= $pdf->getFontHeight(11);
    $text = $pdf->addTextWrap(97, $ypos, 336 - 97, 10, check_plain($item->title));
    $total = $price = store_adjust_misc($txn, $item);
    if (product_has_quantity($item)) {
      phptemplate_pdf_print_amount($pdf, 384, $ypos, $item->qty);
      $total = $price * $item->qty;
    }

    phptemplate_pdf_print_amount($pdf, 432, $ypos, payment_format($price));
    phptemplate_pdf_print_amount($pdf, 528, $ypos, payment_format($total));

    while ($text) {
      $ypos-= $pdf->getFontHeight(10);
      $text = $pdf->addTextWrap(97, $ypos, 336 - 96, 10, $text);
    }
  }

  if ($txn->misc) {
    foreach ($txn->misc as $misc) {
      if (!$misc->seen) {
        $ypos-= $pdf->getFontHeight(11);
        $text = $pdf->addTextWrap(97, $ypos, 336 - 97, 10, check_plain($misc->description));

        phptemplate_pdf_print_amount($pdf, 528, $ypos, payment_format($misc->price));

        while ($text) {
          $ypos-= $pdf->getFontHeight(10);
          $text = $pdf->addTextWrap(97, $ypos, 336 - 96, 10, $text);
        }
      }
    }
  }
}

function phptemplate_pdf_fontdir() {
  return path_to_theme() .'/pdf/fonts';
}

function phptemplate_pdf_print_amount(&$pdf, $right, $ypos, $text) {
  $tw = $pdf->getTextWidth(10, $text);
  $xpos = $right - $tw - 2;
  $pdf->addText($xpos, $ypos, 10, $text);
}
