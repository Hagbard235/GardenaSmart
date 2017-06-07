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
        public function HerstellerInfoAktualisieren() {
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
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Hersteller");
				SetValue($VarID_NEU, $status);
				
				}
				
				

    $proberty_name = "product";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Produktname");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "serial_number";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Serien-Nummer");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "version";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Version");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "category";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"Kategorie");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "last_time_online";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"letzte Onlinezeit");
				SetValue($VarID_NEU, $status);
				
				}

    $proberty_name = "sgtin";$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
			
				$varID = @$this->GetIDForIdent($proberty_name);
				if (IPS_VariableExists($varID)) {
					SetValue($varID, $status);
			

				}
				else {
				$VarID_NEU = $this->RegisterVariableString($proberty_name,"sgtin");
				SetValue($VarID_NEU, $status);
				
				}
				
				
				
				

        }
		
		 
	
    }
?>
