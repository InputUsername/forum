<!DOCTYPE html>
<html lang="en">
<head>
	{* General meta tags *}
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	{* /General meta tags *}

	{* Changable meta tags *}
	{block name="meta"}
	<meta name="theme-color" content="#808080">
	<meta name="description" content="">
	{/block}
	{* /Changable meta tags *}

	<title>{$pageTitle} - {$forumName}</title>

	{* Head includes, overridable *}
	{block name="head"}
	<link rel="shortcut icon" href="{$root}{$iconSmall}">

	<link rel="stylesheet" href="{$root}assets/css/responsive.css" type="text/css">
	<link rel="stylesheet" href="{$root}assets/css/main.css" type="text/css">
	{/block}
	{* /Head includes *}
</head>
<body>
	<div id="wrapper">
		{include file="components/header.tpl"}
		<main class="box" id="content">
			{block name="content"}{/block}
		</main>
		{include file="components/footer.tpl"}
	</div>

	{* JavaScript, overridable *}
	{block name="js"}
	<script src="{$root}assets/js/user_controls.js" type="text/javascript"></script>
	{/block}
	{* /JavaScript *}
</body>
</html>
