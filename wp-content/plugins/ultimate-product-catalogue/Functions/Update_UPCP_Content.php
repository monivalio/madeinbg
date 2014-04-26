<?php
/* This file is the action handler. The appropriate function is then called based 
*  on the action that's been selected by the user. The functions themselves are all
* stored either in Prepare_Data_For_Insertion.php or Update_Admin_Databases.php */

function Update_UPCP_Content() {
global $upcp_message;
if (isset($_GET['Action'])) {
				switch ($_GET['Action']) {
    				case "EditProduct":
						case "AddProduct":
        				$upcp_message = Add_Edit_Product();
								break;
						case "DeleteProduct":
								$upcp_message = Delete_UPCP_Product($_GET['Item_ID']);
								break;
						case "MassDeleteProducts":
								$upcp_message = Mass_Delete_Products();
								break;
						case "AddProductSpreadsheet":
        				$upcp_message = Add_Products_From_Spreadsheet();
								break;
						case "AddProductImage":
								$upcp_message = Prepare_Add_Product_Image();
								break;
						case "DeleteProductImage":
								$upcp_message = Delete_Product_Image();
								break;
						case "EditCategory":
						case "AddCategory":
        				$upcp_message = Add_Edit_Category();
								break;
						case "DeleteCategory":
								$upcp_message = Delete_UPCP_Category($_GET['Category_ID']);
								break;
						case "MassDeleteCategories":
								$upcp_message = Mass_Delete_Categories();
								break;
						case "EditCatalogue":
						case "AddCatalogue":
        				$upcp_message = Add_Edit_Catalogue();
								break;
						case "DeleteCatalogue":
								$upcp_message = Delete_UPCP_Catalogue($_GET['Catalogue_ID']);
								break;
						case "MassDeleteCatalogues":
								$upcp_message = Mass_Delete_Catalogues();
								break;
						case "DeleteCatalogueItem":
								$upcp_message = Delete_Products_Catalogue();
								break;
						case "EditSubCategory":
						case "AddSubCategory":
        				$upcp_message = Add_Edit_SubCategory();
								break;
						case "DeleteSubCategory":
								$upcp_message = Delete_UPCP_SubCategory($_GET['SubCategory_ID']);
								break;
						case "MassDeleteSubCategories":
								$upcp_message = Mass_Delete_SubCategories();
								break;
						case "EditTag":
						case "AddTag":
        				$upcp_message = Add_Edit_Tag();
								break;
						case "DeleteTag":
								$upcp_message = Delete_UPCP_Tag($_GET['Tag_ID']);
								break;
						case "MassDeleteTags":
								$upcp_message = Mass_Delete_UPCP_Tags();
								break;
						case "DeleteTaggedItem":
								$upcp_message = Delete_Products_Tags();
								break;
						case "EditCustomField":
						case "AddCustomField":
        				$upcp_message = Add_Edit_Custom_Field();
								break;
						case "DeleteCustomField":
								$upcp_message = Delete_UPCP_Custom_Field($_GET['Field_ID']);
								break;
						case "MassDeleteCustomFields":
								$upcp_message = Mass_Delete_UPCP_Custom_Fields();
								break;		
						case "UpdateOptions":
								$upcp_message = Update_UPCP_Options();
								break;
						default:
								$upcp_message = __("The form has not worked correctly. Please contact the plugin developer.", 'UPCP');
								break;
				}
		}
}

?>