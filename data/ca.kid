<?xml version='1.0' encoding='utf-8'?>
<html xmlns:py='http://purl.org/kid/ns#'
      xmlns='http://www.w3.org/1999/xhtml' lang="ca" xml:lang='ca'>

<head>
  <title>Coordinació de la traducció Debian al català</title>
  <link rel="stylesheet" type="text/css" href="/style.css"/>
  <script src='/sorttable.js' type='text/javascript'></script>
</head>
<body>

<div id="header">
  <h1><a href="./">Coordinació de la traducció de Debian al català</a></h1>
</div>

<div id="main">

<p py:if="view.has_key('default')">L'objectiu d'aquesta pàgina
és coordinar la traducció al català de material relacionat
amb <a href="http://www.debian.org/">Debian</a>. A la pàgina
d'<a href="/intro">introducció</a> podeu aprendre més sobre
el funcionament general de l'equip i sobre què són les «<a
href="/pseudo-urls">pseudo-urls</a>». També podeu trobar informació
addicional al <a href="http://wiki.debian.org/L10n/Catalan">wiki</a>.</p>

<h2 py:if="view.has_key('translator')" class="translator">Traduccions
de ${view['translator']}</h2>

<h2 py:if="view.has_key('history')" class="history">Historial de
${view['history']}</h2>

<div py:if="not view.has_key('history')" id="categories">
  <p><strong>Categories</strong></p>
  <ul py:for="cat in categories.keys()">
    <li><a href="#${cat}">${cat}</a></li>
  </ul>
</div>

<div class="clear"/>

<div py:for="cat in categories.keys()"><hr/>
<h2 id="${cat}">${cat}</h2>
<table class="sortable ${cat}">
 <thead>
  <tr>
   <th>Tipus</th>
   <th>Nom</th>
   <th>Traductor</th>
   <th>Data</th>
  </tr>
 </thead>
 <tbody>
  <tr py:for="elem in categories[cat]" class="${elem['type']}">
   <td py:if="elem['type'] != 'BTS'">${elem['type']}</td>
   <td py:if="elem['type'] == 'BTS'">
     <a href="http://bugs.debian.org/${elem['bugnum']}">${elem['type']}</a>
   </td>
   <td>
     <a href="?history=${elem['name']}">${elem['name']}</a> 
     <a py:if="elem['file'] and elem['file'] != '0'"
        href="/ca/${elem['id']}.${elem['file']}">[${elem['file']}]</a>
   </td>
   <td><a href="?translator=${elem['author']}">${elem['author']}</a></td>
   <td>${elem['date']}</td>
  </tr>
 </tbody>
</table>
</div>

</div>

<div id="footer">
 <p>
  <a href="http://git.hadrons.org/?p=debian/l10n-bot.git">Browse source code</a> or
  <a href="git://git.hadrons.org/git/debian/l10n-bot.git">clone source code</a>.
 </p>
</div>

</body>
</html>
