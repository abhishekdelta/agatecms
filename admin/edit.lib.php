<?php
/* NEED THIS CODE FOR EDIT PAGE CONTENTS
	$page_fields=getPageTypeTableFields($page_type);
	$page_field_array=explode("|",$page_fields);
	$page_fieldtype_array=array();
	for($i=0; $i <count($page_field_array); $i++)
	{
		$field=trim($page_field_array[$i]);
		if(strpos($field,"'")==false)
		{
			$page_field_array[$i]=$field;
			$page_fieldtype_array[$i]="unquoted";
			continue;
		}
		else
		{
			$field=trim($field,"'");
			$page_field_array[$i]=$field;
			$page_fieldtype_array[$i]="quoted";
			continue;
		}		
		
	}*/
?>
