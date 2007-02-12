
var preloadSubproductImages = true;

/**
 * Check for appropriate Javascript support, and issue warning if absent
 */
if (isJsEnabled()) {
  addLoadEvent(subproductsAutoAttach);
}
else {
  alert('Javascript support needed by this page is not available.  We recommend using Firefox.');
}

/**
 * Attaches behaviours to all required fields
 */
function subproductsAutoAttach() {
  var selects = document.getElementsByTagName('select');
  for (i = 0; select = selects[i]; i++) {
    if (select && hasClass(select, 'product-select')) {
      select.target = document.getElementById(select.getAttribute('data'));
      select.removeAttribute('data');
      select.onchange = function () {
        this.target.setOptions('attribute');
        this.target.onchange();
      }
    }
    else if (select && hasClass(select, 'attribute-select')) {
      var data = select.getAttribute('data');
      select.target = document.getElementById(data) ? document.getElementById(data) : null;
      select.ptype = select.id.substring(select.id.indexOf('edit-') + 5, select.id.lastIndexOf('-variations'));
      if (select.target) {
        select.callTarget = function() {
          this.checkSelected();
          this.target.setOptions('attribute');
          this.target.onchange();
        }
        select.onchange = select.callTarget;
      }
      else {
        select.setProductInfo = function() {
          var productSelect = document.getElementById('edit-' + this.ptype + '-base_parent');
          var productId = productSelect.options[productSelect.selectedIndex].value;
          // Determine product type.
          var ptype = this.productData[productId]['ptype'];
          // This array will be have vid keys, aid values.
          var currentVariations = new Array();
          // Read in currently set options
          for (i in this.variations) {
            var select = document.getElementById('edit-' + this.ptype + '-variations-' + this.variations[i]);
            currentVariations[this.variations[i]] = select.options[select.selectedIndex].value;
          }

          // Cycle through all child products.
          for (i in this.productData[productId]['children']) {
            var use = true;
            // We need to see if any of the above-set variations doesn't match this child product's variation attribute.
            for (vid in currentVariations) {
              if (this.productData[productId]['children'][i]['variations'][vid] != currentVariations[vid]) {
                use = false;
                break;
              }
            }
            if (use) {
              nid = i;
              break;
            }
          }
          if (this.productData[productId]['children'][nid]['price']) {
            var newPrice = document.createTextNode('$' + this.productData[productId]['children'][nid]['price']);
            var priceNode = document.getElementById('priceNode');
            priceNode.replaceChild(newPrice, priceNode.firstChild);
          }
        }
        select.onchange = function(){
          this.checkSelected();
          this.setProductInfo();
        };
      }
      select.checkSelected = function() {
        if (this.options[this.selectedIndex].disabled) {
          if (this.lastSelected && !this.options[this.lastSelected].disabled) {
            this.selectedIndex = this.lastSelected;
          }
          else {
            for (i = 0; option = this.options[i]; i++) {
              if (!option.disabled) {
                this.selectedIndex = i;
                break;
              }
            }
          }
        }
        this.lastSelected = this.selectedIndex;
      }
      select.removeAttribute('data');
      // Point to globally set variables containing data on subproducts and variations
      select.productData = productData[select.ptype]['products'];
      select.variations = productData[select.ptype]['variations'];
      // Parse the variation this select is for from its id
      select.variation = select.id.substring(select.id.indexOf(select.ptype + '-variations-') + select.ptype.length + 12, select.id.length);
      select.setOptions = function(type) {
        switch (type) {
          case 'product':
            break;
          case 'attribute':
            selectedValue = this.options[this.selectedIndex].value;
            var options = new Array();
            for (i = 0; option = this.options[i]; i++) {
              options[options.length] = new attributeOption(option.value, option.text);
            }
            var productSelect = document.getElementById('edit-' + this.ptype + '-base_parent');
            var productId = productSelect.options[productSelect.selectedIndex].value;
            // This array will be have vid keys, aid values.
            var currentVariations = new Array();
            // Read in currently set options
            for (i in this.variations) {
              var select = document.getElementById('edit-' + this.ptype + '-variations-' + this.variations[i]);
              // When we reach the current select's variation, stop, since we only want to test for variations
              // thus far in the array.
              if (this.variations[i] == this.variation) {
                break;
              }
              currentVariations[this.variations[i]] = select.options[select.selectedIndex].value;
            }
            // Cycle through each of the select's options.  
            for (i = 0; option = options[i]; i++) {
              disabled = true;
              // Cycle through the product children.  
              for (j in this.productData[productId]['children']) {
                var count = 0;
                var matches = 0;
                // First determine if the option is for this attribute.
                if (this.productData[productId]['children'][j]['variations'][this.variation] != option.value) {
                  continue;
                }
                // If it is, we need to see if any of the above-set variations doesn't match this child product's variation attribute.
                for (vid in currentVariations) {
                  count++;
                  if (this.productData[productId]['children'][j]['variations'][vid] != currentVariations[vid]) {
                    break;
                  }
                  matches++;
                }
                // Did the subproduct match each of the selected variation attributes?
                // If so, we have a hit so we know this option is valid.
                if (matches == count) {
                  disabled = false;
                  break;
                }
              }
              option.disabled = disabled ? true : false;
            }
            setOptions(this, options, selectedValue);
            break;
        }
      }
    }
  }

  initiateValues();
}

function attributeOption(value, text) {
  this.value = value;
  this.text = text;
  this.enabled = false;
}

/**
 * Remove existing options from a select element and set new ones
 */
function setOptions(select, options, selectedValue) {
  // Remove any existing options.
  while(select.hasChildNodes()) {
    select.removeChild(select.firstChild);
  }
  for (i in options) {
    var opt = document.createElement('option');
    opt.value = options[i].value;
    opt.text = options[i].text;
    opt.disabled = options[i].disabled;
    if ((options[i].value == selectedValue) && !opt.disabled) {
      opt.selected = true;
    }
    // This is the standards-compliant way, but doesn't work in IE
    try {
      select.add(opt, select.options[select.options.length]);
    }
    // IE proprietary method
    catch(err) {
      if (opt.disabled) {
        opt.style.color = 'gray';
      }
      select.add(opt, select.options.length);
    }
  }
}

/**
 * Set up initial values for selected products
 */
function initiateValues() {
  try {
    for (ptype in productData) {
      var base = document.getElementById('edit-' + ptype + '-base_parent');
      if (base) {
        base.onchange();
      }
    }
  }
  catch(err) {
    setTimeout('initiateValues()', 500);
  }
}