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
 
        }
 
        // Überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();
			//Instanz ist aktiv
			$this->SetStatus(102);
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
			$this->BatterieInfosAktualisieren();
			$this->FunkInfosAktualisieren();
				

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
		
		
		 
	
    }
?>
