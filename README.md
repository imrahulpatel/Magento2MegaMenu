# Jnext Magento2 MegaMenu
<h3>Features</h3>
<ul>
	<li>Compatible with both Magento2 series 2.0 (latest version 2.0.11) and 2.1 (latest version 2.1.3)</li>
	<li>The plugin is one of the best ways to improve usability and navigation of your online store.</li>
	<li>Without high technical knowledge, Easy Mega Menu give you the ability to create an organized menu</li>
	<li>You have a lot of products, give a best user experience to customer</li>
	<li>Helps you attract more customers, by the way, you help customers save time, view shop easily and purchase quickly.</li>
	<li>Admin has the power to redirect visitors to where they want with nice gui</li>
	<li>Auto converts to default menu in tablet and smaller devices</li>
	<li>Attracts visitors' attention at first sight with great design</li>
	<li>Easy to use even without coding skills</li>
</ul>
<h3>Usage</h3>
<p>Easy navigation menu at left side having 2 submenus "Manage Megamenu" and "Configurations"</p>
<ol>
	<li>Manage Megamenu: Redirects to "Manage Categories" having "Megamenu" tab added
		<ul>
			<li>Can configure selected categories as mega-menu and rest as default-menu as per admin convenience</li>
			<li>Flexibility to select from different sections top, left, right and bottom</li>
			<li>Flexibility to display eighter static/dynamic content from already created static block or can add manual html using magento standard WYSIWYG Editor</li>
			<li>Can select width of left, right sections and whole-menu from all possible volume in percentage</li>
			<li>Can upload extra category image named category thumbnail image</li>
			<li>Left section has options to display like subcategories menu, subcategories expanded (all children categories visible) and subcategories with category thumbnail</li>
			<li>Selection of category label configured earlier like SALE, NEW, HOT etc.</li>
		</ul>
	</li>
	<li>Configurations: Redirects to sytem configuration to configure the plugin like:
		<ul>
			<li>Enable/Disable the plugin</li>
			<li>Add category label with nice gui of color picker for category label</li>
		</ul>
	</li>
</ol>
<h3>Install</h3>
<p>This package is registered on <a href="https://packagist.org/packages/jnext/megamenu">Packagist</a> for easy installation. In your Magento installation root run:</p>
<p><code>composer require jnext/megamenu</code></p>
<p>This will install the latest version in your Magento installation, when completed run:</p>
<pre><code>php bin/magento module:enable Jnext_Megamenu

php bin/magento setup:upgrade

php bin/magento cache:clean
</code></pre>
<p>This will enable the extension and installs Megamenu tab with fields under Manage Categories</p>
<h3>Note:</h3>
<p>If you face error like</p>
<p><tt>Could not find package jnext/megamenu at any version for your minimum-stability (alpha). Check the package spelling or your minimum-stability</tt></p>
<p>To resolve, edit your composer.json file located at root of your project by setting "minimum-stability": from "alpha" to "dev"</p>
