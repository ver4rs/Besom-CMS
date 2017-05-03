<?php require_once "include_cs2.php" ?>
<?php require_once "server_php/phpuploader/include_phpuploader.php" ?>
<?php

//error_reporting(E_ALL ^ E_NOTICE);

if(!@$_SESSION)session_start();


// php.ini add line : extension="ext/php_gd2.dll"

function RTE_Impl_LoadImage($_x55)
{
	$_x22=pathinfo($_x55,PATHINFO_EXTENSION);
	switch(strtolower($_x22))
	{
		case "png":
			return imagecreatefrompng($_x55);
		case "gif":
			return imagecreatefromgif($_x55);
		case "jpg":
		case "jpeg":
		default:
			$name=strtolower(basename($_x55));
			if(strpos($name,".png"))
				return imagecreatefrompng($_x55);
			if(strpos($name,".gif"))
				return imagecreatefromgif($_x55);
			return imagecreatefromjpeg($_x55);
	}
}

function RTE_GetPhotoDimensions($_x55)
{
	$_x56=RTE_Impl_LoadImage($_x55);
	$_x57=array();
	$_x57["Width"]=imagesx($_x56);
	$_x57["Height"]=imagesy($_x56);
	imagedestroy($_x56);
	return $_x57;
}
function RTE_GenerateThumbnail($_x55,$_x58,$_x59,$_x60)
{
	$_x56=RTE_Impl_LoadImage($_x55);
	$_x61=imagecreatetruecolor($_x59,$_x60);
	imagecopyresized($_x61,$_x56,0,0,0,0,$_x59,$_x60,imagesx($_x56),imagesy($_x56));
	imagejpeg($_x61,$_x58);
	imagedestroy($_x56);
	imagedestroy($_x61);
}


class RichTextEditor extends CuteSoftLibrary
{
	//$_x62->Name same as $_x62->ID 
	
	public $Name="RichTextEditor";
	
	public $UploaderName;
	
	public $Version="2012010311";
	public $LoadDelay=8;
	public $RenderSupportAjax=true;
	
	public $Skin="office2007blue";
	public $Toolbar="ribbon";
	public $EnableDragDrop;
	
	public $Language;
	
	//properties
	public $ContentCss;
	public $ContentCssText;
	public $PreviewCss;
	public $PreviewCssText;
	public $ToolbarItems;
	public $DisabledItems;
	public $TextDirection;
	public $URLType;
	public $ResizeMode;
	public $PasteMode;
	public $EnterKeyTag;
	public $ShiftEnterKeyTag;
	public $EditorMode;
	public $FullScreen;
	public $ToggleBorder;
	public $ReadOnly;
	public $DesignDocType;
	public $SaveButtonScript;
	public $SaveButtonMode;
	public $ContextMenuMode;
	public $EnableContextMenu;
	public $EnableIEBorderRadius;
	public $EnableAntiSpamEmailEncoder;
	public $EnableObjectResizing;
	public $BaseHref;
	public $EditorBodyId;
	public $EditorBodyClass;
	public $EditorBodyStyle;
	public $DisableClassList;
	public $AutoParseClasses;
	public $MaxHTMLLength;
	public $MaxTextLength;
	public $AutoFocus;
	public $ShowRulers;
	public $ShowEditMode;
	public $ShowCodeMode;
	public $ShowPreviewMode;
	public $ShowTagList;
	public $ShowZoomView;
	public $ShowStatistics;
	public $ShowResizeCorner;
	public $ShowBottomBar;
	public $ShowLinkbar;
	public $ShowToolbar;
	public $ShowCodeToolbar;
	public $ShowPreviewToolbar;
	public $AllowScriptCode;
	public $UseHTMLEntities;
	public $EditCompleteDocument;
	public $TagWhiteList;
	public $TagBlackList;
	public $AttrWhiteList;
	public $AttrBlackList;
	public $StyleWhiteList;
	public $StyleBlackList;
	
	public $AjaxPostbackUrl;
	
	//properties end
	
	public $SecurityPolicyFile="default.config";
	
	public $Uploader;
	
	public $FilterBegin;
	public $FilterEnd;
	
	var $_text="";
	
	function get_XHTML()
	{
		return $this->ApplyFilter($this->_text,"ConvertToXHTML");
	}
	function get_Text()
	{
		return $this->_text;
	}
	function set_Text($_x63)
	{
		if(!$_x63)
		{
			$this->_text="";
		}
		else
		{
			$this->_text=$this->ApplyFilter($_x63);
		}
	}
	
	function LoadFormData($_x63)
	{
		if($_x63==null)return;
		$this->set_Text($_x63);
	}
	
	function __set($_x54,$_x64)
	{
		switch($_x54)
		{
			case "ID":
				$this->Name=$_x64;
				return;
			case "TempDirectory":
				$this->Uploader->TempDirectory=$_x64;
				return;
			case "Text":
			case "XHTML":
				$this->set_Text($_x64);
				return;
			
		}
		
		$this->$_x54=$_x64;
	}
	function __get($_x54)
	{
		switch($_x54)
		{
			case "ID":
				return $this->Name;
			case "TempDirectory":
				return $this->Uploader->TempDirectory;
			case "Text":
				return $this->get_Text();
			case "XHTML":
				return $this->get_XHTML();
		}
		
		return $this->$_x54;
	}
	
	
	
	
	var $RTEClient;
	
	function RichTextEditor()
	{
		$this->Uploader=new PhpUploader();
		$this->Uploader->ManualStartUpload=true;
		$this->Uploader->MultipleFilesUpload=true;
		$this->Uploader->AllowedFileExtensions="";	//,bmp
		
		//$this->RTEClient=dirname(dirname(dirname($this->Uploader->ResourceDirectory)))."/";
		$this->RTEClient = URL_ADRESA . 'richtexteditor/richtexteditor/';
		$this->Uploader->LicenseUrl=$this->RTEClient."load.php?type=license&_temp=".time();
	}
	
	function JSEncode($_x65)
	{
		$_x65=str_replace("\\","\\\\",$_x65);
		$_x65=str_replace("\r","\\\r",$_x65);
		$_x65=str_replace("\n","\\\n",$_x65);
		$_x65=str_replace("\"","\\\"",$_x65);
		$_x65=str_replace("'","\\'",$_x65);
		
		return $_x65;
	}
	
	public $TabSpaces;
	public $Width="775px";
	public $Height="320px";
	
	var $_configs=array();
	
	function SetConfig($name,$value)
	{
		$this->_configs[$name]=$value;
	}
	
	
	var $_seclist=array();

	function SetSecurity($category, $storageid, $_x68, $_x69)
	{
		if ($category == null)
			return;
		$category = trim($category);
		if(strlen($category)== 0)
			return;

		if (strpos($category,",")!==false)
		{
			foreach (explode(",",$category) as $_x70)
			{
				$this->SetSecurity($_x70, $storageid, $_x68, $_x69);
			}
			return;
		}

		$_x71 = new RTERuntimeSecurity();
		$_x71->category = $category;
		$_x71->storageid = $storageid;
		$_x71->name = $_x68;
		$_x71->value = $_x69;
		
		array_push($this->_seclist,$_x71);
	}
		
	function LoadConfigFile()
	{
		$_x21 = $this->WebToPhy($this->RTEClient . "config/" . $this->SecurityPolicyFile);
		$_x21 = new RTEConfigFile( $_x21 );
		
		if (count($this->_seclist)==0)
			return $_x21;

		foreach($this->_seclist as $_x71)
		{
			$_x72 = false;
			$_x73 = null;
			foreach ($_x21->_items as $cs)
			{
				if ($_x71->category != "*" && $_x71->category != $cs->Category)
					continue;
				if ($cs->StorageId == null && ($_x73 == null || $_x71->category == $cs->Category))
					$_x73 = $cs;
				if ($_x71->storageid != "*" && $_x71->storageid != $cs->StorageId)
					continue;
				$name=$_x71->name;
				$cs->SetValue($name,$_x71->value);
				$_x72 = true;
			}
			if (!$_x72 && $_x73 != null && $_x71->storageid != "*")
			{
				$cs = $_x73->Clone();
				$cs->StorageId = $_x71->storageid;
				if ($cs->StorageName == null)
					$cs->StorageName = $_x71->storageid;
				array_push($_x21->_items,$cs);
				$name=$_x71->name;
				$cs->$name=$_x71->value;
			}
		}

		return $_x21;
	}
	


	
	function GetInitScriptCode()
	{
		$_x75=array();

		$_x75["servertype"]="PHP";
		
		$_x75["folder"]=$this->RTEClient;
		$_x75["uniqueid"]=$this->Name;
		$_x75["containerid"]=$this->Name."_div";
		
		if($this->UploaderName)
			$_x75["uploaderid"]=$this->UploaderName;
		else
			$_x75["uploaderid"]=$this->Name."_uploader";
		
		$_x75["width"]=$this->Width;
		$_x75["height"]=$this->Height;
		
		$_x75["skin"]=$this->Skin;
		$_x75["toolbar"]=$this->Toolbar;
		$_x75["tabkeyhtml"]=$this->TabSpaces;
		
		$_x75["enabledragdrop"]=$this->EnableDragDrop;
		
		$_x75["contentcss"]=$this->ContentCss;
		$_x75["contentcsstext"]=$this->ContentCssText;
		$_x75["previewcss"]=$this->PreviewCss;
		$_x75["previewcsstext"]=$this->PreviewCssText;
		$_x75["toolbaritems"]=$this->ToolbarItems;
		$_x75["disableditems"]=$this->DisabledItems;
		$_x75["textdirection"]=$this->TextDirection;
		$_x75["urltype"]=$this->URLType;
		$_x75["resize_mode"]=$this->ResizeMode;
		$_x75["paste_default_command"]=$this->PasteMode;
		$_x75["enterkeytag"]=$this->EnterKeyTag;
		$_x75["shiftenterkeytag"]=$this->ShiftEnterKeyTag;
		$_x75["initialtabmode"]=$this->EditorMode;
		$_x75["initialfullscreen"]=$this->FullScreen;
		$_x75["initialtoggleborder"]=$this->ToggleBorder;
		$_x75["readonly"]=$this->ReadOnly;
		$_x75["designdoctype"]=$this->DesignDocType;
		$_x75["savebuttonscript"]=$this->SaveButtonScript;
		$_x75["savebuttonmode"]=$this->SaveButtonMode;
		$_x75["contextmenumode"]=$this->ContextMenuMode;
		$_x75["enablecontextmenu"]=$this->EnableContextMenu;
		$_x75["enableieborderradius"]=$this->EnableIEBorderRadius;
		$_x75["antispamemailencoder"]=$this->EnableAntiSpamEmailEncoder;
		$_x75["enableobjectresizing"]=$this->EnableObjectResizing; 
		$_x75["basehref"]=$this->BaseHref;   
		$_x75["editorbodyid"]=$this->EditorBodyId;   
		$_x75["editorbodyclass"]=$this->EditorBodyClass;   
		$_x75["editorbodystyle"]=$this->EditorBodyStyle;   
		$_x75["disableclasslist"]=$this->DisableClassList;   
		$_x75["autoparseclasses"]=$this->AutoParseClasses;   
		$_x75["maxhtmllength"]=$this->MaxHTMLLength;   
		$_x75["maxtextlength"]=$this->MaxTextLength;   
		$_x75["autofocus"]=$this->AutoFocus;	
		$_x75["showrulers"]=$this->ShowRulers; 
		$_x75["showeditmode"]=$this->ShowEditMode; 
		$_x75["showcodemode"]=$this->ShowCodeMode; 
		$_x75["showpreviewmode"]=$this->ShowPreviewMode; 
		$_x75["showtaglist"]=$this->ShowTagList; 
		$_x75["showzoomview"]=$this->ShowZoomView; 
		$_x75["showstatistics"]=$this->ShowStatistics; 
		$_x75["showresizecorner"]=$this->ShowResizeCorner; 
		$_x75["showbottombar"]=$this->ShowBottomBar; 
		$_x75["showlinkbar"]=$this->ShowLinkbar;  
		$_x75["showtoolbar"]=$this->ShowToolbar;   
		$_x75["showtoolbar_code"]=$this->ShowCodeToolbar;   
		$_x75["showtoolbar_view"]=$this->ShowPreviewToolbar;   
		$_x75["allowscriptcode"]=$this->AllowScriptCode;   
		$_x75["htmlencode"]=$this->UseHTMLEntities;   
		$_x75["editcompletedocument"]=$this->EditCompleteDocument;   
		$_x75["tagwhitelist"]=$this->TagWhiteList;   
		$_x75["tagblacklist"]=$this->TagBlackList;   
		$_x75["attrwhitelist"]=$this->AttrWhiteList;   
		$_x75["attrblacklist"]=$this->AttrBlackList; 
		$_x75["stylewhitelist"]=$this->StyleWhiteList;
		$_x75["styleblacklist"]=$this->StyleBlackList;
		$_x75["ajaxpostbackurl"]=$this->AjaxPostbackUrl;
		
		//ClientFolder 
		$_x76=$this->Language;
		if(!$_x76)
		{
			$_x76=explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$_x76=strtolower($_x76[0]);
		}
		
		//echo $_x76;
		
		$_x77="lang,more";
		
		if(file_exists($this->WebToPhy($this->RTEClient . "lang/more-".$_x76.".js")))
		{
			$_x77="more-".$_x76.",".$_x77;
		}
		if(file_exists($this->WebToPhy($this->RTEClient . "lang/lang-".$_x76.".js")))
		{
			$_x77="lang-".$_x76.",".$_x77;
		}
		
		if(strpos($_x76,"-"))
		{
			$_x76=explode("-",$_x76);
			$_x76=$_x76[0];
						
			if(file_exists($this->WebToPhy($this->RTEClient . "lang/more-".$_x76.".js")))
			{
				$_x77="more-".$_x76.",".$_x77;
			}
			if(file_exists($this->WebToPhy($this->RTEClient . "lang/lang-".$_x76.".js")))
			{
				$_x77="lang-".$_x76.",".$_x77;
			}
			
		}
		
		$_x75["langfiles"]=$_x77;
		
		$_x75["licenseurl"]=$this->Uploader->LicenseUrl;
		$_x75["uploaderresourcehandler"]=$this->RTEClient."server_php/phpuploader/ajaxuploaderhandler.php";
		
				
		foreach($this->_configs as $name=>$value)
		{
			$_x75[$name]=$value;
		}
		
		
		$_x65="window.rteloader=CreateRTELoader(";
		$_x65=$_x65.$this->ToJSON($_x75);
		$_x65=$_x65.");rteloader.startLoadTimer(" . $this->LoadDelay . ");";
		return $_x65;
	}
	
	
	
	
	
	function ToString()
	{
		$_x65="<!--RichTextEditor-->";
		
		$_x65=$_x65."<div id='".$this->Name."_div' style='width:".$this->Width.";height:".$this->Height."'>";
		
		$_x78 = $this->GetInitScriptCode();

		if ($this->RenderSupportAjax)
		{
			$_x78 = "function initcode(){" . $_x78 . "};\r\nif(window.CreateRTELoader) return initcode();"
				. "\r\nvar scripturl='" . $this->JSEncode($this->RTEClient) . "scripts/loader.js?'+new Date().getTime();"
				. <<<STR
var xh=window.XMLHttpRequest?new window.XMLHttpRequest():window.ActiveXObject('Microsoft.XMLHTTP');
xh.onreadystatechange=function()
{
if(xh.readyState<4)return;
xh.onreadystatechange=new Function('','');
if(xh.status==0)return;
if(xh.status!=200)return alert('Failed to load RichTextEditor loader : http '+xh.status+' , '+scripturl);
var runc=new Function('','eval(arguments[0])');
runc(xh.responseText);
initcode();
};
xh.open('GET',scripturl,true);
xh.send('');
STR;

			$_x78 = htmlentities($_x78);
			$_x78=str_replace("'","&#39;",$_x78);
			$_x78=str_replace("\r","",$_x78);
			$_x78=str_replace("\n","",$_x78);

			$_x65=$_x65."<img src='" . htmlentities($this->RTEClient) . "images/zip.gif' onload='this.style.display=&quot;none&quot; ; $_x78' style='position:absolute;' />";
		}
		else
		{
			$_x65=$_x65."<script type='text/javascript' src='" . htmlentities($this->RTEClient) . "scripts/loader.js'></script>";
			$_x65=$_x65."<script type='text/javascript'>$_x78</script>";
		}
		
		
		$_x65=$_x65."</div>";
		//$_x65=$_x65.'<input type="hidden" name="'.$this->Name.'" id="'.$this->Name.'" value="'. htmlentities($this->Text) .'"/>';
		$_x65=$_x65.'<input type="hidden" name="'.$this->Name.'" id="'.$this->Name.'" value="'. htmlspecialchars($this->Text) .'"/>';
		
		return $_x65;
	}

	function Render()
	{
		return $this->ToString();
	}
	function GetString()
	{
		return $this->ToString();
	}
	
	function MvcInit()
	{
		if($this->UploaderName)
			$this->Uploader->Name=$this->UploaderName;
		else
			$this->Uploader->Name=$this->Name."_uploader";
		
		if($this->Uploader->_IsUploadRequest())
		{
			ob_clean();
			$this->Uploader->PreProcessRequest();
			$this->Uploader->WriteValidationOK("");
		}
		else
		{
			if(@$_GET['UseUploadModule']!=null)
				throw (new Exception("Upload request failed for unknown reason."));
			
			$this->ProcessAjaxPostback();
		}
	}
	
	
	
	
	function ApplyFilter($_x79, $_x80="None")
	{
		$_x21 = $this->LoadConfigFile();
		$item = $_x21->GetDefaultItem();

		$_x43 = new RTEFilterEventArgs();
		$_x43->HtmlCode = $_x79;

		if ($this->FilterBegin != null)
			$this->FilterBegin($this, $_x43);

		$_x81 = new RTEFilter();
		$_x81->Option = option;
		$_x81->URLType = $this->URLType;
		$_x81->UseHTMLEntities = $this->UseHTMLEntities;
		$_x81->AllowScriptCode = $this->AllowScriptCode;
		$_x81->EditCompleteDocument = $this->EditCompleteDocument;

		$_x82 = new RTEMatchHandler();
		$_x82->TagWhiteList = RTEMatchList::Parse($this->TagWhiteList, $item->TagWhiteList);
		$_x82->TagBlackList = RTEMatchList::Parse($this->TagBlackList, $item->TagBlackList);
		$_x82->AttrWhiteList = RTEMatchList::Parse($this->AttrWhiteList, $item->AttrWhiteList);
		$_x82->AttrBlackList = RTEMatchList::Parse($this->AttrBlackList, $item->AttrBlackList);
		$_x82->StyleWhiteList = RTEMatchList::Parse($this->StyleWhiteList, $item->StyleWhiteList);
		$_x82->StyleBlackList = RTEMatchList::Parse($this->StyleBlackList, $item->StyleBlackList);
		$_x82->InitFilter($_x81);

		$_x43->HtmlCode = $_x81->Apply($_x43->HtmlCode);

		if ($this->UseSimpleAmpersand)
			$_x43->HtmlCode = str_replace("&amp;","&",$_x43->HtmlCode);

		if ($this->MaxHTMLLength > 0)
		{
			if (strlen($_x43->HtmlCode) > $this->MaxHTMLLength)
				$_x43->HtmlCode = "";
		}
		if ($this->MaxTextLength > 0)
		{
			if (strlen($_x43->HtmlCode) > $this->MaxTextLength && strlen(RTEUtil::ExtractPlainTextWithLinefeedsOutOfHtml($_x43->HtmlCode)) > $this->MaxTextLength)
			{
				$_x43->HtmlCode = "";
			}
		}

		if ($this->FilterEnd != null)
			$this->FilterEnd($this, $_x43);

		return $_x43->HtmlCode;
	}
	
	
	
	
	
	
	
	function InvokeAjaxMethod($_x33)
	{
		switch($_x33->Method)
		{
			case "AjaxGetStorages":
				return $this->AjaxGetStorages($_x33->Arguments[0]);
			case "AjaxGetFolderInfo":
				return $this->AjaxGetFolderInfo($_x33->Arguments[0]);
			case "AjaxGetFolderNodes":
				return $this->AjaxGetFolderNodes($_x33->Arguments[0]);
			case "AjaxGetFolderItem":
				return $this->AjaxGetFolderItem($_x33->Arguments[0],$_x33->Arguments[1]);
			case "AjaxFindPathItem":
				return $this->AjaxFindPathItem($_x33->Arguments[0],$_x33->Arguments[1]);
			
			case "AjaxMoveItems":
				return $this->AjaxMoveItems($_x33->Arguments[0],$_x33->Arguments[1],$_x33->Arguments[2]);
			case "AjaxCopyItems":
				return $this->AjaxCopyItems($_x33->Arguments[0],$_x33->Arguments[1],$_x33->Arguments[2]);
			case "AjaxDeleteItems":
				return $this->AjaxDeleteItems($_x33->Arguments[0],$_x33->Arguments[1]);
			
			case "AjaxCreateFolder":
				return $this->AjaxCreateFolder($_x33->Arguments[0],$_x33->Arguments[1]);
			case "AjaxChangeName":
				return $this->AjaxChangeName($_x33->Arguments[0],$_x33->Arguments[1],$_x33->Arguments[2]);
			
			case "AjaxGetUploaderHTML":
				return $this->AjaxGetUploaderHTML();
			case "AjaxUploadFiles":
				return $this->AjaxUploadFiles($_x33->Arguments[0],$_x33->Arguments[1],$_x33->Arguments[2]);
			
			case "AjaxInitImage":
				return $this->AjaxInitImage($_x33->Arguments[0],$_x33->Arguments[1]);
			case "AjaxCommitImage":
				return $this->AjaxCommitImage($_x33->Arguments[0],$_x33->Arguments[1],$_x33->Arguments[2]);
			case "AjaxSaveImage":
				return $this->AjaxSaveImage($_x33->Arguments[0],$_x33->Arguments[1],$_x33->Arguments[2]);
			case "AjaxLoadTemplate":
				return $this->AjaxLoadTemplate($_x33->Arguments[0],$_x33->Arguments[1]);
			
			default:
				throw(new Exception("Invalid method:$_x33->Method"));
		}
	}
	
	function CreateFileManager($category,$storageid)
	{
		$_x21 = $this->LoadConfigFile();
		$_x71 = null;
		foreach ($_x21->GetAvailableItems($category) as $_x83)
		{
			if ($_x83->StorageId == $storageid)
			{
				$_x71 = $_x83;
				break;
			}
		}

		if ($_x71 == null)
			throw (new Exception("Invalid storage : " . $category . ":" . $storageid));

		$_x84 = new RTEFileManager(new RTEWebFileProvider());
		$_x84->Init($_x71);
		return $_x84;
	}

	
	function CloneFolderArgument($_x24)
	{
		$_x85=new RTEStorage();
		$_x85->CloneFrom($_x24);
		return $_x85;
	}
	function CloneOptionArgument($_x80)
	{
		$_x86=new RTEGetItemOption();
		if($_x80!=null)
			$_x86->CloneFrom($_x80);
		return $_x86;
	}
	
	
	function AjaxGetStorages($category)
	{
		$_x21 = $this->LoadConfigFile();
		$_x87 = $_x21->GetAvailableItems($category);
		$_x47 = array();

		for ($_x12 = 0; $_x12 < count($_x87);$_x12++)
		{
			$_x71 = $_x87[$_x12];
			$_x88 = new RTEStorage();
			$_x88->Category = $category;
			$_x88->UrlPath = "/";
			$_x88->LoadFrom($_x71);

			$_x89 = $this->CreateFileManager($category,$_x71->StorageId);
			$_x88->UrlPrefix = $_x89->GetUrlPrefix($_x88);
			$_x89->Dispose();

			$_x47[$_x12] = $_x88;
		}
		
		return $_x47;
	}
	
	function AjaxGetFolderInfo($_x24)
	{
		$_x24=$this->CloneFolderArgument($_x24);

		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxGetFolderInfo($_x24);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	function AjaxGetFolderNodes($_x24)
	{
		$_x24=$this->CloneFolderArgument($_x24);

		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxGetFolderNodes($_x24);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	
	function AjaxGetFolderItem($_x24,$_x80)
	{
		$_x24=$this->CloneFolderArgument($_x24);
		$_x80=$this->CloneOptionArgument($_x80);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxGetFolderItem($_x24,$_x80);
		
		$_x89->Dispose();
		
		return $_x39;
	}
		
	function AjaxFindPathItem($_x24,$_x23)
	{
		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxFindPathItem($_x24,$_x23);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	
	function AjaxMoveItems($_x24,$_x90,$_x91)
	{
		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxMoveItems($_x24,$_x90,$_x91);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	function AjaxCopyItems($_x24,$_x90,$_x91)
	{
		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxCopyItems($_x24,$_x90,$_x91);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	function AjaxDeleteItems($_x24,$_x91)
	{
		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxDeleteItems($_x24,$_x91);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	
	
	function AjaxCreateFolder($_x24, $_x23)
	{
		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxCreateFolder($_x24,$_x23);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	
	function AjaxChangeName($_x24, $_x92, $_x93)
	{
		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxChangeName($_x24, $_x92, $_x93);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	
	
	function AjaxGetUploaderHTML()
	{
		$_x19=$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
		$_x94=strpos($_x19,"RTEAjaxInvoke");
		if($_x94!==false)
			$_x19=substr($_x19,0,$_x94);

		$this->Uploader->UploadUrl=$_x19;
		return $this->Uploader->GetString();
	}
	
	function AjaxUploadFiles($_x24,$_x80,$_x95)
	{
		$_x96=explode("/",$_POST[$this->Uploader->Name]);
		
		if(count($_x96)==0)
			return array();
		
		$_x97=array();
		
		if($_x95)
		{
			foreach(explode("|",$_x95) as $_x98)
			{
				$_x99=explode("/",$_x98);
				if(count($_x99)!=2)
					continue;
				$_x97[$_x99[0]]=$_x99[1];
			}
		}
		
		$list=array();
		$_x87=array();
		$_x101=0;
		
		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
	
		foreach($_x96 as $_x102)
		{
			$_x103=$this->Uploader->GetUploadedFile($_x102);
			if(!$_x103)
				continue;
			$_x101+=$_x103->FileSize;
			array_push($_x87,$_x103);
		}
		
		$_x89->VerifyStorageSize($_x24,$_x101);
		
		foreach($_x87 as $item)
		{
			$_x23=$item->FileName;
			$_x104=$_x97[strtolower($item->FileGuid)];
			if($_x104&&strlen($_x104)>3)
				$_x23=$_x104;
			
			//TODO:customzie event..
			
			$_x21=$_x89->AjaxCreateFile($_x24, $_x23, $_x80, $this, $item);

			if (!$_x21)
				throw (new Exception("Failed to create file " . $_x23));

			array_push($list,$_x21);
			if(file_exists($item->FilePath))
				$item->Delete();
		}

		$_x89->Maintain($_x24);
		
		$_x89->Dispose();

		return $list;
	}
	
	
	function AjaxInitImage($_x24,$_x23)
	{
		throw(new Exception("Not impl for rtepaint4"));
	}
	
	function AjaxCommitImage($_x24,$_x23,$_x105)
	{
		throw(new Exception("Not impl for rtepaint4"));
	}
	
	function AjaxSaveImage($_x24,$_x23,$_x106)
	{
		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxSaveImage($_x24, $_x23,$_x106);
		
		$_x89->Dispose();
		
		return $_x39;
	}
	
	function AjaxLoadTemplate($_x24,$_x23)
	{
		if ($_x24->Category != "Template")
			throw (new Exception("invalid argument"));

		$_x24=$this->CloneFolderArgument($_x24);
		
		$_x89 = $this->CreateFileManager($_x24->Category,$_x24->ID);
		
		$_x39=$_x89->AjaxLoadData($_x24, $_x23,true);
		
		$_x89->Dispose();
		
		return $_x39;
	}
			
}

class RTERuntimeSecurity
{
	public $category;
	public $storageid;
	public $name;
	public $value;
	public $_x107;
}

class RTEFileManager
{
	var $provider;
	function RTEFileManager($provider)
	{
		$this->provider=$provider;
	}
	var $_sec;
	function Init($_x71)
	{
		$this->_sec=$_x71;
		$this->provider->Init($_x71);
	}
	function Dispose()
	{
		$this->provider->Dispose();
	}
	
	function GetUrlPrefix($_x109)
	{
		return $this->provider->GetUrlPrefix($_x109);
	}
	
	function GetSecurity($_x24)
	{
		if($this->_sec->StorageId==$_x24->ID)
				return $this->_sec;
		throw (new Exception("Invalid storage id : " . $_x24->ID));
	}
	
	function GetFolderId($_x24)
	{
		if($_x24 == null)
			throw(new Exception("folder null"));

		$_x110 = $this->provider->GetRootId($_x24);

		$_x111 = explode("/",str_replace("\\","/",$_x24->UrlPath));
		if(count($_x111)==0)
			return $_x110;
		foreach($_x111 as $_x112)
		{
			if(!$_x112||$_x112=="")
				continue;
			if(strlen($_x112)==0)
				continue;
			if ($_x112 == "_cache")
				return null;
			if($_x112=="..")
				$_x110 = $this->provider->GetParentId($_x110);
			else
				$_x110 = $this->provider->HasFolder($_x110, $_x112);
		}
		
		return $_x110;
	}
	
	function Maintain($_x24)
	{
		$_x110 = $this->GetFolderId($_x24);
		if(!$_x110)
			return;
		$this->MaintainFolder($_x110);
	}
	function MaintainFolder($_x110)
	{
		/*
		Hashtable ht = LoadCache(fid, CACHE_FOLDER_NAME . "_image.cache");
		if (ht == null)
			return;
		Hashtable files = (Hashtable)ht["files"];
		if (files == null)
			return;
		int removecount = 0;
		foreach (string filename in new ArrayList(files.Keys))
		{
			PathItem item = $this->provider->GetItem(fid, new RTEGetItemOption(), filename);
			if ($item == null)
			{
				files.Remove($_x23);
				removecount++;
			}
		}
		SaveCache(fid, CACHE_FOLDER_NAME . "_image.cache", ht);
		*/
	}
	
	function VerifyStorageSize($_x24, $_x113)
	{
		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		if ($_x114->MaxFolderSize <= 0)
			return;
		if ($_x113 < $_x114->MaxFolderSize * 1024)
		{
			$_x115 = $this->provider->CalcStorageSize($_x24);
			if ($_x113 + $_x115 < $_x114->MaxFolderSize * 1024)
				return;
		}
		throw (new Exception("ERROR:MaxFolderSize: is " . $_x114->MaxFolderSize . " KB"));
	}

	function AjaxGetFolderInfo($_x24)
	{
		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x116 = new CuteSoftLibrary_Dynamic();
		$_x116->FolderSize = $this->provider->CalcStorageSize($_x24);
		$_x116->Extensions = $_x114->Extensions;
		$_x116->MimeTypes = $_x114->MimeTypes;
		$_x116->LargeImageMode = $_x114->LargeImageMode;
		$_x116->MaxFileSize = $_x114->MaxFileSize;
		$_x116->MaxFolderSize = $_x114->MaxFolderSize;
		$_x116->MaxImageWidth = $_x114->MaxImageWidth;
		$_x116->MaxImageHeight = $_x114->MaxImageHeight;
		return $_x116;
	}
	
	function AjaxGetFolderNodes($_x24)
	{
		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;

		return $this->provider->GetFolderNodes($_x110);
	}
	
	function AjaxFindPathItem($_x24,$_x23)
	{
		$this->ValidateFileName($_x23);

		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;

		return $this->provider->GetItem($_x110, new RTEGetItemOption(), $_x23);
	}
	
	function AjaxGetFolderItem($_x24,$_x80)
	{
		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;
		
		$_x117=new RTEFolderItem();
		$_x117->UrlPath=$this->provider->GetUrlPath($_x110);
		$_x117->UrlPrefix = $this->provider->GetUrlPrefix($_x24);
		$list=array();
		
		if ($this->provider->GetParentId($_x110) != null)
		{
			$_x118 = new RTEPathItem();
			$_x118->IsFolder = true;
			$_x118->Name = "..";
			$_x118->Size = 0;
			array_push($list,$_x118);
		}

		$_x81 = new RTEFileFilter($_x24->Extensions);
		foreach($this->provider->GetFolders($_x110, $_x80) as $item)
		{
			if($item->Name=="_cache")
				continue;
			array_push($list,$item);
		}
		
		foreach($this->ApplyFileOptionArray($_x24, $_x110, $_x80, $this->provider->GetFiles($_x110, $_x80)) as $item)
		{
			array_push($list,$item);
		}
		$_x117->Items=$list;
		return $_x117;
	}
	
	function AjaxChangeName($_x24, $_x92, $_x93)
	{
		$this->ValidateFileName($_x92);
		$this->ValidateFileName($_x93);
		
		if(pathinfo($_x92,PATHINFO_EXTENSION)!=pathinfo($_x93,PATHINFO_EXTENSION))
			throw (new Exception("ERROR:InvalidExt:Invalid file extension:" . newname));

		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;

		$item = $this->provider->GetItem($_x110, new RTEGetItemOption(), $_x92);
		if ($item == null)
			return null;

		if ($item->IsFolder)
		{
			if (!$_x114->AllowRenameFolder)
				throw (new Exception("ERROR:AllowRenameFolder: is false"));

			$_x114->ValidateFolderName($_x93);

			$this->provider->ChangeFolderName($_x110, $_x92, $_x93);
		}
		else
		{
			if (!$_x114->AllowRenameFile)
				throw (new Exception("ERROR:AllowRenameFile: is false"));

			$_x114->ValidateFileName($_x93);

			$this->provider->ChangeFileName($_x110, $_x92, $_x93);
		}

		$this->MaintainFolder($_x110);

		return $_x93;
	}
	
	function AjaxCreateFolder($_x24, $_x23)
	{
		$this->ValidateFileName($_x23);
		
		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		if (!$_x114->AllowCreateFolder)
			throw (new Exception("ERROR:AllowCreateFolder: is false"));

		$_x114->ValidateFolderName($_x23);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;

		$this->provider->CreateFolder($_x110, $_x23);
		return $this->provider->GetItem($_x110, new RTEGetItemOption(), $_x23);
	}
	
	
	function AjaxCreateFile($_x24, $_x23, $_x80, $_x119, $_x103)
	{
		$this->ValidateFileName($_x23);

		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		if (!$_x114->AllowUpload)
			throw (new Exception("ERROR:AllowUpload: is false"));

		$_x114->ValidateFileName($_x23);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;

		if (!$_x114->AllowOverride)
		{
			if ($this->provider->GetItem($_x110, new RTEGetItemOption(), $_x23) != null)
			{
				throw (new Exception("ERROR:AllowOverride: is false"));
			}
		}

		$_x120="." . strtolower(pathinfo($_x23,PATHINFO_EXTENSION));
		$_x121 = false;
		foreach(explode(",", strtolower($_x114->Extensions)) as $_x122)
		{
			$_x22=str_replace("*","",$_x122);
			if(substr($_x22,0,1)!=".")$_x22=".".$_x22;
			if($_x120==$_x22)
			{
				$_x121=true;
				break;
			}
		}

		if (!$_x121)
			throw (new Exception("ERROR:InvalidExt:Don't allow upload extension '" . $_x120 . "'."));
		
		$_x123=false;
		switch($_x120)
		{
			case ".png":
			case ".jpg":
			case ".jpeg":
			case ".gif":
			case ".bmp":
				$_x123=true;
				break;
		}
		
		if($_x123&& ($_x114->LargeImageMode=="deny"||$_x114->LargeImageMode=="resize") )
		{
			$_x59=null;
			if($_x114->MaxImageWidth&&$_x114->MaxImageWidth>0)
				$_x59=$_x114->MaxImageWidth;
			$_x60=null;
			if($_x114->MaxImageHeight&&$_x114->MaxImageHeight>0)
				$_x60=$_x114->MaxImageHeight;
			if( $_x59 || $_x60 )
			{
				$_x124=false;
				$_x57=RTE_GetPhotoDimensions($_x103->FilePath);
				if($_x59&&$_x57["Width"]>$_x59)
					$_x124="width";
				if($_x60&&$_x57["Height"]>$_x60)
					$_x124="height";
				if($_x124&&$_x114->LargeImageMode=="deny")
				{
					if($_x124=="width")
						throw (new Exception("ERROR:MaxImageWidth: is $_x59"));
					else
						throw (new Exception("ERROR:MaxImageHeight: is $_x60"));
				}
				if($_x124&&$_x114->LargeImageMode=="resize")
				{
					$_x125=$_x59?$_x59:88888888;
					$_x126=$_x60?$_x60:88888888;
					$_x127=min($_x125/$_x57["Width"],$_x126/$_x57["Height"]);
					$_x125=floor($_x127*$_x57["Width"]);
					$_x126=floor($_x127*$_x57["Height"]);
					RTE_GenerateThumbnail($_x103->FilePath,$_x103->FilePath,$_x125,$_x126);
				}
			}
		}
		
		$this->provider->CreateFile($_x110, $_x23, $_x103);

		return $this->ApplyFileOptionSingle($_x24, $_x110, $_x80, $this->provider->GetItem($_x110, $_x80, $_x23));
	}
	
	function AjaxDeleteItems($_x24,$_x91)
	{
		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;


		foreach ($_x91 as $name)
		{
			if (substr($name,strlen($name)-1,1) == ".")
				continue;

			$item = $this->provider->GetItem($_x110, new RTEGetItemOption(), $name);
			if ($item == null)
				continue;
			
			if ($item->IsFolder)
			{
				if (!$_x114->AllowDeleteFolder)
					throw (new Exception("ERROR:AllowDeleteFolder: is false"));

				$this->provider->DeleteFolder($_x110, $name);
			}
			else
			{
				if (!$_x114->AllowDeleteFile)
					throw (new Exception("ERROR:AllowDeleteFile: is false"));

				$this->provider->DeleteFile($_x110, $name);
			}
		}

		$this->MaintainFolder($_x110);
	}
	
	function AjaxMoveItems($_x24,$_x90,$_x91)
	{
		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;
		
		$_x128 = $this->GetFolderId($_x90);
		if ($_x128 == null)
			return null;
		
		foreach($_x91 as $name)
		{
			if (substr($name,strlen($name)-1,1) == ".")
				continue;
			
			$item = $this->provider->GetItem($_x110, new RTEGetItemOption(), $name);
			if ($item == null)
				continue;

			if (!$_x114->AllowOverride)
			{
				if ($this->provider->GetItem($_x128, new RTEGetItemOption(), $name) != null)
				{
					throw (new Exception("ERROR:AllowOverride: is false"));
				}
			}

			if ($item->IsFolder)
			{
				if (!$_x114->AllowMoveFolder)
					throw (new Exception("ERROR:AllowMoveFolder: is false"));

				$this->provider->MoveFolder($_x110, $_x128, $item->Name);
			}
			else
			{
				if (!$_x114->AllowMoveFile)
					throw (new Exception("ERROR:AllowMoveFile: is false"));

				$this->provider->MoveFile($_x110, $_x128, $item->Name);
			}
		}

		$this->MaintainFolder($_x110);
	}
	
	function AjaxCopyItems($_x24,$_x90,$_x91)
	{
		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;
		
		$_x128 = $this->GetFolderId($_x90);
		if ($_x128 == null)
			return null;
		
		foreach($_x91 as $name)
		{
			if (substr($name,strlen($name)-1,1) == ".")
				continue;
			
			$item = $this->provider->GetItem($_x110, new RTEGetItemOption(), $name);
			if ($item == null)
				continue;

			if (!$_x114->AllowOverride)
			{
				if ($this->provider->GetItem($_x128, new RTEGetItemOption(), $name) != null)
				{
					throw (new Exception("ERROR:AllowOverride: is false"));
				}
			}

			if ($item->IsFolder)
			{
				if (!$_x114->AllowCopyFolder)
					throw (new Exception("ERROR:AllowCopyFolder: is false"));

				$this->provider->CopyFolder($_x110, $_x128, $item->Name);
			}
			else
			{
				if (!$_x114->AllowCopyFile)
					throw (new Exception("ERROR:AllowCopyFile: is false"));

				$this->provider->CopyFile($_x110, $_x128, $item->Name);
			}
		}

		$this->MaintainFolder($_x110);
	}
	
	
	function ValidateFileName($_x23)
	{
		if($_x23==null||strlen($_x23)==0)throw(new Exception("empty filename $_x23"));
		if(strpos($_x23,"/")!==false||strpos($_x23,"\\")!==false)throw(new Exception("invalid filename (1)"));
		if(substr($_x23,strlen($_x23)-1,1)==".")throw(new Exception("invalid filename (2)"));
	}
	function ApplyFileOptionArray($_x24,$_x110,$_x80,$_x87)
	{
		return $_x87;
	}
	function ApplyFileOptionSingle($_x24,$_x110,$_x80,$item)
	{
		return $item;
	}
	
	function AjaxLoadData($_x24, $_x23,$_x129)
	{
		$this->ValidateFileName($_x23);

		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;

		$item = $this->provider->GetItem($_x110, new RTEGetItemOption(), $_x23);
		if ($item == null)
			return null;

		if ($item->IsFolder)
			return null;
		
		return $this->provider->LoadFile($_x110,$_x23,$_x129);
	}
	

	
	function AjaxSaveImage($_x24,$_x23,$_x106)
	{
		$this->ValidateFileName($_x23);

		$_x114 = $this->GetSecurity($_x24);
		$_x24->LoadFrom($_x114);

		$_x114->ValidateFileName($_x23);
		
		$_x110 = $this->GetFolderId($_x24);
		if ($_x110 == null)
			return null;
		
		$item = $this->provider->GetItem($_x110, new RTEGetItemOption(), $_x23);
		if ($item == null)
			return null;

		if (!$_x114->AllowUpload)
			throw (new Exception("ERROR:AllowUpload: is false"));

		if (!$_x114->AllowOverride)
			throw (new Exception("ERROR:AllowOverride: is false"));


		$_x120="." . strtolower(pathinfo($_x23,PATHINFO_EXTENSION));
		$_x121 = false;
		foreach(explode(",", strtolower($_x114->Extensions)) as $_x122)
		{
			$_x22=str_replace("*","",$_x122);
			if(substr($_x22,0,1)!=".")$_x22=".".$_x22;
			if($_x120==$_x22)
			{
				$_x121=true;
				break;
			}
		}

		if (!$_x121)
			throw (new Exception("ERROR:InvalidExt:Don't allow upload extension '" . $_x120 . "'."));
		
		$_x123=false;
		switch($_x120)
		{
			case ".jpeg":
			case ".jpg":
			case ".png":
			case ".gif":
			case ".bmp":
				$_x123 = true;
				break;
		}
		
		if (!$_x123)
			return null;
			
		$_x130=base64_decode($_x106);
		
		$this->provider->WriteFileData($_x110, $_x23, $_x130);

		return $this->ApplyFileOptionSingle($_x24, $_x110, new RTEGetItemOption(), $this->provider->GetItem($_x110, $_x80, $_x23));
		
		
	}
	
}

function Path_TrimEndSeparator($_x17)
{
	$_x131=substr($_x17,strlen($_x17)-1,1);
	if($_x131=="/"||$_x131=="\\")
		$_x17=substr($_x17,0,strlen($_x17)-1);
	return $_x17;
}


class RTEWebFileProvider
{
	public $cs;
	var $_sec;
	var $_webpath;
	var $_phypath;
	
	function RTEWebFileProvider()
	{
		$this->cs=new CuteSoftLibrary();
	}
	
	function Init($_x71)
	{
		$this->_sec=$_x71;
		$this->_webpath=$this->cs->MakeAbsolute($_x71->StoragePath)."/";
		$this->_phypath=$this->cs->WebToPhy($this->_webpath);
	}
	function Dispose()
	{
	}
	
	function GetZoneWebPath($_x132)
	{
		if($this->_sec->StorageId==$_x132)
			return $this->_webpath;
		throw (new Exception("Invalid zone"));
	}
	function GetZonePhyPath($_x132)
	{
		if($this->_sec->StorageId==$_x132)
			return $this->_phypath;
		throw (new Exception("Invalid zone"));
	}
	
	function GetRootId($_x133)
	{
		return new RTEFolderID($_x133->ID, $this->GetZonePhyPath($_x133->ID), $_x133->Category, $_x133->Extensions);
	}


	function GetUrlPath($_x134)
	{
		$_x135=$this->GetZonePhyPath($_x134->ZoneID);
		$_x17=substr($_x134->Value,strlen($this->_phypath)-1);
		return str_replace("\\","/",$_x17);
	}
	
	function GetUrlPrefix($_x133)
	{
		$_x17=$this->GetZoneWebPath($_x133->ID);
		$_x17=str_replace("\\","/",$_x17);
		return Path_TrimEndSeparator($_x17);
	}
	
	function CalcStorageSize($_x133)
	{
		$_x135=$this->GetZonePhyPath($_x133->ID);
		return $this->CalcDirectorySize(Path_TrimEndSeparator($_x135));
	}
	
	function CalcDirectorySize($_x17)
	{
		if(!is_dir($_x17))
			return 0;
		$_x57=0;
		foreach(glob($_x17."/*.*") as $_x136)
		{
			if(substr($_x136,strlen($_x136)-1,1)==".")
				continue;
			if(is_dir($_x136))
				$_x57+=$this->CalcDirectorySize($_x17);
			else
				$_x57+=filesize($_x136);
		}
		return $_x57;
	}

	function GetParentId($_x134)
	{
		$_x135 = $this->GetZonePhyPath($_x134->ZoneID);
		$_x17 = Path_TrimEndSeparator($_x134->Value);

		$_x137=strrpos($_x17,"\\");
		$_x138=strrpos($_x17,"/");
		if($_x137==false)$_x137=$_x138;
		else if($_x138!=false)$_x137=max($_x137,$_x138);
		if($_x137==false)return null;
		
		$_x17 = substr($_x17,0,$_x137 + 1);
	
		$_x94=strpos($_x17,$_x135);
		
		if($_x94!==0)
			return null;
		
		return new RTEFolderID($_x134->ZoneID,$_x17,$_x134->Category,$_x134->Extensions);

	}
	function HasFolder($_x134, $_x139)
	{
		$_x140=$_x134->Value.$_x139;
		if(!is_dir($_x140))
			return null;
		$_x17=$_x140."/";
		return new RTEFolderID($_x134->ZoneID,$_x17,$_x134->Category,$_x134->Extensions);
	}
	
	function GetFolderNodes($_x134)
	{
		$_x87=$this->GetFolders($_x134, new RTEGetItemOption());
		$_x141=array();
		foreach($_x87 as $item)
		{
			$_x45=new RTEFolderNode();
			$_x45->Name=$item->Name;
			array_push($_x141,$_x45);
			$_x140=$this->HasFolder($_x134,$item->Name);
			if($_x140)
				$_x45->SubNodes=$this->GetFolderNodes($_x140);
		}
		return $_x141;
	}
	
	function GetFolders($_x134, $_x80)
	{
		if (!is_dir($_x134->Value))
			return array();

		$list = array();
		foreach(scandir($_x134->Value) as $name)
		{
			if($name=="."||$name=="..")
				continue;
			$_x142=$_x134->Value.$name;
			if(!is_dir($_x142))
				continue;
			$item=$this->GetFolderItem($_x142, $_x134->Filter, $_x80);
			if($this->IsHiddenItem($item))
				continue;
			array_push($list,$item);
		}

		return $list;
	}
	function GetFiles($_x134, $_x80)
	{
		if (!is_dir($_x134->Value))
			return array();

		$_x143 = $_x134->Filter->GetFiles($_x134->Value);
		$list = array();
		foreach($_x143 as $_x142)
		{
			$item=$this->GetFileItem($_x80,$_x142);
			if($this->IsHiddenItem($item))
				continue;
			array_push($list,$item);
		}
		return $list;
	}
	
	function IsHiddenItem($item)
	{
		if ($item->Name == ".."||$item->Name == ".")
			return true;
		if ($item->IsFolder && $item->Name=="_cache")
			return true;
		return false;
	}

	function GetItem($_x134, $_x80, $name)
	{
		if ($name == ".."||$name == ".")
			return null;

		$_x142 = $_x134->Value . $name;
		
		if (is_dir($_x142))
			return $this->GetFolderItem($_x142, $_x134->Filter, $_x80);
		if (file_exists($_x142))
			return $this->GetFileItem($_x80, $_x142);
		return null;
	}

	function GetFileItem($_x80, $_x142)
	{
		$item = new RTEPathItem();
		$item->IsFolder = false;
		$item->Name = $this->cs->GetBaseName($_x142);
		$_x116 = null;
		if ($_x80->GetSize || $_x80->GetTime)
		{
			if ($_x80->GetSize)
				$item->Size=filesize($_x142);
			else
				$item->Size = -1;
			if ($_x80->GetTime)
				$item->Time=filectime($_x142)*1000;
		}
		return $item;
	}


	function GetFolderItem($_x142, $_x81, $_x80)
	{
		$item = new RTEPathItem();
		$item->IsFolder = true;
		$item->Name = $this->cs->GetBaseName($_x142);
		$item->Size = -1;
		if ($_x80->GetSize)
		{
			$item->Size = 0;
			/*
			try
			{
				$item->Size = Directory.GetFiles(fullname).Length; // $_x81->GetFiles(fullname).Count;
				foreach (string subdir in SafeGetDirectories(fullname))
				{
					if (subdir.EndsWith(RTEFileManager.CACHE_FOLDER_NAME))
					{
						string n = Path.GetFileName(subdir);
						if (n == RTEFileManager.CACHE_FOLDER_NAME)
							continue;
					}
					$item->Size++;
				}
			}
			catch
			{
			}
			*/
		}
		return $item;
	}
	
	
	function LoadFile($_x134, $_x23,$_x129)
	{
		$_x142 = $_x134->Value . $_x23;

		if (!file_exists($_x142))
			throw (new Exception($_x23 . " does not exist"));

		$_x57=filesize($_x142);
		$_x144=get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$_x145="rb";
		if($_x129)
			$_x145="rt";
		$_x146=fopen($_x142,$_x145);
		$_x130=fread($_x146,$_x57);
		fclose($_x146);
		set_magic_quotes_runtime($_x144);
		return $_x130;
	}
	

	function WriteFileData($_x134, $_x23, $_x130)
	{
		$_x142 = $_x134->Value . $_x23;

		if (is_dir($_x142))
			throw (new Exception($_x23 . " is a folder"));

		if (!is_dir($_x134->Value))
			mkdir($_x134->Value,0777);

		$_x126=PhpUploader_FileOpen(__FILE__,__LINE__,$_x142,"w+b");
		PhpUploader_FileWrite(__FILE__,__LINE__,$_x126,$_x130);
		PhpUploader_FileClose(__FILE__,__LINE__,$_x126);
	}
	function CreateFile($_x134, $_x23, $_x103)
	{
		$_x142 = $_x134->Value . $_x23;

		if (is_dir($_x142))
			throw (new Exception($_x23 . " is a folder"));

		if (!is_dir($_x134->Value))
			mkdir($_x134->Value,0777);

		$_x103->MoveTo($_x142);
	}
	function CreateFolder($_x134, $_x23)
	{
		$_x142 = $_x134->Value . $_x23;
		if (file_exists($_x142))
			throw (new Exception($_x23 . " is a file"));
			
		$item = $this->GetItem($_x134, new RTEGetItemOption(), $_x23);
		if ($item != null)
			return;
		mkdir($_x142,0777);
	}
	function DeleteFile($_x134, $_x23)
	{
		$_x142 = $_x134->Value . $_x23;
		if (file_exists($_x142))
			unlink($_x142);
	}
	function DeleteFolder($_x134,  $_x23)
	{
		$_x142 = $_x134->Value . $_x23;
		$this->cs->DeleteDirectory($_x142);
	}
	function ChangeFileName($_x134, $_x23, $_x93)
	{
		$_x147 = $_x134->Value . $_x23;
		$_x148 = $_x134->Value . $_x93;
		rename($_x147, $_x148);
	}
	function ChangeFolderName($_x134,  $_x23, $_x93)
	{
		$_x147 = $_x134->Value . $_x23;
		$_x148 = $_x134->Value + newname;
		$this->Directory_Move(fullname1, fullname2, $_x134->Filter);
	}
	function MoveFile($_x134, $_x149, $_x23)
	{
		$_x147 = $_x134->Value . $_x23;
		$_x148 = $_x149->Value . $_x23;
		$this->File_Move($_x147, $_x148);
	}
	function MoveFolder($_x134,  $_x149, $_x23)
	{
		$_x147 = $_x134->Value . $_x23;
		$_x148 = $_x149->Value . $_x23;
		$this->Directory_Move($_x147, $_x148, $_x134->Filter);
	}

	function CopyFile($_x134, $_x149, $_x23)
	{
		$_x147 = $_x134->Value . $_x23;
		$_x148 = $_x149->Value . $_x23;
		$this->File_Copy($_x147, $_x148);
	}
	function CopyFolder($_x134,  $_x149, $_x23)
	{
		$_x147 = $_x134->Value . $_x23;
		$_x148 = $_x149->Value . $_x23;
		$this->Directory_Copy($_x147, $_x148, $_x134->Filter);
	}
	
	function Directory_Copy($_x150, $_x151, $_x81)
	{
		if(!is_dir($_x151))
			mkdir($_x151,0777);
		
		foreach(glob($_x150."/*.*") as $item)
		{
			$name=basename($item);
			if(is_dir($item))
			{
				$this->Directory_Copy($item,$_x151."/".$name,$_x81);
			}
			else
			{
				$this->File_Copy($item,$_x151."/".$name);
			}
		}
	}
	function Directory_Move($_x150, $_x151, $_x81)
	{
		if(!is_dir($_x151))
			mkdir($_x151,0777);

		foreach(glob($_x150."/*.*") as $item)
		{
			$name=basename($item);
			if(is_dir($item))
			{
				$this->Directory_Move($item,$_x151."/".$name,$_x81);
			}
			else
			{
				$this->File_Move($item,$_x151."/".$name);
			}
		}
	}
	
	function File_Copy($_x150, $_x151)
	{
		if (!file_exists($_x150)) return;
		if(file_exists($_x151))
			unlink($_x151);
		copy($_x150,$_x151);
	}
	
	function File_Move($_x150, $_x151)
	{
		if (!file_exists($_x150)) return;
		if(file_exists($_x151))
			unlink($_x151);
		rename($_x150,$_x151);
	}
}




class RTEFileFilter
{
	var $_exts;
	function RTEFileFilter($_x152)
	{
		if($_x152)
			$this->_exts=explode(",",str_replace(".","",str_replace("*","",strtolower($_x152))));
		else
			$this->_exts=array();
	}
	function Match($_x23)
	{
		if(count($this->_exts)==0)
			return true;
		
		$_x22=strtolower(pathinfo($_x23,PATHINFO_EXTENSION));
		foreach($this->_exts as $_x122)
		{
			if($_x122==$_x22)
				return true;
		}
		return false;
	}

	function GetFiles($_x20)
	{
		$_x20=Path_TrimEndSeparator($_x20);
		$_x25="*.*";
		$_x26=glob("$_x20/*.*");
		if(!$_x26)return array();
		$list=array();
		foreach($_x26 as $_x21)
		{
			if($this->Match($_x21))
				array_push($list,$_x21);
		}
		return $list;
	}

	//public static FileFilter All = new FileFilter(null);
	//public static FileFilter Image = new FileFilter(".bmp,.jpg,.jpeg,.gif,.png".Split(','));


}
class RTEGetItemOption
{	
	public $GetSize;
	public $GetTime;
	public $GetDimensions;
	public $GetThumbnails;
	
	function CloneFrom($_x80)
	{
		$this->GetSize = $_x80->GetSize;
		$this->GetTime = $_x80->GetTime;
		$this->GetDimensions = $_x80->GetDimensions;
		$this->GetThumbnails = $_x80->GetThumbnails;
	}
}

class RTEStorage
{
	public $ID;
	public $Name;
	public $Category;
	
	public $UrlPath;
	public $UrlPrefix;
	
	public $Extensions;
	public $MimeTypes;
	public $AllowUpload;
	public $AllowCopyFile;
	public $AllowRenameFile;
	public $AllowDeleteFile;
	public $AllowOverride;
	public $AllowCreateFolder;
	public $AllowCopyFolder;
	public $AllowMoveFolder;
	public $AllowRenameFolder;
	public $AllowDeleteFolder;
	public $MaxFolderSize;
	public $MaxImageWidth;
	public $MaxImageHeight;	

	function LoadFrom($_x71)
	{
		$this->ID = $_x71->StorageId;
		$this->Name = $_x71->StorageName;
		
		$this->CloneAttributes($_x71);
	}
	function CloneFrom($_x88)
	{
		$this->ID = $_x88->ID;
		$this->Name = $_x88->Name;
		$this->Category = $_x88->Category;
		$this->UrlPath = $_x88->UrlPath;
		$this->UrlPrefix = $_x88->UrlPrefix;
	
		$this->CloneAttributes($_x88);
	}
	function CloneAttributes($_x88)
	{
		$this->Extensions = $_x88->Extensions;
		$this->MimeTypes = $_x88->MimeTypes;
		$this->AllowUpload = $_x88->AllowUpload;
		$this->AllowCopyFile = $_x88->AllowCopyFile;
		$this->AllowMoveFile = $_x88->AllowMoveFile;
		$this->AllowRenameFile = $_x88->AllowRenameFile;
		$this->AllowDeleteFile = $_x88->AllowDeleteFile;
		$this->AllowOverride = $_x88->AllowOverride;
		$this->AllowCreateFolder = $_x88->AllowCreateFolder;
		$this->AllowCopyFolder = $_x88->AllowCopyFolder;
		$this->AllowMoveFolder = $_x88->AllowMoveFolder;
		$this->AllowRenameFolder = $_x88->AllowRenameFolder;
		$this->AllowDeleteFolder = $_x88->AllowDeleteFolder;
		$this->MaxFolderSize = $_x88->MaxFolderSize;
		$this->MaxImageWidth = $_x88->MaxImageWidth;
		$this->MaxImageHeight = $_x88->MaxImageHeight;
	}
}
class RTEFolderItem extends RTEStorage
{
	public $Items;
}

class RTEFolderInfo
{
	public $FolderSize;
	public $Extensions;
	public $MimeTypes;
	public $LargeImageMode;
	public $MaxFileSize;
	public $MaxFolderSize;
	public $MaxImageWidth;
	public $MaxImageHeight;
}

class RTEFolderNode
{
	public $Name;
	public $SubNodes;
}

class RTEPathItem
{
	public $IsFolder;
	public $Name;
	public $Size;
	public $Time;
	public $Width;
	public $Height;
	public $Thumbnail;

	function IsImage()
	{
		$_x22=".".strtolower(pathinfo($this->Name,PATHINFO_EXTENSION));
		switch($_x22)
		{
			case ".jpeg":
			case ".jpg":
			case ".gif":
			case ".png":
			case ".bmp":
				return true;
		}
		return false;
	}
}

class RTEFolderID
{
	public $ZoneID;
	public $Value;
	public $Category;
	public $Extensions;
	public $Filter;
	
	function RTEFolderID($_x132, $_x63, $category,$_x152)
	{
		if ($_x132 == null)
			throw (new Exception("zoneid is null"));
		if ($_x63 == null)
			throw (new Exception("val is null"));

		$this->ZoneID = $_x132;
		$this->Value = $_x63;
		$this->Category = $category;
		$this->Extensions = $_x152;
		$this->Filter=new RTEFileFilter($_x152);
	}
}



class RTEConfigFile
{
	public $doc;
	public $_items=array();
	
	function RTEConfigFile($_x21)
	{
		array_push($this->_items,new RTEConfigSecurity());
		
		$this->doc=new DOMDocument();
		//$this->doc->load($_x21);
		
		$_x153=$this->doc->childNodes->item(0);
		$_x141=$_x153->childNodes;
		for($_x12=0;$_x12<$_x141->length;$_x12++)
		{
			$_x45=$_x141->item($_x12);
			if($_x45->nodeType!=1)continue;
			if($_x45->nodeName=="watermarks")
			{
				$this->ParseWatermarks($_x45, $this->_items);
				continue;
			}
			if($_x45->nodeName=="security")
			{
				$this->ParseSecurity($_x45, $this->_items);
				continue;
			}
			if($_x45->nodeName=="category")
			{
				$this->ParseCategory($_x45);
				continue;
			}
			throw (new Exception("Invalid element '$_x45->nodeName'"));
				
		}
	}
	
	function GetDefaultItem()
	{
		return $this->_items[0];
	}
	
	function ParseCategory($element)
	{
		$_x154 = $element->getAttribute("for");

		if ( !$_x154 || $_x154 == "*")
			throw (new Exception("<category> node must specify attribute 'for'"));

		$list = array();
	
		foreach (explode(",",$_x154) as $category)
		{
			if(!$category)
				continue;
			$item = $this->FindItem($category, null);
			if (!$item)
			{
				$item = $this->_items[0]->DoClone();
				$item->Category = $category;
				array_push($this->_items,$item);
			}
			array_push($list,$item);
		}

		$_x155 = $list;
	
		$_x141=$element->childNodes;
		for($_x12=0;$_x12<$_x141->length;$_x12++)
		{
			$_x136=$_x141->item($_x12);
			if($_x136->nodeType!=1)continue;

			if ($_x136->nodeName == "security")
			{
				$this->ParseSecurity($_x136, $_x155);
				continue;
			}
			if ($_x136->nodeName == "storage")
			{
				$this->ParseStorage($_x136, $_x155);
				continue;
			}
			throw (new Exception("Invalid element '" .$_x136->nodeName."' under '" . $element->nodeName . "'"));
		}
	
		
	}
	function ParseSecurity($element,$_x155)
	{
		$_x54 = $element->getAttribute("name");
		foreach($_x155 as $_x71)
			$_x71->$_x54=$element->textContent;
	}
	
	function ParseStorage($element, $_x156)
	{
		$_x157 = $element->getAttribute("id");

		if (!$_x157)
			throw (new Exception("<storage> node missing attribute 'id'"));

		$_x155 = array();
		for ($_x12 = 0; $_x12 < count($_x156); $_x12++)
		{
			$_x71=$this->FindItem($_x156[$_x12]->Category, $_x157);
			if (!$_x71)
			{
				$_x71 = $_x156[$_x12]->DoClone();
				$_x71->StorageId = $_x157;
				$_x71->StorageName = $_x157;
				array_push($this->_items,$_x71);
				
			}
			$_x155[$_x12] = $_x71;
		}

		$_x141=$element->childNodes;
		for($_x12=0;$_x12<$_x141->length;$_x12++)
		{
			$_x136=$_x141->item($_x12);
			if($_x136->nodeType!=1)continue;
			
			if ($_x136->nodeName == "security")
			{
				$this->ParseSecurity($_x136, $_x155);
				continue;
			}
			throw (new Exception("Invalid element '" .$_x136->nodeName. "' under '" .$element->nodeName. "'"));
		}
	}
		
	function ParseWatermarks()
	{
	}
	
	function FindItem($category, $storageid)
	{
		foreach ($this->_items as $item)
		{
			if ( $item->Category == $category &&  $item->StorageId == $storageid)
				return $item;
		}
		return null;
	}
	
	function GetAvailableItems($category)
	{
		return $this->GetItems($category,false,false);
	}
	function GetItems($category,$_x158,$_x159)
	{
		$list = array();
		foreach ($this->_items as $item)
		{
			if ($item->Category != $category)
				continue;
			if ($item->AllowAccess == false && !$_x158)
				continue;
			if ($_x159||$item->StorageId != null)
			{
				array_push($list,$item);
			}
		}
		return $list;
	}

}

class RTEConfigSecurity
{
	function RTEConfigSecurity()
	{
		$this->AllowAccess=true;
	}
	
	function DoClone()
	{
		
		$_x71=new RTEConfigSecurity();
		foreach($this as $_x31=>$_x32)
		{
			$_x71->$_x31=$_x32;
		}
		return $_x71;
	}
	
	function __set($_x54,$_x64)
	{
		$this->SetValue($_x54,$_x64);
	}
	function SetValue($_x54,$_x64)
	{
		switch($_x54)
		{
			case "DrawWatermarks":
			case "AllowAccess":
			case "AllowUpload":
			case "AllowCopyFile":
			case "AllowMoveFile":
			case "AllowRenameFile":
			case "AllowDeleteFile":
			case "AllowOverride":
			case "AllowCreateFolder":
			case "AllowCopyFolder":
			case "AllowMoveFolder":
			case "AllowRenameFolder":
			case "AllowDeleteFolder":
				switch(strtolower($_x64))
				{
					case "true":
					case "1":
					case "yes":
						$_x64=true;
						break;
					default:
						$_x64=false;
						break;
				}
			default:
				break;
		}
		
		$this->$_x54=$_x64;
	}
	function __get($_x54)
	{
		return $this->$_x54;
	}
	
	function ValidateFileName($name)
	{
		if(!$this->FilePattern)
			return;
		if(preg_match("/".str_replace("\\u","\\x",$this->FilePattern)."/",$name)==0)
			throw (new Exception("ERROR:FilePattern:"));
	}
	function ValidateFolderName($name)
	{
		if(!$this->FolderPattern)
			return;
		if(preg_match("/".str_replace("\\u","\\x",$this->FolderPattern)."/",$name)==0)
			throw (new Exception("ERROR:FilePattern:"));
	}

}

function rtefilter_strpos($_x65,$_x160,$_x94=null)
{
	$_x94=strpos($_x65,$_x160,$_x94);
	if($_x94===false)
		return -1;
	return $_x94;
}

class RTEFilterEventArgs
{
	public $HtmlCode;
}

class RTEFilter
{
	public $Option = "None";
	public $AllowScriptCode = false;
	public $EditCompleteDocument = false;
	public $CheckTag;
	public $CheckAttr;
	public $CheckStyle;
	public $UseHTMLEntities = true;
	public $URLType = "Default";
	

	function Apply($_x79)
	{
		if ($_x79 == null)
			return "";

		$_x161 = $this->ParseHtmlCode($_x79);

		$this->BuildRelation($_x161);

		if (!$this->AllowScriptCode) 
			$this->RemoveScriptCode($_x161);

		if (!$this->EditCompleteDocument)
			$this->RemoveOuterCode($_x161);

		if ($this->CheckTag != null||$this->CheckAttr != null||$this->CheckStyle != null)
			$this->DoCheckTagAttrStyle($_x161);
		if ($this->URLType != "Default")
			$this->DoFixURLType($_x161);

		if ($this->UseHTMLEntities)
			$this->DoEncodeToEntity($_x161);

		if ($this->Option == "HTML2BBCode")
		{
			return RTEBBCodeConverter::HTML2BBCodeForNode($_x161);
		}

		return $this->Render($_x161);
	}


	function DoFixURLType($_x161)
	{
		$_x162 = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
		$_x163 = $_x162.'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

		$_x164=explode('/',rawurl);
		
		$_x165=implode('/',array_splice($_x164,0,3));
		$_x166 = implode('/',array_splice($_x164,0,count($_x164)-1))."/";
		
		$_x167=strlen($_x165);

		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if($_x45->NodeType!="Element")
				continue;
				
			for($_x168=$_x45->element->HeadAttribute;$_x168!=null;$_x168= $_x168 == null ? $element->HeadAttribute : $_x168->NextNode )
			{
				$_x63 = $_x168->Value;

				if (RTEUtil::IsNullOrEmpty(val))
					continue;

				if ($_x168->NameLower != "src" && $_x168->NameLower != "href")
					continue;

				if ($this->URLType == "SiteRelative")
				{
					if (rtefilter_strpos($_x63,"://") == -1)
						continue;
					if(strlen($_x63)<$_x167+1)
						continue;
					if(substr($_x63,$_x167,1)!="/")
						continue;
					if(substr($_x63,0,$_x167)!=$_x165)
						continue;
					$_x168->Value = $_x169($_x63,$_x167);
				}
				else if ($this->URLType == "Absolute")
				{
					if (rtefilter_strpos($_x63,"://") != -1)
						continue;
					if ($_x169($_x63,0,1) == '/')
						$_x168->Value = $_x165 . $_x63;
					else
						$_x168->Value = $_x166 . $_x63;
				}

			}
		}
	}

	function DoCheckTagAttrStyle($_x161)
	{

		$_x170 = false;
		if ($this->CheckAttr != null) $_x170 = true;
		else if ($this->CheckStyle != null) $_x170 = true;

		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if($_x45->NodeType!="Element")
				continue;
			
			if ($this->CheckTag != null && !$this->CheckTag->CheckTag($_x45->NameLower))
			{
				$element=$_x45;
				$_x45 = $_x45->PrevNode;
				$element->Remove($element->IsTSS());
				continue;
			}

			if (!$_x170)
				continue;

			for ($_x168 = $_x45->HeadAttribute; $_x168 != null; $_x168 = $_x168 == null ? $_x45->HeadAttribute : $_x168->NextNode)
			{
				if ($this->CheckAttr!=null&&!$this->CheckAttr->CheckAttr($_x45->NameLower, $_x168->NameLower, $_x168->ValueLower))
				{
					$_x168 = $_x168->PrevNode;
					$_x45->RemoveAttribute($_x168);
					continue;
				}
				if ($this->CheckStyle != null && $_x168->NameLower == "style")
				{
					$_x111=explode(";",$_x168->Value);
					$_x171 = false;
					for ($_x12 = 0; $_x12 < count(parts); $_x12++)
					{
						$_x112 = trim($_x111[i]);
						if (strlen($_x112).Length == 0)
						{
							$_x111[i] = null;
							continue;
						}
						$_x99=explode(":",$_x112,2);
						if (count($_x99)==1)
						{
							$_x111[i] = null;
							$_x171 = true;
							continue;
						}
						$_x172 = strtolower(trim($_x99[0]));
						$_x173 = strtolower(trim($_x99[1]));
						if (strlen($_x172) == 0 || strlen($_x173) == 0)
						{
							$_x111[i] = null;
							$_x171 = true;
							continue;
						}
						if (!$this->CheckStyle->CheckStyle($_x45->NameLower, $_x172, $_x173))
						{
							$_x111[i] = null;
							$_x171 = true;
							continue;
						}
					}
					if ($_x171)
					{
						$_x174 = "";
						foreach ($_x111 as $_x112)
						{
							if(RTEUtil::IsNullOrEmpty($_x112))
								continue;
							if(strlen($_x174)==0)
								$_x174=$_x112;
							else
								$_x174=$_x174.";".$_x112;
						}
						$_x168->Value = $_x174;
					}
				}
			}

		}
	}

	function DoEncodeToEntity($_x161)
	{
		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if($_x45->NodeType=="Element")
			{
				if($_x45->NameLower=="script")
					continue;
			}
			$_x45->EncodeToEntity();
		}
	}

	function RemoveOuterCode($_x161)
	{
		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if($_x45->NodeType!="Element")
				continue;

			switch ($_x45->NameLower)
			{
				case "html":
				case "body":
					$element=$_x45;
					$_x45 = $_x45->PrevNode;
					$element->Remove(false);
					break;
				case "!doctype":
				case "head":
				case "title":
				case "meta":
				case "base":
				case "basefont":
					$element=$_x45;
					$_x45 = $_x45->PrevNode;
					$element->Remove(true);
					break;
			}
		}
	}

	function RemoveScriptCode($_x161)
	{
		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if($_x45->NodeType!="Element")
				continue;

			if ($_x45->NameLower == "script" || $_x45->NameLower == "link")
			{
				$_x175=$_x45;
				$_x45 = $_x45->PrevNode;
				$_x175->Remove(true);
				continue;
			}

			if ($_x45->NameLower == "style")
			{
				if ($_x45->EndTag == null)
				{
					$element=$_x45;
					$_x45 = $_x45->PrevNode;
					$element->Remove(true);
					continue;
				}

				//TODO: parse into InnerText , check whether its has @import

				$element=$_x45;
				$_x45 = $_x45->PrevNode;
				$element->Remove(true);
				continue;

			}

			for ($_x168 = $_x45->HeadAttribute; $_x168 != null; $_x168 = $_x168 == null ? $_x45->HeadAttribute : $_x168->NextNode)
			{
				if (substr($_x168->NameLower,0,1) == 'o' && substr($_x168->NameLower,1,1) == 'n')
				{
					$_x168=$_x168->PrevNode;
					$_x45->RemoveAttribute($_x168);
					continue;
				}
				if ($_x168->Value == null)
					continue;

				//TODO: make better
				if (rtefilter_strpos($_x168->ValueLower,"javascript:") != -1)
				{
					$_x168 = $_x168->PrevNode;
					$_x45->RemoveAttribute($_x168);
					continue;
				}
				if ($_x168->NameLower == "style")
				{
					//TODO: make better
					if (rtefilter_strpos($_x168->ValueLower,"behavior")!= -1 || rtefilter_strpos($_x168->ValueLower,"expression") != -1)
					{
						$_x168 = $_x168->PrevNode;
						$_x45->RemoveAttribute($_x168);
						continue;
					}
				}
			}

		}
	}


	function Render($_x161)
	{
		$_x174 = new RTEStringBuilder();
		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
			$_x45->WriteHtmlCode($_x174);
		return $_x174->ToString();
	}

	function BuildRelation($_x161)
	{
		$_x176 = array();
		$_x177=0;
		
		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if ($_x177 > 0)
			{
				$_x45->Parent = $_x176[$_x177-1];
			}
			
			if($_x45->NodeType!="Element")
				continue;

			if ($_x45->IsEndTag)
			{
				while ($_x177 > 0)
				{
					$_x178 = $_x176[$_x177-1];
					$_x177--;
					array_pop($_x176);
					if ($_x178->NameLower == $_x45->NameLower)
					{
						$_x178->EndTag = $_x45;
						break;
					}
				}
			}
			else if (!$_x45->IsClosed)
			{
				array_push($_x176,$_x45);
				$_x177++;
			}
		}
	}

	function ParseHtmlCode($_x79)
	{
		$_x161 = new RTEFilterNode();
		$_x179 = $_x161;

		$_x15 = strlen($_x79);
		$_x94 = 0;

		while ($_x94<$_x15)
		{

			$_x180 = rtefilter_strpos($_x79,"<",$_x94);
			if ($_x180 == -1 || $_x180 == $_x15 - 1)
			{
				$_x179=$this->InsertNode(new RTEFilterText(substr($_x79,$_x94, $_x15 - $_x94)), $_x179);
				break;
			}

			if ($_x180 > $_x94)
			{
				$_x179=$this->InsertNode(new RTEFilterText(substr($_x79,$_x94, $_x180 - $_x94)), $_x179);
				$_x94 = $_x180;
			}

			$_x181 = substr($_x79,$_x180+1,1);

			if ($_x181 != '/')
			{
				if ($_x181 == '!')
				{
					if ( $_x180 + 3 < $_x15 && substr($_x79,$_x180 + 2,1) != '-' && substr($_x79,$_x180 + 3,1) != '-')
					{
						$_x182 = rtefilter_strpos($_x79,"-->", $_x180 + 3);
						if ($_x182 != -1)
							$_x182 = $_x182 + 3;
						else
							$_x182 = $_x15;
						$_x179=$this->InsertNode(new RTEFilterComment(substr($_x79,$_x180, $_x182 - $_x180)), $_x179);
						$_x94 = $_x182;
						continue;
					}
				}
				else if ($_x181 == '%' || $_x181 == '?')
				{
					$_x182 = $_x180 + 3 >= $_x15 ? -1 : substr($_x79,$_x181 . ">", $_x180 + 3);
					if ($_x182 != -1)
						$_x182 = $_x182 + 2;
					else
						$_x182 = $_x15;
					$_x179=$this->InsertNode(new RTEFilterOhter(substr($_x79, $_x180, $_x182 - $_x180)), $_x179);
					$_x94 = $_x182;
					continue;
				}
				else if (!RTEUtil::IsLetter($_x181))
				{
					$_x45 = new RTEFilterText("<" . $_x181);
					$_x179=$this->InsertNode($_x45, $_x179);
					$_x94 = $_x180 + 2;
					continue;
				}
			}

			$_x182 = rtefilter_strpos($_x79,'>', $_x180 + 1);
			if ($_x182 == -1)
			{
				$_x179=$this->InsertNode(new RTEFilterText(substr($_x79,$_x94, $_x15 - $_x94)), $_x179);
				break;
			}
			
			$_x182++;

			$element=new RTEFilterElement(substr($_x79,$_x94, $_x182 - $_x94));
			if ($element->IsValid)
			{
				$_x179=$this->InsertNode($element, $_x179);
			}

			$_x94 = $_x182;
		}

		$_x179->NextNode = new RTEFilterNode();
		$_x179->NextNode->PrevNode = $_x179;

		return $_x161;
	}

	function InsertNode($_x45, $_x179)
	{
		$_x179->NextNode = $_x45;
		$_x45->PrevNode = $_x179;
		return $_x45;
	}
	

}

class RTEFilterNode
{
	public $NodeType="Node";
	public $RawCode;
	public $PrevNode;
	public $NextNode;
	public $Parent;
	public $IsModified = false;

	function WriteHtmlCode($_x183)
	{
		$_x183->Append($this->RawCode);
	}
	function EncodeToEntity()
	{
	}

	function ParseNodeName($_x29, $_x184)
	{
		$_x137 = $_x184;
		$_x185=strlen($_x29);
		for (; $_x137 < $_x185; $_x137++)
		{
			$_x30=substr($_x29,$_x137,1);
			if ($_x30=='!'||$_x30 == ':' || $_x30 == '-' || $_x30 == '_')
				continue;
			$_x186=ord($_x30);
			if($_x186>=48&&$_x186<58)
				continue;
			if($_x186>=65&&$_x186<91)
				continue;
			if($_x186>=97&&$_x186<123)
				continue;
			break;
		}
		if ($_x137 > $_x184)
			return substr($_x29,$_x184,$_x137-$_x184);
		return null;
	}
	function SkipWhiteSpace($_x29, $_x184)
	{
		$_x185=strlen($_x29);
		while ($_x184 < $_x185)
		{
			$_x30=substr($_x29,$_x184,1);
			if($_x30==' '||$_x30=='\r'||$_x30=='\n'||$_x30=='\t')
			{
				$_x184++;
			}
			else
			{
				return $_x184;
			}
		}
		return $_x184;
	}
	function HtmlDecode($_x29)
	{
		if ($_x29 == null) 
			return "";
		return html_entity_decode($_x29);
	}


	function HtmlEncode($_x29)
	{
		if ($_x29 == null) 
			return "";
		$_x29=htmlentities($_x29);
		$_x29=str_replace("&#160;", "&nbsp;",$_x29);
		$_x29=str_replace("'", "&apos;",$_x29);
		return $_x29;
	}
}

class RTEFilterText extends RTEFilterNode
{
	public $NewCode = null;
	function RTEFilterText($_x29)
	{
		$this->NodeType="Text";

		$this->RawCode = $_x29;
	}

	function WriteHtmlCode($_x183)
	{
		if ($this->IsModified)
			$_x183->Append($this->NewCode);
		else
			$_x183->Append($this->RawCode);
	}
	function EncodeToEntity()
	{
		$_x29 = $this->HtmlEncode($this->HtmlDecode($this->RawCode));
		if ($_x29 != $this->RawCode)
		{
			$this->IsModified = true;
			$this->NewCode = $_x29;
		}
	}
}
class RTEFilterComment extends RTEFilterNode
{
	function RTEFilterComment($_x29)
	{
		$this->NodeType="Comment";
		$this->RawCode = $_x29;
	}
}
class RTEFilterOhter extends RTEFilterNode
{
	function RTEFilterOhter($_x29)
	{
		$this->NodeType="Other";
		$this->RawCode = $_x29;
	}
}
class RTEFilterElement extends RTEFilterNode
{
	function RTEFilterElement($_x29)
	{
		$this->NodeType="Element";
		
		$this->RawCode = $_x29;

		$_x15 = strlen($_x29);

		$_x181 = substr($_x29,1,1);
		if ($_x181 == '/')
			$this->IsEndTag = true;
		
		$this->Name = $this->ParseNodeName($_x29, $this->IsEndTag ? 2 : 1);
		if ($this->Name == null)
			return;
		$this->NameLower = strtolower($this->Name);

		$_x187=strlen($this->Name) + ($this->IsEndTag ? 2 : 1);
		$_x94 = $_x187;

		while($_x94 < $_x15)
		{
			$_x181 = substr($_x29,$_x94,1);

			if($_x181==' '||$_x181=='\r'||$_x181=='\n'||$_x181=='\t')
			{
				$_x94++;
				continue;
			}


			if ($_x181 == '>')
			{
				$this->IsValid = true;
				return;
			}

			if ($_x181 == '/')
			{
				if ($_x94 == $_x15 - 2 && !$this->IsEndTag)
				{
					$this->IsClosed = true;
					$this->IsValid = true;
				}
				return;
			}

			if ($_x94 == $_x187)
				return;//invalid

			$_x188 = $this->ParseNodeName($_x29, $_x94);
			if ($_x188 == null)
				return;//not valid

			$_x189 = $_x94;
			
			$_x94 += strlen($_x188);

			$_x94=$this->SkipWhiteSpace($_x29, $_x94);

			$_x181 = substr($_x29,$_x94,1);
			
			if ($_x181 != '=')
			{
				//attribute without value
				$this->AddAttribute(new RTEFilterAttribute(substr($_x29,$_x189,$_x94-$_x189),$_x188));
				continue;
			}
			
			$_x94++;

			//attribute with value
			$_x94=$this->SkipWhiteSpace($_x29, $_x94);

			$_x181 = substr($_x29,$_x94,1);
			if ($_x181 == '>')
			{
				$this->AddAttribute(new RTEFilterAttribute(substr($_x29,$_x189,$_x94-$_x189),$_x188));
				break;
			}

			if ($_x181 == '"' || $_x181 == '\'')
			{
				$_x94++;
				$_x190 = rtefilter_strpos($_x29,$_x181,$_x94);
				if ($_x190 == -1)
					return;//invalid
				$_x191 = new RTEFilterAttribute(substr($_x29,$_x189, $_x190 + 1 - $_x189), $_x188);
				$_x191->Quote = $_x181;
				$_x191->SetValueCode(substr($_x29,$_x94, $_x190 - $_x94));
				$this->AddAttribute($_x191);
				$_x94 = $_x190 + 1;
			}
			else
			{
				$_x190 = $_x94+1;
				for (; $_x190 < $_x15;$_x190++ )
				{
					$_x181 = substr($_x29,$_x190,1);
					if ($_x181 == '>')
						break;
					if ($_x181 == '/' && $_x190 == $_x15 - 2)
						break;
					if($_x181==' '||$_x181=='\r'||$_x181=='\n'||$_x181=='\t')
						break;
				}
				$_x191 = new RTEFilterAttribute(substr($_x29,$_x189, $_x190 - $_x189), $_x188);
				$_x191->SetValueCode( substr($_x29,$_x94, $_x190 - $_x94) );
				$this->AddAttribute($_x191);
				$_x94 = $_x190;
			}

		}

		$this->IsValid = true;
	}

	function Remove($_x192)
	{
		$_x193 = $this->PrevNode;
		$_x194 = $this->NextNode;

		if ($this->EndTag != null)
		{
			if ($_x192)
				$_x194 = $this->EndTag->NextNode;
			else
				$this->EndTag->Remove(false);
		}

		if ($_x193 != null) $_x193->NextNode = $_x194;
		if ($_x194 != null) $_x194->PrevNode = $_x193;
	}

	function GetAttribute($_x172)
	{
		for ($_x191 = $this->HeadAttribute; $_x191 != null; $_x191 = $_x191->NextNode)
		{
			if ($_x191->NameLower == $_x172)
				return $_x191->Value;
		}
		return null;
	}

	function RemoveAttribute($_x191)
	{
		$_x193 = $_x191->PrevNode;
		$_x194 = $_x191->NextNode;

		if ($_x193 != null)
			$_x193->NextNode = $_x194;
		else
			$this->HeadAttribute = $_x194;
		if ($_x194 != null)
			$_x194->PrevNode = $_x193;
		else
			$this->LastAttribute = $_x193;

		$this->IsModified = true;
	}

	function EncodeToEntity()
	{
		for ($_x191 = $this->HeadAttribute; $_x191 != null; $_x191 = $_x191->NextNode)
		{
			$_x191->EncodeToEntity();
		}
	}

	function AddAttribute($_x191)
	{
		$_x191->Parent = $this;
		if ($this->HeadAttribute == null)
		{
			$this->HeadAttribute = $_x191;
			$this->LastAttribute = $_x191;
			return;
		}
		$_x191->PrevNode = $this->LastAttribute;
		$this->LastAttribute->NextNode = $_x191;
		$this->LastAttribute = $_x191;
	}

	function WriteHtmlCode($_x183)
	{
		if (!$this->IsModified)
		{
			$_x183->Append($this->RawCode);
			return;
		}

		if ($this->IsEndTag)
		{
			$_x183->Append("</");
			$_x183->Append($this->Name);
			$_x183->Append(">");
			return;
		}
		$_x183->Append("<");
		$_x183->Append($this->Name);

		for ($_x191 = $this->HeadAttribute; $_x191 != null; $_x191 = $_x191->NextNode)
		{
			$_x183->Append(" ");
			$_x191->WriteHtmlCode($_x183);
		}

		if ($this->IsClosed)
			$_x183->Append("/");
		$_x183->Append(">");
	}

	public $Name;
	public $NameLower;
	public $IsValid = false;
	public $EndTag;
	public $IsEndTag = false;
	public $IsClosed = false;
	public $HeadAttribute;
	public $LastAttribute;

	public $BBCodeEndTagCode;

	function IsTSS()
	{
		switch ($this->NameLower)
		{
			case "script":
			case "style":
			case "textarea":
				return true;
		}
		return false;
	}
}

class RTEFilterAttribute extends RTEFilterNode
{
	function RTEFilterAttribute($_x29, $_x188)
	{
		$this->NodeType="Attribute";
		
		$this->RawCode = $_x29;
		$this->Name = $_x188;
		$this->NameLower = strtolower($_x188);
	}

	function WriteHtmlCode($_x183)
	{
		if (!$this->IsModified)
		{
			$_x183->Append($this->RawCode);
			return;
		}

		$_x183->Append($this->Name);
		if ($this->_value == null && $this->_valcode == null)
			return;
		$_x183->Append("=");
		if ($this->Quote != null)
			$_x183->Append($this->Quote);
		if ($this->_valcode != null)
			$_x183->Append($this->_valcode);
		else
			$_x183->Append($this->HtmlEncode($this->_value));
		if ($this->Quote != null)
			$_x183->Append($this->Quote);
	}

	function EncodeToEntity()
	{
		if($this->_valcode==null)
			return;
		$this->_valuelower = null;
		$this->_value = $this->HtmlDecode($this->_valcode);
		$_x195 = $this->HtmlEncode($this->_value);
		if ($_x195 == $this->_valcode)
			return;
		$this->_valcode = null;
		$this->IsModified = true;
		if ($this->Parent != null) $this->Parent->IsModified = true;
	}

	public $Name;
	public $NameLower; 
	public $Quote; 
	private $_valcode;
	private $_value;
	private $_valuelower;
	
	function __set($_x54,$_x64)
	{
		switch($_x54)
		{
			case "Value":
				$this->_value = $_x64;
				$this->_valcode = null;
				$this->_valuelower = null;
				$this->IsModified = true;
				if($this->Parent!=null)$this->Parent->IsModified = true;
				return;
		}
		
		$this->$_x54=$_x64;
	}
	function __get($_x54)
	{
		switch($_x54)
		{
			case "ValueLower":
				if($this->_valuelower!=null)
					return $this->_valuelower;
				if ($this->Value == null)
					return null;
				$this->_valuelower = strtolower($this->_value);
				return $this->_valuelower;
			case "Value":
				if ($this->_value != null)
					return $this->_value;
				if ($this->_valcode == null)
					return null;
				$this->_value = $this->HtmlDecode($this->_valcode);
				return $this->_value;
		}
		
		return $this->$_x54;
	}
	

	function SetValueCode($_x196)
	{
		$this->_value = null;
		$this->_valcode = $_x196;
		$this->_valuelower = null;
	}
}



class RTEBBCodeNode
{
	public $Name;//null means text!
	public $Value;
	public $PrevNode;
	public $NextNode;
	public $EndTag;
	public $Parent;

	function __get($_x54)
	{
		switch($_x54)
		{
			case "IsEndTag":
				if (RTEUtil::IsNullOrEmpty($this->Name))
					return false;
				if (substr($this->Name,0,1) == '/')
					return true;
				return false;
		}
	}
	
	public $IsClosed = false;

	static function CreateText($_x197)
	{
		$_x45 = new RTEBBCodeNode();
		$_x45->Value = $_x197;
		return $_x45;
	}
	static function CreateTag($name, $_x63)
	{
		$_x45 = new RTEBBCodeNode();
		$_x45->Name = $name;
		$_x45->Value = $_x63;
		return $_x45;
	}

	function GetInnerText()
	{
		$_x174 = new RTEStringBuilder();
		for ($_x45 = $this->NextNode; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if ($_x45->Name != null)
				break;
			if ($_x45->Value != null)
				$_x174->Append($_x45->Value);
		}
		return $_x174->ToString();
	}
}

class RTEBBCodeConverter
{
	static function HTML2BBCode($_x65)
	{
		$_x81=new RTEFilter();
		$_x81->Option="HTML2BBCode";
		return $_x81->Apply($_x65);
	}
	
	static function HTML2BBCodeForNode($_x161)
	{
		$_x198=new RTEBBCodeConverter();
		return $_x198->HTML2BBCodeForNodeInstance($_x161);
	}
	function HTML2BBCodeForNodeInstance($_x161)
	{
		$_x174 = new RTEStringBuilder();
		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if($_x45->NodeType=="Text")
			{
				$_x174->Append(html_entity_decode($_x45->IsModified?$_x45->NewCode:$_x45->RawCode));
				continue;
			}
			
			if($_x45->NodeType!="Element")
				continue;
			
			if ($_x45->IsEndTag)
			{
				$_x174->Append($_x45->BBCodeEndTagCode);
				continue;
			}
			
			if ($_x45->IsClosed)
			{
				switch ($_x45->NameLower)
				{
					case "br":
					case "hr":
						$_x174->Append("\r\n");
						break;
					case "img":
						$_x150 = $_x45->GetAttribute("src");
						if (RTEUtil::IsNullOrEmpty($_x150))
							break;
						$_x174->Append("[img]");
						$_x174->Append($_x150);
						$_x174->Append("[/img]");
						break;
				}
				continue;
			}

			if ($_x45->EndTag == null)
				continue;

			switch ($_x45->NameLower)
			{
				case "font":
					$_x199 = $_x45->GetAttribute("name");
					if (!RTEUtil::IsNullOrEmpty($_x199))
					{
						$_x174->Append("[face=" . $_x199 . "]");
						$_x45->EndTag->BBCodeEndTagCode = "[/face]" . $_x45->EndTag->BBCodeEndTagCode;
					}
					$_x57 = $_x45->GetAttribute("size");
					if (!RTEUtil::IsNullOrEmpty($_x57))
					{
						$_x174->Append("[size=" . $_x57 . "]");
						$_x45->EndTag->BBCodeEndTagCode = "[/size]" . $_x45->EndTag->BBCodeEndTagCode;
					}
					break;
				case "strike":
				case "s":
					$_x174->Append("[s]");
					$_x45->EndTag->BBCodeEndTagCode = "[/s]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "u":
					$_x174->Append("[u]");
					$_x45->EndTag->BBCodeEndTagCode = "[/u]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "em":
				case "i":
					$_x174->Append("[i]");
					$_x45->EndTag->BBCodeEndTagCode = "[/i]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "strong":
				case "b":
					$_x174->Append("[b]");
					$_x45->EndTag->BBCodeEndTagCode = "[/b]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "sup":
					$_x174->Append("[sup]");
					$_x45->EndTag->BBCodeEndTagCode = "[/sup]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "sub":
					$_x174->Append("[sub]");
					$_x45->EndTag->BBCodeEndTagCode = "[/sub]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "h1":
				case "h2":
				case "h3":
				case "h4":
				case "h5":
				case "h6":
					$_x174->Append("[h=" . $_x45->NameLower .Substring(1) . "]");
					$_x45->EndTag->BBCodeEndTagCode = "[/h]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "pre":
					$_x174->Append("[pre]");
					$_x45->EndTag->BBCodeEndTagCode = "[/pre]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "p":
				case "div":
				case "fieldset":
					$_x174->Append("[p]");
					$_x45->EndTag->BBCodeEndTagCode = "[/p]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "legend":
					$_x45->EndTag->BBCodeEndTagCode = "\r\n" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "blockquote":
					$_x174->Append("[quote]");
					$_x45->EndTag->BBCodeEndTagCode = "[/quote]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "table":
				case "tr":
				case "td":
					$_x174->Append("[" . $_x45->NameLower . "]");
					$_x45->EndTag->BBCodeEndTagCode = "[/" . $_x45->NameLower . "]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "th":
					$_x174->Append("[td]");
					$_x45->EndTag->BBCodeEndTagCode = "[/td]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "a":
					$_x200 = $_x45->GetAttribute("href");
					if (RTEUtil::IsNullOrEmpty(href))
						break; ;
					$_x174->Append("[url=" . $_x200 . "]");
					$_x45->EndTag->BBCodeEndTagCode = "[/url]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "textarea":
					$_x174->Append("[code]");
					$_x45->EndTag->BBCodeEndTagCode = "[/code]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "ul":
					$_x174->Append("[list]");
					$_x45->EndTag->BBCodeEndTagCode = "[/list]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "ol":
					$_x174->Append("[list=1]");
					$_x45->EndTag->BBCodeEndTagCode = "[/list]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
				case "li":
					$_x174->Append("[*]");
					break;
				case "center":
					$_x174->Append("[align=center]");
					$_x45->EndTag->BBCodeEndTagCode = "[/align]" . $_x45->EndTag->BBCodeEndTagCode;
					break;
			}

			$_x201 = $_x45->GetAttribute("style");

			if (RTEUtil::IsNullOrEmpty($_x201))
				continue;
			
			foreach (explode(";",$_x201) as $_x202)
			{
				$_x94 = rtefilter_strpos($_x202,":");
				if ($_x94 == -1)
					continue;

				$value = trim(substr($_x202,$_x94 + 1));
				if (strlen($value)== 0 || rtefilter_strpos($value,'[') != -1 || rtefilter_strpos($value,']') != -1)
					continue;

				$_x203 = strtolower(trim(substr($_x202,0, $_x94)));

				switch ($_x203)
				{
					case "vertical-align":
						if (strtolower($value) == "sub")
						{
							$_x174->Append("[sub]");
							$_x45->EndTag->BBCodeEndTagCode = "[/sub]" . $_x45->EndTag->BBCodeEndTagCode;
						}
						if (strtolower($value) == "super")
						{
							$_x174->Append("[sup]");
							$_x45->EndTag->BBCodeEndTagCode = "[/sup]" . $_x45->EndTag->BBCodeEndTagCode;
						}
						break;
					case "text-align":
						$_x174->Append("[align=" . $value . "]");
						$_x45->EndTag->BBCodeEndTagCode = "[/align]" . $_x45->EndTag->BBCodeEndTagCode;
						break;
					case "font-weight":
						if (strtolower($value) == "bold")
						{
							$_x174->Append("[b]");
							$_x45->EndTag->BBCodeEndTagCode = "[/b]" . $_x45->EndTag->BBCodeEndTagCode;
						}
						break;
					case "font-style":
						if (strtolower($value) == "italic")
						{
							$_x174->Append("[i]");
							$_x45->EndTag->BBCodeEndTagCode = "[/i]" . $_x45->EndTag->BBCodeEndTagCode;
						}
						break;
					case "text-decoration":
						if (rtefilter_strpos(strtolower($value),"underline")!=-1)
						{
							$_x174->Append("[u]");
							$_x45->EndTag->BBCodeEndTagCode = "[/u]" . $_x45->EndTag->BBCodeEndTagCode;
						}
						if (rtefilter_strpos(strtolower($value),"line-through")!=-1)
						{
							$_x174->Append("[s]");
							$_x45->EndTag->BBCodeEndTagCode = "[/s]" . $_x45->EndTag->BBCodeEndTagCode;
						}
						break;
					case "font-famliy":
						$_x174->Append("[face=" . $value . "]");
						$_x45->EndTag->BBCodeEndTagCode = "[/face]" . $_x45->EndTag->BBCodeEndTagCode;
						break;
					case "font-size":
						$_x174->Append("[size=" . $value . "]");
						$_x45->EndTag->BBCodeEndTagCode = "[/size]" . $_x45->EndTag->BBCodeEndTagCode;
						break;
					case "color":
						$_x174->Append("[color=" . $value . "]");
						$_x45->EndTag->BBCodeEndTagCode = "[/color]" . $_x45->EndTag->BBCodeEndTagCode;
						break;
				}
			}
		}
		return $_x174->ToString();
	}
	static function BBCode2HTML($_x29)
	{
		$_x198=new RTEBBCodeConverter();
		return $_x198->BBCode2HTMLInstance($_x29);
	}
	function BBCode2HTMLInstance($_x29)
	{
		if ($_x29 == null)
			return "";
		
		$_x174 = new RTEStringBuilder();
		$_x161 = $this->ParseBBCode($_x29);
		$this->BuildRelation($_x161);

		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if ($_x45->Name == null)
			{
				if ($_x45->Value != null)
				{
					$_x174->Append(str_replace("\n","<br/>",str_replace("\r","",htmlentities($_x45->Value))));
				}
				continue;
			}
			switch (strtolower($_x45->Name))
			{
				case "*":
					$_x174->Append("<li>");
					break;
				case "list":
					$_x174->Append("<ol>");
					break;
				case "/list":
					$_x174->Append("</ol>");
					break;
				case "sub":
					$_x174->Append("<sub>");
					break;
				case "/sub":
					$_x174->Append("</sub>");
					break;
				case "sup":
					$_x174->Append("<sup>");
					break;
				case "/sup":
					$_x174->Append("</sup>");
					break;
				case "s":
					$_x174->Append("<s>");
					break;
				case "/s":
					$_x174->Append("</s>");
					break;
				case "u":
					$_x174->Append("<u>");
					break;
				case "/u":
					$_x174->Append("</u>");
					break;
				case "i":
					$_x174->Append("<i>");
					break;
				case "/i":
					$_x174->Append("</i>");
					break;
				case "b":
					$_x174->Append("<b>");
					break;
				case "/b":
					$_x174->Append("</b>");
					break;
				case "h":
					if ($_x45->EndTag == null)
						break;
					$_x45->EndTag->Value = $_x45->Value;
					$_x174->Append("<h" . $_x45->Value . ">");
					break;
				case "/h":
					if ($_x45->Value == null)
						break;
					$_x174->Append("</h" . $_x45->Value . ">");
					break;
				case "img":
					$_x174->Append("<img src='");
					if ($_x45->Value!=null)
					{
						$_x174->Append(HttpUtility.HtmlEncode($_x45->Value));
						$_x174->Append("'/>");
					}
					break;
				case "/img":
					$_x174->Append("'/>");
					break;
				default:
					$_x174->Append("[");
					$_x174->Append($_x45->Name);
					$_x174->Append("]");
					break;
				case "url":
					$_x174->Append("<a href='");
					if ($_x45->Value != null)
					{
						$_x174->Append(HttpUtility.HtmlEncode($_x45->Value));
					}
					else
					{
						$_x174->Append($_x45->GetInnerText());
					}
					$_x174->Append("'>");
					break;
				case "/url":
					$_x174->Append("</a>");
					break;
				case "email":
					$_x174->Append("<a href='mailto:");
					if ($_x45->Value != null)
					{
						$_x174->Append(HttpUtility.HtmlEncode($_x45->Value));
					}
					else
					{
						$_x174->Append($_x45->GetInnerText());
					}
					$_x174->Append("'>");
					break;
				case "/email":
					$_x174->Append("</a>");
					break;
				case "color":
					$_x174->Append("<font");
					if ($_x45->Value != null)
					{
						$_x174->Append(" color='");
						$_x174->Append(HttpUtility.HtmlEncode($_x45->Value));
						$_x174->Append("'");
					}
					$_x174->Append(">");
					break;
				case "/color":
					$_x174->Append("</font>");
					break;
				case "face":
					$_x174->Append("<font");
					if ($_x45->Value != null)
					{
						$_x174->Append(" name='");
						$_x174->Append(HttpUtility.HtmlEncode($_x45->Value));
						$_x174->Append("'");
					}
					$_x174->Append(">");
					break;
				case "/face":
					$_x174->Append("</font>");
					break;
				case "size":
					$_x174->Append("<font");
					if ($_x45->Value != null)
					{
						if ($this->OnlyNumber($_x45->Value))
						{
							$_x174->Append(" size='");
							$_x174->Append(HttpUtility.HtmlEncode($_x45->Value));
							$_x174->Append("'"); 
						}
						else
						{
							$_x174->Append(" style='font-size:");
							$_x174->Append(HttpUtility.HtmlEncode($_x45->Value));
							$_x174->Append("'");
						}
					}
					$_x174->Append(">");
					break;
				case "/size":
					$_x174->Append("</font>");
					break;
				case "align":
					$_x174->Append("<div style='");
					if ($_x45->Value != null)
					{
						$_x174->Append("text-align:");
						$_x174->Append(HttpUtility.HtmlEncode($_x45->Value));
					}
					$_x174->Append("'>");
					break;
				case "/align":
					$_x174->Append("</div>");
					break;
				case "quote":
					$_x174->Append("<blockquote>");
					break;
				case "/quote":
					$_x174->Append("</blockquote>");
					break;
				case "pre":
					$_x174->Append("<pre>");
					break;
				case "/pre":
					$_x174->Append("</pre>");
					break;
				case "p":
					$_x174->Append("<p>");
					break;
				case "/p":
					$_x174->Append("</p>");
					break;
				case "table":
					$_x174->Append("<table>");
					break;
				case "/table":
					$_x174->Append("</table>");
					break;
				case "tr":
					$_x174->Append("<tr>");
					break;
				case "/tr":
					$_x174->Append("</tr>");
					break;
				case "td":
					$_x174->Append("<td>");
					break;
				case "/td":
					$_x174->Append("</td>");
					break;
				case "code":
					$_x174->Append("<textarea class='ubb_tag_code' style='width:600px;height:350px;'>");
					break;
				case "/code":
					$_x174->Append("</textarea>");
					break;
			}
		}
		return $_x174->ToString();
	}

	function ParseBBCode($_x79)
	{
		$_x161 = new RTEBBCodeNode();
		$_x179 = $_x161;

		$_x15 = strlen($_x79);
		$_x204 = $_x15 - 1;
		$_x94 = 0;
		
		while ($_x94 < $_x15)
		{
			$_x180 = rtefilter_strpos($_x79,'[', $_x94);
			if ($_x180 == -1 || $_x180 == $_x204)
			{
				$_x179=$this->InsertNode(RTEBBCodeNode::CreateText(substr($_x79,$_x94, $_x15 - $_x94)), $_x179);
				break;
			}

			$_x205 = strpos($_x79,']', $_x180);
			if ($_x205 == -1)
			{
				$_x179=$this->InsertNode(RTEBBCodeNode::CreateText(substr($_x79,$_x94, $_x15 - $_x94)), $_x179);
				break;
			}

			if ($_x180 > $_x94)
			{
				$_x179=$this->InsertNode(RTEBBCodeNode::CreateText(substr($_x79,$_x94, $_x180 - $_x94)), $_x179);
				$_x94 = $_x180;
			}

			$_x181 = substr($_x79,$_x180 + 1,1);

			if ($_x181!='/'&&!RTEUtil::IsLetter($_x181))
			{
				if ($_x181 == '*' && $_x180 + 2 == $_x205)
				{
					$_x179=$this->InsertNode(RTEBBCodeNode::CreateTag("*", null), $_x179);
					$_x94 = $_x180 + 3;
					continue;
				}

				$_x179=$this->InsertNode(RTEBBCodeNode::CreateText("[" . $_x181), $_x179);
				$_x94 = $_x180 + 2;
				continue;
			}

			$_x206=false;
				
			for ($_x180 = $_x94+2; $_x180 < $_x15; $_x180++)
			{
				$_x181 = substr($_x79,$_x180,1);
				if ($_x181 == ']')
				{
					$_x179=$this->InsertNode(RTEBBCodeNode::CreateTag(substr($_x79,$_x94 + 1, $_x180 - $_x94 - 1), null), $_x179);
					$_x94 = $_x205 + 1;
					$_x206=true;
					break;
				}
				if ($_x181 == '=')
				{
					$_x179=$this->InsertNode(RTEBBCodeNode::CreateTag(substr($_x79,$_x94 + 1, $_x180 - $_x94 - 1), substr($_x79,$_x180 + 1, $_x205 - $_x180 - 1)), $_x179);
					$_x94 = $_x205 + 1;
					$_x206=true;
					break;
				}
				if (RTEUtil::IsLetter($_x181))
					continue;
				break;
			}
			
			if($_x206)
				continue;
				
			$_x94++;

		}

		$_x179->NextNode = new RTEBBCodeNode();
		$_x179->NextNode->PrevNode = $_x179;

		return $_x161;
	}
	function BuildRelation($_x161)
	{
		$_x176 = array();
		$_x177=0;
		for ($_x45 = $_x161; $_x45 != null; $_x45 = $_x45->NextNode)
		{
			if ($_x45->Name == null)
				continue;
			if ($_x45->IsEndTag)
			{
				$_x207=strtolower(substr($_x45->Name,1));
				while ($_x177 > 0)
				{
					$_x178 = array_pop($_x176);
					$_x177--;
					if($_x178->NameLower==$_x207)
					{
						$_x178->EndTag = $_x45;
						break;
					}
				}
			}
			else
			{
				if ($_x177 > 0)
				{
					$_x45->Parent = $_x176[$_x177-1];
				}
				if (!$_x45->IsClosed)
				{
					array_push($_x176,$_x45);
					$_x177++;
				}
			}
		}
	}
	function InsertNode($_x45, $_x179)
	{
		$_x179->NextNode = $_x45;
		$_x45->PrevNode = $_x179;
		return $_x45;
	}

	function OnlyNumber($_x65)
	{
		if (RTEUtil::IsNullOrEmpty($_x65))
			return true;
		$_x15=strlen($_x65);
		for($_x12=0;$_x12<$_x15;$_x12++)
		{
			$_x186=ord(substr($_x65,$_x12,1));
			if($_x186>=48&&$_x186<58)
				continue;
			return false;
		}
		return true;
	}
}


class RTEAllMatchItem
{
	public $NextItem;
	function IsMatch($_x172, $_x173)
	{
		return true;
	}
}
class RTENameMatchItem
{
	public $NextItem;
	public $MatchName;
	function IsMatch($_x172, $_x173)
	{
		if($_x172 == $this->MatchName)
		{
			
			return true;
		}
		return false;
	}
}

class RTEMatchList
{
	static function Parse($_x208, $_x209)
	{
		if ($_x208 == null)
			$_x208 = $_x209;
		if ($_x208 == null)
			return null;
		return RTEMatchList::ParseItem($_x208);
	}
	static function ParseItem($_x210)
	{
		if ($_x210 == null)
			return null;
		$_x210=trim($_x210);
		if (strlen($_x210)== 0)
			return null;

		$list = new RTEMatchList();
		if ($_x210 == "*")
		{
			$list->_item = new RTEAllMatchItem();
			return $list;
		}

		$_x211 = null;
		$_x212 = null;

		foreach (explode(",",strtolower($_x210)) as $_x98)
		{
			$_x112 = trim($_x98);
			if(strlen($_x112)==0)
				continue;
			$item = new RTENameMatchItem();
			$item->MatchName = $_x112;

			if ($_x211 == null)
			{
				$_x211 = $item;
				$_x212 = $item;
			}
			else
			{
				$_x212->NextItem = $item;
				$_x212 = $item;
			}
		}

		$list->_item = $_x211;
		return $list;
	}


	public $_item;

	function IsMatch($_x172, $_x173)
	{
		if($this->_item==null)
		{
			return true;
		}
		for ($item = $this->_item; $item != null; $item = $item->NextItem)
		{
			if ($item->IsMatch($_x172, $_x173))
				return true;
		}
		return false;
	}
}


class RTEMatchHandler
{
	public $TagWhiteList;
	public $TagBlackList;
	public $AttrWhiteList;
	public $AttrBlackList;
	public $StyleWhiteList;
	public $StyleBlackList;

	function InitFilter($_x81)
	{
		if ($this->TagWhiteList != null || $this->TagBlackList != null)
			$_x81->CheckTag = $this;
		if ($this->AttrWhiteList != null || $this->AttrBlackList != null)
			$_x81->CheckAttr = $this;
		if ($this->StyleWhiteList != null || $this->StyleBlackList != null)
			$_x81->CheckStyle = $this;
	}
	function CheckTag($_x172)
	{
		if ($this->TagBlackList != null)
			if ($this->TagBlackList->IsMatch($_x172, ""))
				return false;
		if ($this->TagWhiteList != null)
			if (!$this->TagWhiteList->IsMatch($_x172, ""))
				return false;
		return true;
	}
	function CheckAttr($_x213, $_x172, $_x173)
	{
		if ($this->AttrBlackList != null)
			if ($this->AttrBlackList->IsMatch($_x172, $_x173))
				return false;
		if ($this->AttrWhiteList != null)
			if (!$this->AttrWhiteList->IsMatch($_x172, $_x173))
				return false;
		return true;
	}
	function CheckStyle($_x213, $_x172, $_x173)
	{
		if ($this->StyleBlackList != null)
			if ($this->StyleBlackList->IsMatch($_x172, $_x173))
				return false;
		if ($this->StyleWhiteList != null)
			if (!$this->StyleWhiteList->IsMatch($_x172, $_x173))
				return false;
		return true;
	}
}

class RTEUtil
{
	static function IsNullOrEmpty($_x65)
	{
		if($_x65==null)
			return true;
		if($_x65=="")
			return true;
		return false;
	}
	static function IsLetter($_x30)
	{
		$_x186=ord($_x30);
		
		if($_x186>=65&&$_x186<91)
			return true;
		if($_x186>=97&&$_x186<123)
			return true;
		return false;
	}
	static function ExtractPlainTextWithLinefeedsOutOfHtml($_x214)
	{
		return $_x214;
	}
}
class RTEStringBuilder
{
	var $list=array();
	function Append($_x65)
	{
		if($_x65)array_push($this->list,$_x65);
	}
	function ToString()
	{
		return implode("",$this->list);
	}
}



?>