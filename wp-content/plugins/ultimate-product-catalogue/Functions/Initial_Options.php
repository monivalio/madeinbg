<?php
/* Adds a the default options for the product catalogue which can be changed on the options page */
function Initial_UPCP_Options() {
		update_option("UPCP_Color_Scheme", "Blue");
		update_option("UPCP_Product_Links", "Same");
		update_option("UPCP_Tag_Logic", "AND");
		update_option("UPCP_Filter_Type", "Javascript");
		update_option("UPCP_Read_More", "Yes");
		update_option("UPCP_Pretty_Links", "No");
		update_option("UPCP_Mobile_SS", "No");
		update_option("UPCP_Install_Flag", "Yes");
		update_option("UPCP_First_Install_Version", "2.1");
		update_option("UPCP_Desc_Chars", 240);
}
