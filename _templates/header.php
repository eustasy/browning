<!DocType html>
<html>
	<head>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Browning - A Lightweight Mailgun Script &nbsp;&middot;&nbsp; eustasy Org</title>

		<link rel="icon" href="http://labs.eustasy.org/favicon.ico">
		<link rel="shortcut icon" href="http://labs.eustasy.org/favicon.ico">

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/g/normalize,colors.css">
		<link rel="stylesheet" href="http://labs.eustasy.org/grid.css">
		<link rel="stylesheet" href="assets/css/browning.css">

		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-45667989-11', 'eustasy.org');
			ga('send', 'pageview');
		</script>

		<script>var jQl={q:[],dq:[],gs:[],ready:function(a){'function'==typeof a&&jQl.q.push(a);return jQl},getScript:function(a,c){jQl.gs.push([a,c])},unq:function(){for(var a=0;a<jQl.q.length;a++)jQl.q[a]();jQl.q=[]},ungs:function(){for(var a=0;a<jQl.gs.length;a++)jQuery.getScript(jQl.gs[a][0],jQl.gs[a][1]);jQl.gs=[]},bId:null,boot:function(a){'undefined'==typeof window.jQuery.fn?jQl.bId||(jQl.bId=setInterval(function(){jQl.boot(a)},25)):(jQl.bId&&clearInterval(jQl.bId),jQl.bId=0,jQl.unqjQdep(),jQl.ungs(),jQuery(jQl.unq()), 'function'==typeof a&&a())},booted:function(){return 0===jQl.bId},loadjQ:function(a,c){setTimeout(function(){var b=document.createElement('script');b.src=a;document.getElementsByTagName('head')[0].appendChild(b)},1);jQl.boot(c)},loadjQdep:function(a){jQl.loadxhr(a,jQl.qdep)},qdep:function(a){a&&('undefined'!==typeof window.jQuery.fn&&!jQl.dq.length?jQl.rs(a):jQl.dq.push(a))},unqjQdep:function(){if('undefined'==typeof window.jQuery.fn)setTimeout(jQl.unqjQdep,50);else{for(var a=0;a<jQl.dq.length;a++)jQl.rs(jQl.dq[a]); jQl.dq=[]}},rs:function(a){var c=document.createElement('script');document.getElementsByTagName('head')[0].appendChild(c);c.text=a},loadxhr:function(a,c){var b;b=jQl.getxo();b.onreadystatechange=function(){4!=b.readyState||200!=b.status||c(b.responseText,a)};try{b.open('GET',a,!0),b.send('')}catch(d){}},getxo:function(){var a=!1;try{a=new XMLHttpRequest}catch(c){for(var b=['MSXML2.XMLHTTP.5.0','MSXML2.XMLHTTP.4.0','MSXML2.XMLHTTP.3.0','MSXML2.XMLHTTP','Microsoft.XMLHTTP'],d=0;d<b.length;++d){try{a= new ActiveXObject(b[d])}catch(e){continue}break}}finally{return a}}};if('undefined'==typeof window.jQuery){var $=jQl.ready,jQuery=$;$.getScript=jQl.getScript};</script>
		<script>jQl.loadjQ('https://cdn.jsdelivr.net/g/prefixfree,jquery,jquery.equalize,jquery.downboy');</script>
		<script>
			$(function(){
				equalize('.grid', '.equalize');
				downBoy();
				window.onresize = function() {
					equalize('.grid', '.equalize');
					downBoy();
				}
			})
		</script>

	</head>

	<body class="text-center background-belize-hole color-white">

		<a href="https://github.com/eustasy/browning-a-mailgun-script" class="github-corner" aria-label="View source on Github">
			<svg width="80" height="80" viewBox="0 0 250 250" class="color-belize-hole" aria-hidden="true">
				<path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path>
				<path class="octo-arm" fill="currentColor" style="transform-origin: 130px 106px;" d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2"></path>
				<path class="octo-body" fill="currentColor" d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z"></path>
			</svg>
		</a>

		<header>
			<h1>Browning</h1>
			<p class="sub-title">A Lightweight Mailgun Script</p>
		</header>

		<section class="whole grid">
