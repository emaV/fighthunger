// $Id: toc.js,v 1.1 2006/04/03 21:53:15 nedjo Exp $

if (isJsEnabled()) {
  addLoadEvent(function() {
    var url = location.href;
    if (url.indexOf('node/') == -1) {
      return;
    }
    var nodeDiv;
    // Select all h2 elements
    divs = document.getElementsByTagName('div');
    for (var i = 0; div = divs[i]; ++i) {
      if (hasClass(div, 'node')) {
        nodeDiv = div;
        break;
      }
    }
    if (nodeDiv) {
      h2s = nodeDiv.getElementsByTagName('h2');
      if (h2s.length) {
        var h2;
        var toc = document.createElement('fieldset');
        legend = document.createElement('legend');
        var a = document.createElement('a');
        a.href = '#';
        a.onclick = function() {
          toggleClass(this.parentNode.parentNode, 'collapsed');
          if (!hasClass(this.parentNode.parentNode, 'collapsed')) {
            collapseScrollIntoView(this.parentNode.parentNode);
          }
          this.blur();
          return false;
        };
        a.appendChild(document.createTextNode('contents'));
        legend.appendChild(a);
        toc.appendChild(legend);
        addClass(toc, 'collapsible');
        var ol = document.createElement('ol');
        for (var i = 0; h2 = h2s[i]; ++i) {
          if (h2) {
            var li = document.createElement('li');
            var link = document.createElement('a');
            link.setAttribute('href', '#toc-' + i);
            var span = document.createElement('span');
            span.appendChild(document.createTextNode(h2.firstChild.nodeValue));
            link.appendChild(span);
            li.appendChild(link);
            ol.appendChild(li);
            var anchor = document.createElement('a');
            anchor.setAttribute('name', 'toc-' + i);
            h2.appendChild(anchor);
          }
        }
        toc.appendChild(ol);
        nodeDiv.insertBefore(toc, nodeDiv.firstChild);
        collapseEnsureErrorsVisible(toc);
      }
    }
  });
}


