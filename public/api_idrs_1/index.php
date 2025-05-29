<?php 
 //$uri =  $_SERVER["REQUEST_URI"]; //it will print full url
 //error_log("FULL URL : ". $uri);
 //$uriArray = explode('/', $uri); //convert string into array with explode
 //$cmdvalue = $uriArray[2];
 //error_log("cmdvalue : ".$cmdvalue);
 //error_log("COMMAND FROM URL : ".$_REQUEST['cmd']);

if(isset($_REQUEST['cmd'])){
   $cmd=$_REQUEST['cmd'];
  

   if($cmd){//checking if a command has been submitted
      include('functions.php');
      switch($cmd){ 
      	case "Login":
	          Login();
	     break;
		 case "viewFacility":
			   viewFacility();
	     break;
	     case "addtest":
	           addtest();
	     break;
	     case "addsample":
	           addsample();
	     break;
		 case "checkLogin":
	           checkLogin();
	     break;
	     case "addbarcode":
	           addbarcode();
	     break;
		 case "viewSamples":
	           viewSamples();
	     break;
		 case "RecieveSample":
	           RecieveSample();
	     break;
	     case "ConfirmSamples":
	           ConfirmSamples();
	     break;
	     case "addPackageMovement":
	           addPackageMovement();
	     break;
		 case "bigBarcodeLogic":
			error_log("bigBarcodeLogic reached : ".$cmd);
	           bigBarcodeLogic();
	     break;
	     case "ViewSampleStatus":
	           ViewSampleStatus();
	     break;
	     case "delieverSample":
	           delieverSample();
	     break;
	     case "addReferedsample":
	           addReferedsample();
	     break;
	     case "updateDelivery":
	           updateDelivery();
	     break;
	     case "updateRecieve":
	           updateRecieve();
	     break;
	     case "ReferedPackage":
	           ReferedPackage();
	     break;
	     case "SampleTranspoterLogin": 
	           SampleTranspoterLogin(); 
	     break;
	     case "updateTransportPicking": 
	           updateTransportPicking(); 
	     break;
	     case "updateTransPicking": 
	           updateTransPicking(); 
	     break;
	     case "addTobigpackage": 
	           addTobigpackage(); 
	     break;
	     case "PackageDetail": 
	           PackageDetail(); 
	     break;
	     case "updateBStatusPick": 
	           updateBStatusPick(); 
	     break;
	     case "updateBStatusDeliever": 
	           updateBStatusDeliever(); 
	     break;
	     case "updateBarStatusPicking": 
	           updateBarStatusPicking(); 
	     break;
	     case "delieverSampleCPHL": 
	           delieverSampleCPHL(); 
	     break;
	     case "updateDeliveryCPHL": 
	           updateDeliveryCPHL(); 
	     break;
	     case "updateTransportDelievery": 
	           updateTransportDelievery(); 
	     break;
		 case "updateCPHLRecieving": 
	           updateCPHLRecieving(); 
	     break;
		 case "updateBStatusRecieveCPHL": 
	           updateBStatusRecieveCPHL(); 
	     break;
		 case "RecieveSampleCPHL": 
	           RecieveSampleCPHL(); 
	     break;
		 case "updateRecieveCPHL": 
	           updateRecieveCPHL(); 
	     break;  
		 case "CheckMySamples": 
			   CheckMySamples(); 
	     break; 
		case "getFacility": 
			  getFacility(); 
	     break;  
		case "viewReferenceLab":  
              viewReferenceLab();  
		break; 
        case "getSpecimen": 
			  getSpecimen(); 
        break; 
	    case "getEvents":  
	          getEvents(); 
	    break; 
	    case "addPackageEvent":  
			  addPackageEvent(); 
		break; 
		case "CheckBarcodeRefered":  
	          CheckBarcodeRefered(); 
		break; 
		case "updateReferedBarStatusPicking":  
	          updateReferedBarStatusPicking(); 
		break; 
		case "BarEmergencyStatus":  
	          BarEmergencyStatus(); 
		break; 
		case "delieverReferedSampleCPHL": 
			  delieverReferedSampleCPHL(); 
		break; 
		case "delieverOBSampleCPHL": 
			  delieverOBSampleCPHL(); 
		break;
		case "updateDeliveryReferCPHL": 
			  updateDeliveryReferCPHL(); 
		break; 
		case "updateBStatusDelieverRefer": 
			  updateBStatusDelieverRefer(); 
		break;     
		case "addResults": 
			  addResults(); 
		break; 
		case "updatevl_lodresults": 	          
			  updatevl_lodresults();	     
		break;	
		case "getHubs": 
	          getHubs(); 
	    break; 
	    case "addLogisticPackage": 
	          addLogisticPackage(); 
	    break; 
	    case "AddLogPackageMovement": 
	          AddLogPackageMovement(); 
	    break;
	    case "getLogistics": 
	          getLogistics(); 
	    break;
	    case "updateComdtyDelivery": 
	          updateComdtyDelivery(); 
	    break;
	    case "delieverComdty": 
	          delieverComdty(); 
	    break;
	    case "updateBen_Inventory_db": 
	          updateBen_Inventory_db();
	    break; 
	    case "updateBen_Inventory_OnPicking": 
	          updateBen_Inventory_OnPicking(); 
	    break; 
	    case "updateBen_Status": 
	          updateBen_Status(); 
	    break; 
	default:
	        $json=array();
	        $ary=array("status"=>"invalid request 1");
	        $json['result'][]=$ary;
	        echo json_encode($json);
	      break;
	  }
  }else{//if no command was submitted return error message
     $json=array();
	 $ary=array("status"=>"invalid request 2");
	 $json['result'][]=$ary;
	 echo json_encode($json);
   } 
}else{// if no command was submitted return error message
    $json=array();
	 $ary=array("status"=>"invalid request 3");
	 $json['result'][]=$ary;
	 echo json_encode($json);
}
?>