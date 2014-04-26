<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Product_Catalog($atts) {
		// Include the required global variables, and create a few new ones
		global $wpdb, $categories_table_name, $subcategories_table_name, $tags_table_name, $tagged_items_table_name, $catalogues_table_name, $catalogue_items_table_name, $items_table_name;
		global $ReturnString, $ProdCats, $ProdSubCats, $ProdTags, $Catalogue_ID, $Catalogue_Layout_Format, $Catalogue_Sidebar, $Full_Version;

		$ReturnString = "";
		$Tag_Logic = get_option("UPCP_Tag_Logic");
		$Filter = get_option("UPCP_Filter_Type");
		$Color = get_option("UPCP_Color_Scheme");
		$Links = get_option("UPCP_Product_Links");
		$Pretty_Links = get_option("UPCP_Pretty_Links");
		$Mobile_Style = get_option("UPCP_Mobile_SS");
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
														 		"id" => "1",
																"excluded_layouts" => "None",
																"starting_layout" => "",
																"products_per_page" => 5000,
																"sidebar" => "Yes",
																"only_inner" => "No",
																"category" => "",
																"subcategory" => "",
																"tags" => "",
																"prod_name" => ""),
																$atts
														)
												);
		
		if (get_query_var('single_product') != "" or $_GET['SingleProduct'] != "") {$ReturnString = SingleProductPage(); return $ReturnString;}
						
		$Catalogue_ID = $id;
		$Catalogue_Sidebar = $sidebar;
		$Starting_Layout = ucfirst($starting_layout);
		if ($excluded_layouts != "None") {$Excluded_Layouts = explode(",", $excluded_layouts);}
		else {$Excluded_Layouts = array();}
		if ($tags == "") {$tags = array();}
		else {$tags = explode(",", $tags);}		
		
		$ReturnString .= "<div class='Hide-Item' id='upcp-shortcode-atts'>";
		$ReturnString .= "<div class='shortcode-attr' id='upcp-catalogue-id'>" . $id . "</div>";
		$ReturnString .= "<div class='shortcode-attr' id='upcp-catalogue-sidebar'>" . $sidebar . "</div>";
		$ReturnString .= "<div class='shortcode-attr' id='upcp-starting-layout'>" . $starting_layout . "</div>";
		$ReturnString .= "<div class='shortcode-attr' id='upcp-exclude-layouts'>" . $excluded_layouts . "</div>";
		$ReturnString .= "</div>";
		
		if (sizeOf($Excluded_Layouts)>0) {for ($i=0; $i<sizeOf($Excluded_Layouts); $i++) {$ExcludedLayouts[$i] = ucfirst(trim($Excluded_Layouts[$i]));}}
		else {$ExcludedLayouts = array();}
		
		if ($Starting_Layout == "") {
			  if (!in_array("Thumbnail", $Excluded_Layouts)) {$Starting_Layout = "Thumbnail";}
				elseif (!in_array("List", $Excluded_Layouts)) {$Starting_Layout = "List";}
				else {$Starting_Layout = "Detail";}
		}
		
		// Select the catalogue information from the database 
		$Catalogue = $wpdb->get_row("SELECT * FROM $catalogues_table_name WHERE Catalogue_ID=" . $id);
		$CatalogueItems = $wpdb->get_results("SELECT * FROM $catalogue_items_table_name WHERE Catalogue_ID=" . $id . " ORDER BY Position");
				
		// Make sure that the layout is set
		if ($layout_format != "Thumbnail" and $layout_format != "List") {
			  if ($Catalogue->Catalogue_Layout_Format != "") {$format = $Catalogue->Catalogue_Layout_Format;}
				else {$format = "Thumbnail";}
		}
		else {$format = $layout_format;}
		
		// Add any additional CSS in-line
		if ($Catalogue->Catalogue_Custom_CSS != "") {
				$HeaderBar .= "<style type='text/css'>";
				$HeaderBar .= $Catalogue->Catalogue_Custom_CSS;
				$HeaderBar .= "</style>";
		}
		
		// Arrays to store what categories, sub-categories and tags are applied to the product in the catalogue
		$ProdCats = array();
		$ProdSubCats = array();
		$ProdTags = array();
		
		$ProdThumbString .=  "<div id='prod-cat-" . $id . "' class='prod-cat thumb-display ";
		if ($Starting_Layout != "Thumbnail") {$ProdThumbString .= "hidden-field";}
		$ProdThumbString .= "'>\n";
		
		$ProdListString .=  "<div id='prod-cat-" . $id . "' class='prod-cat list-display ";
		if ($Starting_Layout != "List") {$ProdListString .= "hidden-field";}
		$ProdListString .= "'>\n";
		
		$ProdDetailString .=  "<div id='prod-cat-" . $id . "' class='prod-cat detail-display ";
		if ($Starting_Layout != "Detail") {$ProdDetailString .= "hidden-field";} 
		$ProdDetailString .= "'>\n";
		
		$Product_Count = 0;
		foreach ($CatalogueItems as $CatalogueItem) {
				
				// If the item is a product, then simply call the AddProduct function to add it to the code
				if ($CatalogueItem->Item_ID != "" and $CatalogueItem->Item_ID != 0) {
					  $Product = $wpdb->get_row("SELECT * FROM $items_table_name WHERE Item_ID=" . $CatalogueItem->Item_ID);
						$ProdTagObj = $wpdb->get_results("SELECT Tag_ID FROM $tagged_items_table_name WHERE Item_ID=" . $CatalogueItem->Item_ID);
						$ProdTag = ObjectToArray($ProdTagObj);
						if ($category == "" or $category == $Product->Category_ID) {
						if ($subcategory == "" or $subcategory == $Product->SubCategory_ID) {
						if (sizeOf($tags) == 0 or count(array_intersect($tags, $ProdTag)) == count($tags)) {
						if ($prod_name == "" or strpos($Product->Item_Name, $prod_name) !== false) {
						if ($Product->Item_Display_Status != "Hide") {
						$HeaderBar .= "<a id='hidden_FB_link-" . $CatalogueItem->Item_ID . "' class='fancybox' href='#prod-cat-addt-details-" . $CatalogueItem->Item_ID . "'></a>";
						if (!in_array("Thumbnail", $ExcludedLayouts)) {$ProdThumbString .= AddProduct("Thumbnail", $CatalogueItem->Item_ID, $Product, $ProdTagObj);}
						if (!in_array("List", $ExcludedLayouts)) {$ProdListString .= AddProduct("List", $CatalogueItem->Item_ID, $Product, $ProdTagObj);}
						if (!in_array("Detail", $ExcludedLayouts)) {$ProdDetailString .= AddProduct("Detail", $CatalogueItem->Item_ID, $Product, $ProdTagObj);}
						$Product_Count++;
						}}}}}
				}
				
				// If the item is a category, then add the appropriate extra HTML and call the AddProduct function
				// for each individual product in the category
				if ($CatalogueItem->Category_ID != "" and $CatalogueItem->Category_ID != 0) {
				if ($category == "" or $category == $CatalogueItem->Category_ID) {						
						$CatProdCount = 0;
						$Category = $wpdb->get_row("SELECT Category_Name FROM $categories_table_name WHERE Category_ID=" . $CatalogueItem->Category_ID);
						
						$ProdThumbString .= "<div id='prod-cat-category-" . $CatalogueItem->Category_ID . "' class='prod-cat-category upcp-thumb-category'>\n";
						$ProdListString .= "<div id='prod-cat-category-" . $CatalogueItem->Category_ID . "' class='prod-cat-category upcp-list-category'>\n";
						$ProdDetailString .= "<div id='prod-cat-category-" . $CatalogueItem->Category_ID . "' class='prod-cat-category upcp-detail-category'>\n";
						
						$ProdThumbString .= "%Category_Label%";
						$ProdListString .= "%Category_Label%";
						$ProdDetailString .= "%Category_Label%";
						
						$CatThumbHead = "<div id='prod-cat-category-label-" . $CatalogueItem->Category_ID . "' class='prod-cat-category-label upcp-thumb-category-label'>" . $Category->Category_Name ."</div>\n";
						$CatListHead = "<div id='prod-cat-category-label-" . $CatalogueItem->Category_ID . "' class='prod-cat-category-label upcp-list-category-label'>" . $Category->Category_Name ."</div>\n";
						$CatDetailHead = "<div id='prod-cat-category-label-" . $CatalogueItem->Category_ID . "' class='prod-cat-category-label upcp-detail-category-label'>" . $Category->Category_Name ."</div>\n";
						
						$Products = $wpdb->get_results("SELECT * FROM $items_table_name WHERE Category_ID=" . $CatalogueItem->Category_ID);
						
						foreach ($Products as $Product) {
								$ProdTagObj = $wpdb->get_results("SELECT Tag_ID FROM $tagged_items_table_name WHERE Item_ID=" . $Product->Item_ID);
								$ProdTag = ObjectToArray($ProdTagObj);
								if ($subcategory == "" or $subcategory == $Product->SubCategory_ID) {
								if (sizeOf($tags) == 0 or count(array_intersect($tags, $ProdTag)) == count($tags)) {
								if ($prod_name == "" or strpos($Product->Item_Name, $prod_name) !== false) {
								if ($Product->Item_Display_Status != "Hide") {
								$HeaderBar .= "<a id='hidden_FB_link-" . $Product->Item_ID . "' class='fancybox' href='#prod-cat-addt-details-" . $Product->Item_ID . "'></a>";
								if (!in_array("Thumbnail", $ExcludedLayouts)) {$ProdThumbString .= AddProduct("Thumbnail", $Product->Item_ID, $Product, $ProdTagObj);}
								if (!in_array("List", $ExcludedLayouts)) {$ProdListString .= AddProduct("List", $Product->Item_ID, $Product, $ProdTagObj);}
								if (!in_array("Detail", $ExcludedLayouts)) {$ProdDetailString .= AddProduct("Detail", $Product->Item_ID, $Product, $ProdTagObj);}
								$Product_Count++;
								$CatProdCount++;
								}}}}
						}
						
						if ($CatProdCount > 0) {
							 	$ProdThumbString =  str_replace("%Category_Label%", $CatThumbHead, $ProdThumbString);
								$ProdListString = str_replace("%Category_Label%", $CatListHead, $ProdListString);
								$ProdDetailString = str_replace("%Category_Label%", $CatDetailHead, $ProdDetailString);
						}
						else {
								$ProdThumbString = str_replace("%Category_Label%", "", $ProdThumbString);
								$ProdListString = str_replace("%Category_Label%", "", $ProdListString);
								$ProdDetailString = str_replace("%Category_Label%", "", $ProdDetailString);
						}
						
						$ProdThumbString .= "</div>";
						$ProdListString .= "</div>";
						$ProdDetailString .= "</div>";
				}}
				
				// If the item is a sub-category, then add the appropriate extra HTML and call the AddProduct function
				// for each individual product in the sub-category
				if ($CatalogueItem->SubCategory_ID != "" and $CatalogueItem->SubCategory_ID != 0) {
				if ($subcategory == "" or $subcategory == $CatalogueItem->SubCategory_ID) {
						$Products = $wpdb->get_results("SELECT * FROM $items_table_name WHERE SubCategory_ID=" . $CatalogueItem->SubCategory_ID);
						
						foreach ($Products as $Product) {
								$ProdTagObj = $wpdb->get_results("SELECT Tag_ID FROM $tagged_items_table_name WHERE Item_ID=" . $Product->Item_ID);
								$ProdTag = ObjectToArray($ProdTagObj);
								if ($category == "" or $subcategory == $Product->Category_ID) {
								if (sizeOf($tags) == 0 or count(array_intersect($tags, $ProdTag)) == count($tags)) {
								if ($prod_name == "" or strpos($Product->Item_Name, $prod_name) !== false) {
								if ($Product->Item_Display_Status != "Hide") {
								$HeaderBar .= "<a id='hidden_FB_link-" . $Product->Item_ID . "' class='fancybox' href='#prod-cat-addt-details-" . $Product->Item_ID . "'></a>";
								if (!in_array("Thumbnail", $ExcludedLayouts)) {$ProdThumbString .= AddProduct("Thumbnail", $Product->Item_ID, $Product, $ProdTagObj);}
								if (!in_array("List", $ExcludedLayouts)) {$ProdListString .= AddProduct("List", $Product->Item_ID, $Product, $ProdTagObj);}
								if (!in_array("Detail", $ExcludedLayouts)) {$ProdDetailString .= AddProduct("Detail", $Product->Item_ID, $Product, $ProdTagObj);}
								$Product_Count++;
								}}}}
						}
				}}
		}
				
		$ProdThumbString .= "<div class='clear'></div>\n";
		$ProdListString .= "<div class='clear'></div>\n";
		$ProdDetailString .= "<div class='clear'></div>\n";
		
		$ProdThumbString .= "</div>\n";
		$ProdListString .= "</div>\n";
		$ProdDetailString .= "</div>\n";
		
		if (in_array("Thumbnail", $ExcludedLayouts)) {unset($ProdThumbString);}
		if (in_array("List", $ExcludedLayouts)) {unset($ProdListString);}
		if (in_array("Detail", $ExcludedLayouts)) {unset($ProdDetailString);}
		
		
		// Create string from the arrays, should use the implode function instead
		foreach ($ProdCats as $key=>$value) {$ProdCatString .= $key . ",";}
		$ProdCatString = trim($ProdCatString, " ,");
		foreach ($ProdSubCats as $key=>$value) {$ProdSubCatString .= $key . ",";}
		$ProdSubCatString = trim($ProdSubCatString, " ,");
		foreach ($ProdTags as $key=>$value) {$ProdTagString .= $key . ",";}
		$ProdTagString = trim($ProdTagString, " ,");
		
		// If the sidebar is requested, add it
		if (($sidebar == "Yes" or $sidebar == "yes" or $sidebar == "YES") and $only_inner != "Yes") {
				// Get the categories, sub-categories and tags that apply to the products in the catalog
				if ($ProdCatString != "") {$Categories = $wpdb->get_results("SELECT Category_ID, Category_Name FROM $categories_table_name WHERE Category_ID in (" . $ProdCatString . ")");}
				if ($ProdSubCatString != "") {$SubCategories = $wpdb->get_results("SELECT SubCategory_ID, SubCategory_Name FROM $subcategories_table_name WHERE SubCategory_ID in (" . $ProdSubCatString . ")");}
				if ($ProdTagString != "") {$Tags = $wpdb->get_results("SELECT Tag_ID, Tag_Name FROM $tags_table_name WHERE Tag_ID in (" . $ProdTagString . ")");}
				else {$Tags = array();}
				
				$SidebarString .= "<div id='prod-cat-sidebar-" . $id . "' class='prod-cat-sidebar'>\n";
				$SidebarString .= "<form action='#' name='Product_Catalog_Sidebar_Form'>\n";
				
				//Create the 'Sort By' select box
				if ($Full_Version == "Yes") {
					  $SidebarString .= "<div id='prod-cat-sort-by' class='prod-cat-sort-by'>";
						$SidebarString .= __('Sort By:', 'UPCP') . "<br>";
						$SidebarString .= "<div class='styled-select styled-input'>";
						$SidebarString .= "<select name='upcp-sort-by' id='upcp-sort-by' onchange='UPCP_Sort_By();'>";
						$SidebarString .= "<option value=''></option>";
						$SidebarString .= "<option value='price_asc'>" . __('Price (Ascending)',  'UPCP') . "</option>";
						$SidebarString .= "<option value='price_desc'>" . __('Price (Descending)',  'UPCP') . "</option>";
						$SidebarString .= "<option value='name_asc'>" . __('Name (Ascending)',  'UPCP') . "</option>";
						$SidebarString .= "<option value='name_desc'>" . __('Name (Descending)',  'UPCP') . "</option>";
						$SidebarString .= "</select>";
						$SidebarString .= "</div>";
						$SidebarString .= "</div>";
				}
				
				// Create the text search box
				$SidebarString .= "<div id='prod-cat-text-search' class='prod-cat-text-search'>\n";
				$SidebarString .= __("Product Name:", 'UPCP') . "<br /><div class='styled-input'>";
				if ($Filter  == "Javascript" and $Tag_Logic == "OR") {$SidebarString .= "<input type='text' class='jquery-prod-name-text' name='Text_Search' value='" . __('Name', 'UPCP') . "...' onfocus='FieldFocus(this);' onblur='FieldBlur(this);' onkeyup='UPCP_Filer_Results_OR();'>\n";}
				elseif ($Filter  == "Javascript") {$SidebarString .= "<input type='text' class='jquery-prod-name-text' name='Text_Search' value='" . __('Name', 'UPCP') . "...' onfocus='FieldFocus(this);' onblur='FieldBlur(this);' onkeyup='UPCP_Filer_Results();'>\n";}
				else {$SidebarString .= "<input type='text' class='jquery-prod-name-text' name='Text_Search' value='" . __('Name', 'UPCP') . "...' onfocus='FieldFocus(this);' onblur='FieldBlur(this);' onkeyup='UPCP_Ajax_Filter();'>\n";}
				$SidebarString .= "</div></div>\n";
				
				// Create the categories checkboxes
				if (sizeof($Categories) > 0) {
					  $SidebarString .= "<div id='prod-cat-sidebar-category-div-" . $id . "' class='prod-cat-sidebar-category-div'>\n";
						$SidebarString .= "<div id='prod-cat-sidebar-category-title-" . $id . "' class='prod-cat-sidebar-category-title'><h3>" . __("Categories:", 'UPCP') . "</h3></div>\n";
						foreach ($Categories as $Category) {
								$SidebarString .= "<div id='prod-cat-sidebar-category-" . $Category->Category_ID . "' class='prod-cat-sidebar-category'>\n";
								if ($Filter  == "Javascript" and $Tag_Logic == "OR") {$SidebarString .= "<input type='checkbox' class='jquery-prod-cat-value' name='Category" . $Category->Category_ID . "' value='" . $Category->Category_ID . "' onclick='UPCP_Filer_Results_OR(); UPCPHighlight(this, \"" . $Color . "\");'>" . $Category->Category_Name . " (" . $ProdCats[$Category->Category_ID]/(3-sizeOf($ExcludedLayouts)) . ")\n";}
								elseif ($Filter  == "Javascript") {$SidebarString .= "<input type='checkbox' class='jquery-prod-cat-value' name='Category" . $Category->Category_ID . "' value='" . $Category->Category_ID . "' onclick='UPCP_Filer_Results(); UPCPHighlight(this, \"" . $Color . "\");'>" . $Category->Category_Name . " (" . $ProdCats[$Category->Category_ID]/(3-sizeOf($ExcludedLayouts)) . ")\n";}
								else {$SidebarString .= "<input type='checkbox' class='jquery-prod-cat-value' name='Category" . $Category->Category_ID . "' value='" . $Category->Category_ID . "' onclick='UPCP_Ajax_Filter(); UPCPHighlight(this, \"" . $Color . "\");'> " . $Category->Category_Name . " (" . $ProdCats[$Category->Category_ID]/(3-sizeOf($ExcludedLayouts)) . ")\n";}
								$SidebarString .= "</div>\n";
						}
						$SidebarString .= "</div>\n";
				}
				
				// Create the sub-categories checkboxes
				if (sizeof($SubCategories) > 0) {
					  $SidebarString .= "<div id='prod-cat-sidebar-subcategory-div-" . $id . "' class='prod-cat-sidebar-subcategory-div'>\n";
						$SidebarString .= "<div id='prod-cat-sidebar-subcategory-title-" . $id . "' class='prod-cat-sidebar-subcategory-title'><h3>" . __("Sub-Categories:", 'UPCP') . "</h3></div>\n";
						foreach ($SubCategories as $SubCategory) {
								$SidebarString .= "<div id='prod-cat-sidebar-subcategory-" . $SubCategory->SubCategory_ID . "' class='prod-cat-sidebar-subcategory'>\n";
								if ($Filter  == "Javascript" and $Tag_Logic == "OR") {$SidebarString .= "<input type='checkbox' class='jquery-prod-sub-cat-value' name='SubCategory[]' value='" . $SubCategory->SubCategory_ID . "'  onclick='UPCP_Filer_Results_OR(); UPCPHighlight(this, \"" . $Color . "\");'> " . $SubCategory->SubCategory_Name . " (" . $ProdSubCats[$SubCategory->SubCategory_ID]/(3-sizeOf($ExcludedLayouts)) . ")\n";}
								elseif ($Filter  == "Javascript") {$SidebarString .= "<input type='checkbox' class='jquery-prod-sub-cat-value' name='SubCategory[]' value='" . $SubCategory->SubCategory_ID . "'  onclick='UPCP_Filer_Results(); UPCPHighlight(this, \"" . $Color . "\");'> " . $SubCategory->SubCategory_Name . " (" . $ProdSubCats[$SubCategory->SubCategory_ID]/(3-sizeOf($ExcludedLayouts)) . ")\n";}
								else {$SidebarString .= "<input type='checkbox' class='jquery-prod-sub-cat-value' name='SubCategory[]' value='" . $SubCategory->SubCategory_ID . "'  onclick='UPCP_Ajax_Filter(); UPCPHighlight(this, \"" . $Color . "\");'> " . $SubCategory->SubCategory_Name . " (" . $ProdSubCats[$SubCategory->SubCategory_ID]/(3-sizeOf($ExcludedLayouts)) . ")\n";}
								$SidebarString .= "</div>\n";
						}
						$SidebarString .= "</div>\n";
				}
				
				// Create the tags checkboxes
				if (sizeof($Tags) > 0) {
					  $SidebarString .= "<div id='prod-cat-sidebar-tag-div-" . $id . "' class='prod-cat-sidebar-tag-div'>\n";
						$SidebarString .= "<div id='prod-cat-sidebar-tag-title-" . $id . "' class='prod-cat-tag-sidebar-title'><h3>" . __("Tags:", 'UPCP') . "</h3></div>\n";
						foreach ($Tags as $Tag) {
								$SidebarString .= "<div id='prod-cat-sidebar-tag-" . $Tag->Tag_ID . "' class='prod-cat-sidebar-tag'>\n";
								if ($Filter  == "Javascript" and $Tag_Logic == "OR") {$SidebarString .= "<input type='checkbox' class='jquery-prod-tag-value' name='Tag[]' value='" . $Tag->Tag_ID . "'  onclick='UPCP_Filer_Results_OR(); UPCPHighlight(this, \"" . $Color . "\");'>" . $Tag->Tag_Name . "\n";}
								elseif ($Filter  == "Javascript") {$SidebarString .= "<input type='checkbox' class='jquery-prod-tag-value' name='Tag[]' value='" . $Tag->Tag_ID . "'  onclick='UPCP_Filer_Results(); UPCPHighlight(this, \"" . $Color . "\");'> " . $Tag->Tag_Name . "\n";}
								else {$SidebarString .= "<input type='checkbox' class='jquery-prod-tag-value' name='Tag[]' value='" . $Tag->Tag_ID . "'  onclick='UPCP_Ajax_Filter(); UPCPHighlight(this, \"" . $Color . "\");'>" . $Tag->Tag_Name . "\n";}
								$SidebarString .= "</div>";
						}
				$SidebarString .= "</div>\n";
				}
				
				$SidebarString .= "</form>\n</div>\n";
		}
		
		if ($Mobile_Style == "Yes") {
			  $MobileMenuString .= "<div id='prod-cat-mobile-menu' class='upcp-mobile-menu'>\n";
				$MobileMenuString .= "<div id='prod-cat-mobile-search'>\n";
				if ($Tag_Logic == "OR") {$MobileMenuString .= "<input type='text' class='jquery-prod-name-text mobile-search' name='Mobile_Search' value='" . __('Product Name', 'UPCP') . "...' onfocus='FieldFocus(this);' onblur='FieldBlur(this);' onkeyup='UPCP_Filer_Results_OR();'>\n";}
				else {$MobileMenuString .= "<input type='text' class='jquery-prod-name-text mobile-search' name='Mobile_Search' value='" . __('Product Name', 'UPCP') . "...' onfocus='FieldFocus(this);' onblur='FieldBlur(this);'  onkeyup='UPCP_Filer_Results();'>\n";}
				$MobileMenuString .= "</div>";
				$MobileMenuString .= "</div>";
		}
		
		$HeaderBar .= "<div class='prod-cat-header-div " . $Color . "-prod-cat-header-div'>";
		$HeaderBar .= "<div class='prod-cat-header-padding'></div>";
		$HeaderBar .= "<div id='starting-layout' class='hidden-field'>" . $Starting_Layout . "</div>";
		if (!in_array("Thumbnail", $ExcludedLayouts)) {
			  $HeaderBar .= "<a href='#' onclick='ToggleView(\"" . __("Thumbnail", 'UPCP') . "\");return false;' title='Thumbnail'><div class='upcp-thumb-toggle-icon " . $Color . "-thumb-icon'></div></a>";
		}
		if (!in_array("List", $ExcludedLayouts)) {
			  $HeaderBar .= "<a href='#' onclick='ToggleView(\"" . __("List", 'UPCP') . "\"); return false;' title='List'><div class='upcp-list-toggle-icon " . $Color . "-list-icon'></div></a>";
		}
		if (!in_array("Detail", $ExcludedLayouts)) {
			  $HeaderBar .= "<a href='#' onclick='ToggleView(\"" . __("Detail", 'UPCP') . "\"); return false;' title='Detail'><div class='upcp-details-toggle-icon " . $Color . "-details-icon'></div></a>";
		}
		$HeaderBar .= "<div class='clear'></div>";
		$HeaderBar .= "</div>";
		
		if (isset($_GET['Product_ID'])) {
			  $JS .= "<script language='JavaScript' type='text/javascript'>";
				$JS .= "jQuery(window).load(OpenProduct('" . $_GET['Product_ID'] . "'));";
				$JS .= "</script>";
		}
		
		$InnerString .= "<div class='prod-cat-inner'>" . $ProdThumbString . "<div class='clear'></div>" . $ProdListString . "<div class='clear'></div>" . $ProdDetailString . "<div class='clear'></div></div>";
		
		if ($only_inner == "Yes") {return $InnerString;}
		
		$ReturnString .= "<div class='prod-cat-container'>";
		$ReturnString .= $HeaderBar;
		$ReturnString .= $MobileMenuString;
		$ReturnString .= $InnerString;
		$ReturnString .= $SidebarString;
		$ReturnString .= $JS;
		$ReturnString .= "<div class='clear'></div></div>";
		
		return $ReturnString;
}

/* Function to add the HTML for an individual product to the catalog */
function AddProduct($format, $Item_ID, $Product, $Tags) {
		// Add the required global variables
		global $wpdb, $categories_table_name, $subcategories_table_name, $tags_table_name, $tagged_items_table_name, $catalogues_table_name, $catalogue_items_table_name, $items_table_name, $item_images_table_name;
		global $ProdCats, $ProdSubCats, $ProdTags, $ReturnString;
		
		$ReadMore = get_option("UPCP_Read_More");
		$Links = get_option("UPCP_Product_Links");
		$Pretty_Links = get_option("UPCP_Pretty_Links");
		$Detail_Desc_Chars = get_option("UPCP_Desc_Chars");
		if ($Links == "New") {$NewWindow = true;}
		else {$NewWindow = false;}
		
		$Description = ConvertCustomFields($Product->Item_Description);
		$Description = do_shortcode($Description);
		
		//Select the product info, tags and images for the product
		$Item_Images = $wpdb->get_results("SELECT Item_Image_URL, Item_Image_ID FROM $item_images_table_name WHERE Item_ID=" . $Item_ID);
		$TagsString = "";
		
		if ($Product->Item_Photo_URL != "") {$PhotoURL = htmlspecialchars($Product->Item_Photo_URL, ENT_QUOTES);}
		else {$PhotoURL = plugins_url('ultimate-product-catalogue/images/No-Photo-Available.jpg');}
						
		// Increment the arrays keeping count of the number of products in each 
		// category, sub-category and tag
		$ProdCats[$Product->Category_ID]++;
		$ProdSubCats[$Product->SubCategory_ID]++;
		foreach ($Tags as $Tag) {$ProdTags[$Tag->Tag_ID]++; $TagsString .= $Tag->Tag_ID . ", ";}
		$TagsString = trim($TagsString, " ,");
		
		// Check whether the FancyBox for WordPress plugin is activated
		$plugin = "fancybox-for-wordpress/fancybox.php";
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$FancyBox_Installed = is_plugin_active($plugin);
		
		$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
		$FB_Perm_URL = $uri_parts[0] . "?" . $uri_parts[1];
		if ($uri_parts[1] == "") {$FB_Perm_URL .= "Product_ID=" . $Product->Item_ID;}
		else {$FB_Perm_URL .= "&Product_ID=" . $Product->Item_ID;}
		
		if ($Product->Item_Link != "") {$ItemLink = $Product->Item_Link;}
		elseif ($FancyBox_Installed) {$ItemLink = "#prod-cat-addt-details-" . $Product->Item_ID; $FancyBoxClass = true;}
		elseif ($Pretty_Links == "Yes") {$ItemLink = $uri_parts[0] . "" . $Product->Item_Slug . "/?" . $uri_parts[1];}
		else {$ItemLink = $uri_parts[0] . "?" . $uri_parts[1] . "&SingleProduct=" . $Product->Item_ID;}

		//Create the listing for the thumbnail layout display
		if ($format == "Thumbnail") {				
				$ProductString .= "<div id='prod-cat-item-" . $Product->Item_ID . "' class='prod-cat-item upcp-thumb-item'>\n";
				$ProductString .= "<div id='prod-cat-thumb-div-" . $Product->Item_ID . "' class='prod-cat-thumb-image-div upcp-thumb-image-div'>";
				$ProductString .= "<a class='";
				if ($FancyBoxClass and !$NewWindow) {$ProductString .= "fancybox";}
				$ProductString .= "' ";
				if ($NewWindow) {$ProductString .= "target='_blank'";}
				$ProductString .= " href='" . $ItemLink . "' onclick='RecordView(" . $Product->Item_ID . ");'>";
				$ProductString .= "<img src='" . $PhotoURL . "' alt='" . $Product->Item_Name . " Image' id='prod-cat-thumb-" . $Product->Item_ID . "' class='prod-cat-thumb-image upcp-thumb-image'>";
				$ProductString .= "</a>";	
				$ProductString .= "</div>\n";
				$ProductString .= "<a class='";
				if ($FancyBoxClass and !$NewWindow) {$ProductString .= "fancybox";}
				$ProductString .= " no-underline'";
				if ($NewWindow) {$ProductString .= "target='_blank'";}
				$ProductString .= " href='" . $ItemLink . "' onclick='RecordView(" . $Product->Item_ID . ");'>";
				$ProductString .= "<div id='prod-cat-title-" . $Product->Item_ID . "' class='prod-cat-title upcp-thumb-title'>" . $Product->Item_Name . "</div>\n";
				$ProductString .= "</a>";
				$ProductString .= "<div id='prod-cat-price-" . $Product->Item_ID . "' class='prod-cat-price upcp-thumb-price'>" . $Product->Item_Price . "</div>\n";
				$ProductString .= "<a class='";
				if ($FancyBoxClass and !$NewWindow) {$ProductString .= "fancybox";}
				$ProductString .= "' ";
				if ($NewWindow) {$ProductString .= "target='_blank'";}
				$ProductString .= " href='" . $ItemLink . "' onclick='RecordView(" . $Product->Item_ID . ");'>";
				$ProductString .= "<div id='prod-cat-details-link-" . $Product->Item_ID . "' class='prod-cat-details-link upcp-thumb-details-link'>" . __("Details", 'UPCP') . "</div>\n";
				$ProductString .= "</a>";
		}
		//Create the listing for the list layout display
		if ($format == "List") {				
				$ProductString .= "<div id='prod-cat-item-" . $Product->Item_ID . "' class='prod-cat-item upcp-list-item'>\n";
				$ProductString .= "<div id='prod-cat-title-" . $Product->Item_ID . "' class='prod-cat-title upcp-list-title' onclick='ToggleItem(" . $Product->Item_ID . ");'>" . $Product->Item_Name . "</div>\n";
				$ProductString .= "<div id='prod-cat-price-" . $Product->Item_ID . "' class='prod-cat-price upcp-list-price' onclick='ToggleItem(" . $Product->Item_ID . ");'>" . $Product->Item_Price . "</div>\n";
				$ProductString .= "<div id='prod-cat-details-" . $Product->Item_ID . "' class='prod-cat-details upcp-list-details hidden-field'>\n";
						$ProductString .= "<div id='prod-cat-thumb-div-" . $Product->Item_ID . "' class='prod-cat-thumb-image-div upcp-list-image-div'>";
						$ProductString .= "<a class='";
						if ($FancyBoxClass and !$NewWindow) {$ProductString .= "fancybox";}
						$ProductString .= "' ";
				if ($NewWindow) {$ProductString .= "target='_blank'";}
				$ProductString .= " href='" . $ItemLink . "' onclick='RecordView(" . $Product->Item_ID . ");'>";
						$ProductString .= "<img src='" . $PhotoURL . "' alt='" . $Product->Item_Name . " Image' id='prod-cat-thumb-" . $Product->Item_ID . "' class='prod-cat-list-image upcp-list-image'>";
						$ProductString .= "</a>";
						$ProductString .= "</div>\n";
						$ProductString .= "<div id='prod-cat-desc-" . $Product->Item_ID . "' class='prod-cat-desc upcp-list-desc'>" . $Description . "</div>\n";
					 	$ProductString .= "<a class='";
						if ($FancyBoxClass and !$NewWindow) {$ProductString .= "fancybox";}
						$ProductString .= "' ";
				if ($NewWindow) {$ProductString .= "target='_blank'";}
				$ProductString .= " href='" . $ItemLink . "' onclick='RecordView(" . $Product->Item_ID . ");'>";
						$ProductString .= "<div id='prod-cat-details-link-" . $Product->Item_ID . "' class='prod-cat-details-link upcp-list-details-link'>" . __("Images", 'UPCP') . "</div>\n";
						$ProductString .= "</a>";
				$ProductString .= "</div>";
		}
		//Create the listing for the detail layout display
		if ($format == "Detail") {				
				$ProductString .= "<div id='prod-cat-item-" . $Product->Item_ID . "' class='prod-cat-item upcp-detail-item'>\n";
				$ProductString .= "<div id='prod-cat-detail-div-" . $Product->Item_ID . "' class='prod-cat-detail-image-div upcp-detail-image-div'>";
				$ProductString .= "<a class='";
				if ($FancyBoxClass and !$NewWindow) {$ProductString .= "fancybox";}
				$ProductString .= "' ";
				if ($NewWindow) {$ProductString .= "target='_blank'";}
				$ProductString .= " href='" . $ItemLink . "' onclick='RecordView(" . $Product->Item_ID . ");'>";
				$ProductString .= "<img src='" . $PhotoURL . "' alt='" . $Product->Item_Name . " Image' id='prod-cat-detail-" . $Product->Item_ID . "' class='prod-cat-thumb-image upcp-thumb-image'>";
				$ProductString .= "</a>";	
				$ProductString .= "</div>\n";
				$ProductString .= "<div id='prod-cat-mid-div-" . $Product->Item_ID . "' class='prod-cat-mid-detail-div upcp-mid-detail-div'>";
				$ProductString .= "<div id='prod-cat-title-" . $Product->Item_ID . "' class='prod-cat-title upcp-detail-title'>" . $Product->Item_Name . "</div>\n";
				if ($ReadMore == "Yes") {$ProductString .= "<div id='prod-cat-desc-" . $Product->Item_ID . "' class='prod-cat-desc upcp-detail-desc'>" . substr($Description, 0, $Detail_Desc_Chars);}
				else {$ProductString .= "<div id='prod-cat-desc-" . $Product->Item_ID . "' class='prod-cat-desc upcp-detail-desc'>" . $Description;}
				if ($ReadMore == "Yes") {
					  if (strlen($Description) > $Detail_Desc_Chars) {
					  	  $ProductString .= "... <a class='";
								if ($FancyBoxClass and !$NewWindow) {$ProductString .= "fancybox";}
								$ProductString .= "' ";
								if ($NewWindow) {$ProductString .= "target='_blank'";}
								$ProductString .= " href='#prod-cat-addt-details-" . $Product->Item_ID . "' onclick='RecordView(" . $Product->Item_ID . ");'>" . __("Read More", 'UPCP') . "</a>";
						}
				}
				$ProductString .= "</div>\n";
				$ProductString .= "</div>";
				$ProductString .= "<div id='prod-cat-end-div-" . $Product->Item_ID . "' class='prod-cat-end-detail-div upcp-end-detail-div'>";
				$ProductString .= "<div id='prod-cat-price-" . $Product->Item_ID . "' class='prod-cat-price upcp-detail-price'>" . $Product->Item_Price . "</div>\n";
				$ProductString .= "<a class='";
				if ($FancyBoxClass and !$NewWindow) {$ProductString .= "fancybox";}
				$ProductString .= "' ";
				if ($NewWindow) {$ProductString .= "target='_blank'";}
				$ProductString .= " href='" . $ItemLink . "' onclick='RecordView(" . $Product->Item_ID . ");'>";
				$ProductString .= "<div id='prod-cat-details-link-" . $Product->Item_ID . "' class='prod-cat-details-link upcp-detail-details-link'>" . __("Details", 'UPCP') . "</div>\n";
				$ProductString .= "</a>";
				$ProductString .= "</div>";
		}
		
		if ($FancyBox_Installed) {
				
				$ProductString .= "<div style='display:none;' id='upcp-fb-" . $Product->Item_ID . "'>";
				$ProductString .= "<div id='prod-cat-addt-details-" . $Product->Item_ID . "' class='prod-cat-addt-details'>";
				$ProductString .= "<div id='prod-cat-addt-details-thumbs-div-" . $Product->Item_ID . "' class='prod-cat-addt-details-thumbs-div'>";
				$ProductString .= "<img src='" . $PhotoURL . "' id='prod-cat-addt-details-thumb-P". $Product->Item_ID . "' class='prod-cat-addt-details-thumb' onclick='ZoomImage(\"" . $Product->Item_ID . "\", \"0\");'>";
				foreach ($Item_Images as $Image) {$ProductString .= "<img src='" . htmlspecialchars($Image->Item_Image_URL, ENT_QUOTES) . "' id='prod-cat-addt-details-thumb-". $Image->Item_Image_ID . "' class='prod-cat-addt-details-thumb' onclick='ZoomImage(\"" . $Product->Item_ID . "\", \"" . $Image->Item_Image_ID . "\");'>";}
				$ProductString .= "</div>";
				$ProductString .= "<div id='prod-cat-addt-details-right-div-" . $Product->Item_ID . "' class='prod-cat-addt-details-right-div'>";
				$ProductString .= "<h2 class='prod-cat-addt-details-title'><a class='no-underline' href='http://" . $_SERVER['HTTP_HOST'] . $FB_Perm_URL . "'>" . $Product->Item_Name . "<img class='upcp-product-url-icon' src='" . get_bloginfo('wpurl') . "/wp-content/plugins/ultimate-product-catalogue/images/insert_link.png' /></a></h2>";
				$ProductString .= "<div id='prod-cat-addt-details-main-div-" . $Product->Item_ID . "' class='prod-cat-addt-details-main-div'>";
				$ProductString .= "<a class='upcp-no-pointer' onclick='return false'>";
				$ProductString .= "<img src='" . $PhotoURL . "' alt='" . $Product->Item_Name . " Image' id='prod-cat-addt-details-main-" . $Product->Item_ID . "' class='prod-cat-addt-details-main'>";
				$ProductString .= "</a>";
				$ProductString .= "</div>";
				$ProductString .= "<div class='clear'></div>";
				$ProductString .= "<div id='prod-cat-addt-details-desc-div-" . $Product->Item_ID . "' class='prod-cat-addt-details-desc-div'>";
				$ProductString .= $Description . "</div>";
				$ProductString .= "</div></div></div>";
				//$ProductString .= "</div>";
		}
		
		// Add hidden fields with the category, sub-category and tag ID's for each product		
		$ProductString .= "<div id='prod-cat-category-jquery-" . $Product->Item_ID . "' class='prod-cat-category-jquery jquery-hidden'> " . $Product->Category_ID . ",</div>\n";
		$ProductString .= "<div id='prod-cat-subcategory-jquery-" . $Product->Item_ID . "' class='prod-cat-subcategory-jquery jquery-hidden'> " . $Product->SubCategory_ID . ",</div>\n";
		$ProductString .= "<div id='prod-cat-tag-jquery-" . $Product->Item_ID . "' class='prod-cat-tag-jquery jquery-hidden'> " . $TagsString . ",</div>\n";
		$ProductString .= "<div id='prod-cat-title-jquery-" . $Product->Item_ID . "' class='prod-cat-title-jquery jquery-hidden'> " . $Product->Item_Name . ",</div>\n";
		$ProductString .= "<div class='clear'></div>\n";
		$ProductString .= "</div>\n";
				
		return $ProductString;
}

function SingleProductPage() {
		global $wpdb, $items_table_name, $item_images_table_name;
		
		$Pretty_Links = get_option("UPCP_Pretty_Links");
		$Single_Page_Price = get_option("UPCP_Single_Page_Price");
		
		if ($Pretty_Links == "Yes") {$Product = $wpdb->get_row("SELECT * FROM $items_table_name WHERE Item_Slug='" . get_query_var('single_product') . "'");}
		else {$Product = $wpdb->get_row("SELECT * FROM $items_table_name WHERE Item_ID='" . $_GET['SingleProduct'] . "'");}
		$Item_Images = $wpdb->get_results("SELECT Item_Image_URL, Item_Image_ID FROM $item_images_table_name WHERE Item_ID=" . $Product->Item_ID);
		
		$Links = get_option("UPCP_Product_Links");
		$Description = ConvertCustomFields($Product->Item_Description);
		$Description = do_shortcode($Description);
		
		if ($Product->Item_Photo_URL != "") {$PhotoURL = htmlspecialchars($Product->Item_Photo_URL, ENT_QUOTES);}
		else {$PhotoURL = plugins_url('ultimate-product-catalogue/images/No-Photo-Available.jpg');}
		
		$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
		$SP_Perm_URL = $uri_parts[0] . "?" . $uri_parts[1];
		$Return_URL = $uri_parts[0];
		if ($Pretty_Links == "Yes") {$Return_URL = substr($uri_parts[0], 0, strrpos($uri_parts[0], "/", -2)) . "/?" . $uri_parts[1];}
		elseif ($uri_parts[0] == "/") {$Return_URL .= "?" . substr($uri_parts[1], 0, strpos($uri_parts[1], "&"));}
		
		if ($uri_parts[1] == "") {$SP_Perm_URL .= "Product_ID=" . $Product->Item_ID;}
		else {$SP_Perm_URL .= "&Product_ID=" . $Product->Item_ID;}
		
		$ProductString .= "<div class='prod-cat-back-link'>";
		$ProductString .= "<a href='" . $Return_URL . "'>&#171; Back to Catalogue</a>";
		$ProductString .= "</div>";
		
		$ProductString .= "<div id='prod-cat-addt-details-" . $Product->Item_ID . "' class='prod-cat-addt-details'>";
		$ProductString .= "<div id='prod-cat-addt-details-thumbs-div-" . $Product->Item_ID . "' class='prod-cat-addt-details-thumbs-div'>";
		$ProductString .= "<img src='" . $PhotoURL . "' id='prod-cat-addt-details-thumb-P". $Product->Item_ID . "' class='prod-cat-addt-details-thumb' onclick='ZoomImage(\"" . $Product->Item_ID . "\", \"0\");'>";
		foreach ($Item_Images as $Image) {$ProductString .= "<img src='" . htmlspecialchars($Image->Item_Image_URL, ENT_QUOTES) . "' id='prod-cat-addt-details-thumb-". $Image->Item_Image_ID . "' class='prod-cat-addt-details-thumb' onclick='ZoomImage(\"" . $Product->Item_ID . "\", \"" . $Image->Item_Image_ID . "\");'>";}
		$ProductString .= "</div>";
		$ProductString .= "<div id='prod-cat-addt-details-right-div-" . $Product->Item_ID . "' class='prod-cat-addt-details-right-div'>";
		$ProductString .= "<h2 class='prod-cat-addt-details-title'><a class='no-underline' href='http://" . $_SERVER['HTTP_HOST'] . $SP_Perm_URL . "'>" . $Product->Item_Name . "<img class='upcp-product-url-icon' src='" . get_bloginfo('wpurl') . "/wp-content/plugins/ultimate-product-catalogue/images/insert_link.png' /></a></h2>";
		if ($Single_Page_Price == "Yes") {$ProductString .= "<h3 class='prod-cat-addt-details-price'>" . $Product->Item_Price . "</h3>";}
		$ProductString .= "<div id='prod-cat-addt-details-main-div-" . $Product->Item_ID . "' class='prod-cat-addt-details-main-div'>";
		$ProductString .= "<img src='" . $PhotoURL . "' alt='" . $Product->Item_Name . " Image' id='prod-cat-addt-details-main-" . $Product->Item_ID . "' class='prod-cat-addt-details-main'>";
		$ProductString .= "</div>";
		$ProductString .= "<div class='clear'></div>";
		$ProductString .= "<div id='prod-cat-addt-details-desc-div-" . $Product->Item_ID . "' class='prod-cat-addt-details-desc-div'>";
		$ProductString .= $Description . "</div>";
		$ProductString .= "<div class='clear'></div>\n";
		$ProductString .= "</div>\n";
		$ProductString .= "</div>\n";
		
		return $ProductString;
}

function ConvertCustomFields($Description) {
		global $wpdb;
		global $fields_table_name, $fields_meta_table_name;
		
		$Fields = $wpdb->get_results("SELECT Field_ID, Field_Slug FROM $fields_table_name");
		$Metas = $wpdb->get_results("SELECT Field_ID, Meta_Value FROM $fields_meta_table_name");
		
		if (is_array($Fields)) {
			  if (is_array($Metas)) {
					  foreach ($Metas as $Meta) {
					  		$MetaArray[$Meta->Field_ID] = $Meta->Meta_Value;
						}
				}
				foreach ($Fields as $Field) {
						$Description = str_replace("[" . $Field->Field_Slug . "]" , $MetaArray[$Field->Field_ID], $Description); 
				}
		}
		
		return $Description;
}

function ObjectToArray($Obj) {
		$TagsArray = array();
		foreach ($Obj as $Tag) {
				$TagsArray[] = $Tag->Tag_ID;
		}
		
		return $TagsArray;
}
add_shortcode("product-catalogue", "Insert_Product_Catalog");

 ?>