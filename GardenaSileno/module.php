<?
    // Klassendefinition
    class GardenaSileno extends IPSModule {
     include("php_gardena_mover_class.ips.php");
    var $user_id, $token, $locations;
    var $devices = array();

    const LOGINURL        = "https://sg-api.dss.husqvarnagroup.net/sg-1/sessions";
    const LOCATIONSURL    = "https://sg-api.dss.husqvarnagroup.net/sg-1/locations/?user_id=";
    const DEVICESURL    = "https://sg-api.dss.husqvarnagroup.net/sg-1/devices?locationId=";
    const CMDURL        = "https://sg-api.dss.husqvarnagroup.net/sg-1/devices/|DEVICEID|/abilities/mower/command?locationId=";

    var $CMD_MOWER_PARK_UNTIL_NEXT_TIMER        = array("name" => "park_until_next_timer");
    var $CMD_MOWER_PARK_UNTIL_FURTHER_NOTICE    = array("name" => "park_until_further_notice");
    var $CMD_MOWER_START_RESUME_SCHEDULE        = array("name" => "start_resume_schedule");
    var $CMD_MOWER_START_24HOURS                = array("name" => "start_override_timer", "parameters" => array("duration" => 1440));
    var $CMD_MOWER_START_3DAYS                    = array("name" => "start_override_timer", "parameters" => array("duration" => 4320));
// -----------------------------------------------------------------------------
    // Gateway
    const CATEGORY_DEVICE_INFO        = "device_info";
    const CATEGORY_GATEWAY            = "gateway";
    // Mover Device Info
    const CATEGORY_DEVICE            = "device_info";
    // Mover Akkuzustand
    const CATEGORY_BATTERY            = "battery";
    // Mover Radio - Funksignal
    const CATEGORY_RADIO            = "radio";
    // Mover Mäherstatus
    const CATEGORY_MOWER            = "mower";
    const PROPERTY_STATUS            = "status";
    // Mover Temperatur
    const CATEGORY_TEMPERATUR        = "internal_temperature";
 
 
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
        public function DatenAktualisieren() {
            // Selbsterstellter Code
            $uName = ;
			    $gardena = new gardena($this->ReadPropertyString("Username"), $this->ReadPropertyString("Password"));
				$mower = $gardena -> getFirstDeviceOfCategory($gardena::CATEGORY_MOWER);
				$category_name = "device_info";
				$proberty_name = "manufacturer";
				$status = $gardena -> getInfo($mower, $category_name, $proberty_name);
				echo($id_device_manufaktur, $status);
        }
		
		 
	
    }
?>
