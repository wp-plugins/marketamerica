/*
 * copyright (c) 2010 Market America - Golam Osmani - marketamerica.com
 *
 * This file is part of WordPress Market America Widget Plugin.
 *
 * WordPress Market America Widget is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * WordPress Market America Widget is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WordPress Market America Widget.
 * If not, see <http://www.gnu.org/licenses/>.
*/
function MA_includeJS(path)
{
    var fileref=document.createElement("script");
    fileref.setAttribute("type","text/javascript");
    fileref.setAttribute("src", path);
    if (typeof fileref!="undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref);
}
var MA_maCategoriesLoaded = false;
var MA_maCatList = null;
var MA_maLoadCatCallback = null;
//var maUrlBase = "http://localhost/ma_ws/";
//var maUrlBase = "http://208.43.210.246:8480/";
var maUrlBase = "http://mawidget.marketamerica.com/";
function MA_LoadCategoryList()
{
	if(MA_maCategoriesLoaded==false)
	{
		var url = maUrlBase + "CSearchByCat.aspx?format=json&catID=0&controlId=dummy&defaultValue=dummy&prdCountry=" + MA_prdCountry + "&merchCountry=" + MA_merchCountry + "&callback=MA_ShowCategories";
	    MA_includeJS(url);
	}
	else
	{
		MA_ShowCategories(null, "", "");
	}
}
function MA_ShowCategories(jsonData, dummy1, dummy2)
{
	if(MA_maCategoriesLoaded==false)
	{
	    var data = eval(jsonData);
	    if(data==null|| data.length ==0) return;
	    MA_maCatList = data;
	    MA_maCategoriesLoaded = true;		
	}
	if(MA_maLoadCatCallback && MA_maLoadCatCallback != 'null')
	{
		MA_maLoadCatCallback();
		MA_maLoadCatCallback = null;
	}
}
function MA_LoadCategory(optionId, defaultValue)
{
    var optionObject = document.getElementById(optionId);
	if(!optionObject || MA_maCatList==null || MA_maCatList.length==0) return;
	optionObject.options.length = 0;
	var y=document.createElement('option');
		y.text = "Select...";
		y.value = "";
		if(defaultValue=="" || defaultValue == null) y.selected = true;
		try
		  {
			optionObject.add(y,null); // standards compliant
		  }
		catch(ex)
		  {
			optionObject.add(y); // IE only
		  }
	for (var i = 0; i < MA_maCatList.length; i++)
	{
		y=document.createElement('option');
		y.text = MA_maCatList[i].CategoryName;
		y.value = MA_maCatList[i].CategoryId;
		if(defaultValue==MA_maCatList[i].CategoryId) y.selected = true;
		try
		  {
			optionObject.add(y,null); // standards compliant
		  }
		catch(ex)
		  {
			optionObject.add(y); // IE only
		  }
	}
}
function MA_ChangeCategory(catOptionId, subCatOptionId)
{
	var catOptionObject = document.getElementById(catOptionId);
	var subCatOptionObject = document.getElementById(subCatOptionId);
	if(!catOptionObject || !subCatOptionObject) return;
	
	if(catOptionObject.selectedIndex <=0) return;
	var value = catOptionObject.options[catOptionObject.selectedIndex].value;
	MA_LoadSubCategory(subCatOptionId, value, '');
}
function MA_ChangeSubCategory(subCatOptionId, productOptionId)
{
	var productOptionObject = document.getElementById(productOptionId);
	var subCatOptionObject = document.getElementById(subCatOptionId);
	if(!productOptionObject || !subCatOptionObject) return;
	
	if(subCatOptionObject.selectedIndex <=0) return;
	var value = subCatOptionObject.options[subCatOptionObject.selectedIndex].value;
	if(!value) return;
	//var catPart = value.toString().split('-');
	//if(!catPart || catPart.length!=2) return;
	MA_LoadProduct(productOptionId, value, '');
}
function MA_LoadProduct(optionId, subCatId, defaultValue)
{
    var optionObject = document.getElementById(optionId);
	if(!optionObject) return;
	var url = maUrlBase + "PSearchByCat.aspx?format=json&catID=" + subCatId + "&controlId=" + optionId + "&defaultValue=" + defaultValue+ "&prdCountry=" + MA_prdCountry + "&merchCountry=" + MA_merchCountry + "&callback=MA_ShowProducts";
    MA_includeJS(url);
}
function MA_ShowProducts(jsonData, optionId, defaultValue)
{	
	if(!jsonData) return;
	var data = eval(jsonData);
	if(data==null) return;
	var optionObject = document.getElementById(optionId);
	if(!optionObject) return;	
	optionObject.options.length = 0;
	var y=document.createElement('option');
	y.text = "Select...";
	y.value = "";
	if(defaultValue=="" || defaultValue == null) y.selected = true;
	try
	  {
		optionObject.add(y,null); // standards compliant
	  }
	catch(ex)
	  {
		optionObject.add(y); // IE only
	  }
	if(data.ProductList==null|| data.ProductList.length ==0) return;
	for (var i = 0; i < data.ProductList.length; i++)
	{
		var product = data.ProductList[i];
		y=document.createElement('option');
		y.text = product.Name;
		y.value = product.ID;
		if(defaultValue==product.ID) y.selected = true;
		try
		  {
			optionObject.add(y,null); // standards compliant
		  }
		catch(ex)
		  {
			optionObject.add(y); // IE only
		  }
	}
}

function MA_LoadSubCategory(optionId, catId, defaultValue)
{
    var optionObject = document.getElementById(optionId);
	if(!optionObject) return;
	var url = maUrlBase + "CSearchByCat.aspx?format=json&catID=" + catId + "&controlId=" + optionId + "&defaultValue=" + defaultValue+ "&prdCountry=" + MA_prdCountry + "&merchCountry=" + MA_merchCountry + "&callback=MA_ShowSubCategory";
	MA_includeJS(url);
}
function MA_ShowSubCategory(jsonData, optionId, defaultValue)
{
	if(!jsonData) return;
	var data = eval(jsonData);
	if(data==null) return;
    var optionObject = document.getElementById(optionId);
	if(!optionObject || data==null || data.length==0) return;
	var category = '';
	optionObject.options.length = 0;
	var y=document.createElement('option');
		y.text = "Select...";
		y.value = "";
		if(defaultValue=="" || defaultValue == null) y.selected = true;
		try
		  {
			optionObject.add(y,null); // standards compliant
		  }
		catch(ex)
		  {
			optionObject.add(y); // IE only
		  }
	
		for (var i = 0; i < data.length; i++)
		{
			y=document.createElement('option');
			var subCat = data[i];
			y.text = subCat.CategoryName;
			y.value = subCat.CategoryId;
			if(defaultValue==subCat.CategoryId) y.selected = true;
		try
		  {
			optionObject.add(y,null); // standards compliant
		  }
		catch(ex)
		  {
			optionObject.add(y); // IE only
		  }
		}		
}
