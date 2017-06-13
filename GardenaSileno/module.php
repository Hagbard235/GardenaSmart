<?
     include("php_gardena_mover_class.ips.php");
    // Klassendefinition
    class GardenaSileno extends IPSModule {

 
        // Der Konstruktor des Moduls
        // Überschreibt den Standard Kontruktor von IPS
        public function __construct($InstanceID) {
            // Diese Zeile nicht löschen
            parent::__construct($InstanceID);
 
            // Selbsterstellter Code
        }
 
        // Überschreibt die interne IPS_Create($id) Funktion
        public function Create() {
            // Diese Zeile nicht löschen.
            parent::Create();
            $this->RegisterPropertyString("Username", "Mail-Adresse bei Gardena"); 
            $this->RegisterPropertyString("Password", "Password"); 
			$this->RegisterPropertyInteger("Interval",5); 
			$this->RegisterPropertyInteger("IntervalB",5); 
			$this->RegisterPropertyInteger("IntervalB",0); 
			
			//Variablenprofil anlegen ($name, $ProfileType, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Icon)
		$profilename = "GAR.Befehle";
		if (!IPS_VariableProfileExists($profilename)) {
			IPS_CreateVariableProfile($profilename, 1);
			IPS_SetVariableProfileIcon($profilename, "Flower");
			IPS_SetVariableProfileAssociation($profilename, 1, "bis auf weiteres parken", "", 0xFFFF00);
			IPS_SetVariableProfileAssociation($profilename, 2, "parken bis zum nächsten Timer", "", 0xFFFF00);
			IPS_SetVariableProfileAssociation($profilename, 3, "Start/Wiederaufname Timer", "", 0xFFFF00);
			IPS_SetVariableProfileAssociation($profilename, 4, "Start für 24 Stunden", "", 0xFFFF00);
			IPS_SetVariableProfileAssociation($profilename, 5, "Start für 3 Tage", "", 0xFFFF00);
			
		}
				$proberty_name = "action";
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableInteger($proberty_name,"Aktion","GAR.Befehle",0);
				$this->EnableAction($proberty_name);
		
				
				
				}
				$doThis = 'GAR_AktuellerGeraeteStatusAktualisieren($_IPS[\'TARGET\']);';
				$interv = $this->ReadPropertyInteger("Interval")*60000;
				$this->RegisterTimer("Update", $interv, $doThis);
				$doThis = 'GAR_BatterieInfosAktualisieren($_IPS[\'TARGET\']);';
				$interv = $this->ReadPropertyInteger("IntervalB")*60000;
				$this->RegisterTimer("UpdateB", $interv, $doThis);
				$doThis = 'GAR_FunkInfosAktualisieren($_IPS[\'TARGET\']);';
				$interv = $this->ReadPropertyInteger("IntervalF")*60000;
				$this->RegisterTimer("UpdateF", $interv, $doThis);
				
 
        }
		
		public function RequestAction($Ident, $Value) {
		  $username = $this->ReadPropertyString("Username");
				$password = $this->ReadPropertyString("Password");
			    $gardena = new gardena($username, $password );
		  $mower = $gardena -> getFirstDeviceOfCategory($gardena::CATEGORY_MOWER);

		switch($Value) {
			case "1":
				 $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_PARK_UNTIL_FURTHER_NOTICE);
				break;
			case "2":
				 $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_PARK_UNTIL_NEXT_TIMER);
				break;
			case "3":
				 $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_START_RESUME_SCHEDULE);
				break;
			case "4":
				  $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_START_24HOURS);
				break;
			case "5":
				 $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_START_3DAYS);
				break;
			default:
				throw new Exception("Invalid action");
		}
	}
 
        // Überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();
			//Instanz ist aktiv
			$this->SetStatus(102);
			
			if ($this->ReadPropertyString("Password") !== "Password") {
			$this->AlleInfosAktualisieren();
			$this->SetTimerInterval("Update", $this->ReadPropertyInteger("Interval")*60000);
			}
        }
 
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * ABC_MeineErsteEigeneFunktion($id);
        *
        */
        public function AlleInfosAktualisieren() {
            $this->GeraeteInfosAktualisieren();
			$this->AktuellerGeraeteStatusAktualisieren();
			$this->BatterieInfosAktualisieren();
			$this->FunkInfosAktualisieren();

				

        }
		public function CheckUsernameUndPassword() {
            // Selbsterstellter Code
			    $username = $this->ReadPropertyString("Username");
				$password = $this->ReadPropertyString("Password");
			
				
				$data = array(
            "sessions" => array(
                "email" => "$username", "password" => "$password")
            );
        $data_string = json_encode($data);
        $ch = curl_init(self::LOGINURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
			echo ($result);	
			   
		}
		
		public function GeraeteInfosAktualisieren() {
            // Selbsterstellter Code
			    $username = $this->ReadPropertyString("Username");
				$password = $this->ReadPropertyString("Password");
				//echo ($username);
				//echo ($password);
			    $gardena = new gardena($username, $password );
				$mower = $gardena -> getFirstDeviceOfCategory($gardena::CATEGORY_MOWER);
				$category_name = "device_info";
				$proberty_name = "manufacturer";
				$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Gerät_Hersteller");
				SetValue($VarID_NEU, $status);
				
				}
				
				

    $proberty_name = "product";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Gerät_Produktname");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "serial_number";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Gerät_Serien-Nummer");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "version";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Gerät_Version");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "category";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Gerät_Kategorie");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "last_time_online";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Gerät_letzte Onlinezeit");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "sgtin";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Gerät_sgtin");
				SetValue($VarID_NEU, $status);
				
				}
				
				

        }
		
		public function BatterieInfosAktualisieren() {
            // Selbsterstellter Code
			    $username = $this->ReadPropertyString("Username");
				$password = $this->ReadPropertyString("Password");
				//echo ($username);
				//echo ($password);
			    $gardena = new gardena($username, $password );
				$mower = $gardena -> getFirstDeviceOfCategory($gardena::CATEGORY_MOWER);
				
				
				$category_name = "battery";
				
  $proberty_name = "level";$varID = @$this->GetIDForIdent($proberty_name);
   $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableInteger($proberty_name,"Batterie_Level");
				 $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
				
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "rechargable_battery_status";$varID = @$this->GetIDForIdent($proberty_name);
	 $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Batterie_Status");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "charging";$varID = @$this->GetIDForIdent($proberty_name);
	 $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableBoolean($proberty_name,"Batterie_Ladestatus");
				SetValue($VarID_NEU, $status);
				
				}
				   
				

        }
		public function FunkInfosAktualisieren() {
            // Selbsterstellter Code
			    $username = $this->ReadPropertyString("Username");
				$password = $this->ReadPropertyString("Password");
				//echo ($username);
				//echo ($password);
			    $gardena = new gardena($username, $password );
				$mower = $gardena -> getFirstDeviceOfCategory($gardena::CATEGORY_MOWER);
				
				   $category_name = "radio";
				   
				 $proberty_name = "quality";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableInteger($proberty_name,"Funk_Stärke");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "connection_status";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Funk_Status");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "state";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Funk_Qualität");
				SetValue($VarID_NEU, $status);
				
				}
				

        }
		public function AktuellerGeraeteStatusAktualisieren() {
            // Selbsterstellter Code
			    $username = $this->ReadPropertyString("Username");
				$password = $this->ReadPropertyString("Password");
				//echo ($username);
				//echo ($password);
			    $gardena = new gardena($username, $password );
				$mower = $gardena -> getFirstDeviceOfCategory($gardena::CATEGORY_MOWER);
				
				  $category_name = "internal_temperature";

  
    $proberty_name = "temperature";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
   
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableInteger($proberty_name,"Gerät_Interne_Temperatur");
				SetValue($VarID_NEU, $status);
				
				}
  $category_name = "mower";
				
				$proberty_name = "manual_operation";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Status_manuelle Operation");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "status";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Status_aktuelle Aktion");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "source_for_next_start";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Status_Grund für nächsten Start");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "timestamp_next_start";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Status_Uhrzeit nächster Start");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "override_end_time";
    $status = $gardena -> getInfo($mower, $category_name, $proberty_name);
    $varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Status_Überschriebene Endzeit");
				SetValue($VarID_NEU, $status);
				
				}
			
        }
			public function AktionAusfuehren($action) {
      
	$gardena = new gardena($pw_user_maeher, $pw_pawo_maeher);
    $mower = $gardena -> getFirstDeviceOfCategory($gardena::CATEGORY_MOWER);

    
        {
            
            $switch = $action;
            switch ($switch)
            {
            // Parken ----------------------------------------------------------
            case 1:
                $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_PARK_UNTIL_FURTHER_NOTICE);
                break;
            case 2:
                $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_PARK_UNTIL_NEXT_TIMER);
                break;
            // Mähen -----------------------------------------------------------
            case 3:
                $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_START_RESUME_SCHEDULE);
                break;
            case 4:
                $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_START_24HOURS);
                break;
            case 5:
                $gardena -> sendCommand($mower, $gardena -> CMD_MOWER_START_3DAYS);
                break;
            }
        
			}		
			}
    }
?>
