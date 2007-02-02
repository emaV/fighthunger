BiDi Theme README
------------------

This is a bidi (bi-directional) theme for Drupal, based on box_grey,
it uses the PHPTemplate engine.
The theme can be used for right-to-left locale sites (like Arabic),
or multi-lingual sites with one or more enabled RTL (Right to Left)
locales.

Installation
-------------
You can choose to *either* use this theme as bi-directional, in this
case theme will flip directions based on current locale (works
wonderfully with i18n.module language block),
*or* RTL-only, in this case you won't need to patch core files, but
the theme's direction will always be RTL no matter what the current
locale is.

 = BiDi =
1) Copy BiDi's folder to your themes folder.
2) Copy files under bidi/misc to Drupal's misc folder, replacing the
   original drupal.css.
3) Patch includes/common.inc and includes/theme.inc by copying the
   provided patches under bidi/patches folder to Drupal's includes
   folder and running the following command in that folder:
     patch -p0 < filename.patch
4) Enable theme from the admin/themes page.

 = RTL-Only =
1) Copy BiDi's folder to your themes folder.
2) Copy files under bidi/misc to Drupal's misc folder, replacing the
   original drupal.css.
3) Enable theme from the admin/themes page.

 = Notes =
* Making backup copies of misc/drupal.css, includes/common.inc, and
includes/theme.inc is highly recommended!
* Tested with Drupal 4.6 and 4.7, patches are provided for both.

Known Issues
-------------
* Node comments are always padded to left, to fix this comment and forum
  modules need to be patched.
* Print-friendly book pages are aligned to left, to fix this book module
  needs to be patched.

TODO
-----
* Create patches for mentioned issues.

Author
-------
Ayman Hourieh
E-mail: drupal@aymanh.com
WWW: http://aymanh.com/

Based on box_grey
------------------
adrinux
mailto: adrinux@gmail.com
IM: perlucida
