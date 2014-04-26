		<div class="UPCPMenu">
				 <h2 class="nav-tab-wrapper">
				 		 <a id="Dashboard_Menu" class="MenuTab nav-tab <?php if ($Display_Page == '' or $Display_Page == 'Dashboard') {echo 'nav-tab-active';}?>" onclick="ShowTab('Dashboard');">Dashboard</a>
				 		 <a id="Products_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Products') {echo 'nav-tab-active';}?>" onclick="ShowTab('Products');">Products</a>
				 		 <a id="Catalogues_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Catalogues') {echo 'nav-tab-active';}?>" onclick="ShowTab('Catalogues');">Catalogues</a>
						 <a id="Categories_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Categories') {echo 'nav-tab-active';}?>" onclick="ShowTab('Categories');">Categories</a>
				 		 <a id="SubCategories_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'SubCategories') {echo 'nav-tab-active';}?>" onclick="ShowTab('SubCategories');">Sub-Categories</a>
				 		 <a id="Tags_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Tags') {echo 'nav-tab-active';}?>" onclick="ShowTab('Tags');">Tags</a>
						 <a id="CustomFields_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'CustomFields') {echo 'nav-tab-active';}?>" onclick="ShowTab('CustomFields');">Custom Fields</a>
						 <a id="Options_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Options') {echo 'nav-tab-active';}?>" onclick="ShowTab('Options');">Options</a>
				 </h2>
		</div>
		
		<div class="clear"></div>
		
		<!-- Add the individual pages to the admin area, and create the active tab based on the selected page -->
		<div class="OptionTab <?php if ($Display_Page == "" or $Display_Page == 'Dashboard') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Dashboard">
				<?php include ABSPATH . 'wp-content/plugins/ultimate-product-catalogue/html/DashboardPage.php';?>
		</div>
		
		<div class="OptionTab <?php if ($Display_Page == 'Products' or $Display_Page == 'Product') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Products">
				<?php include ABSPATH . 'wp-content/plugins/ultimate-product-catalogue/html/ProductsPage.php';?>
		</div>
		
		<div class="OptionTab <?php if ($Display_Page == 'Categories' or $Display_Page == 'Category') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Categories">
				<?php include ABSPATH . 'wp-content/plugins/ultimate-product-catalogue/html/CategoriesPage.php';?>
		</div>
		
		<div class="OptionTab <?php if ($Display_Page == 'Catalogues' or $Display_Page == 'Catalogue') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Catalogues">
				<?php include ABSPATH . 'wp-content/plugins/ultimate-product-catalogue/html/CataloguesPage.php';?>
		</div>
		
		<div class="OptionTab <?php if ($Display_Page == 'SubCategories' or $Display_Page == 'SubCategory') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="SubCategories">
				<?php include ABSPATH . 'wp-content/plugins/ultimate-product-catalogue/html/SubCategoriesPage.php';?>
		</div>
		
		<div class="OptionTab <?php if ($Display_Page == 'Tags' or $Display_Page == 'Tag') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Tags">
				<?php include ABSPATH . 'wp-content/plugins/ultimate-product-catalogue/html/TagsPage.php';?>
		</div>	
		<div class="OptionTab <?php if ($Display_Page == 'CustomFields' or $Display_Page == 'CustomField') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="CustomFields">
				<?php include ABSPATH . 'wp-content/plugins/ultimate-product-catalogue/html/CustomFieldsPage.php';?>
		</div>
		<div class="OptionTab <?php if ($Display_Page == 'Options' or $Display_Page == 'Option') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Options">
				<?php include ABSPATH . 'wp-content/plugins/ultimate-product-catalogue/html/OptionsPage.php';?>
		</div>	