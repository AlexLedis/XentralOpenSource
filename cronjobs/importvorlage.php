<?php
use Xentral\Core\LegacyConfig\ConfigLoader;
error_reporting(E_ERROR | E_PARSE);
if(!class_exists('Conf')){
  include_once(dirname(__DIR__) . '/conf/main.conf.php');
}
include_once(dirname(__DIR__) . '/phpwf/plugins/class.mysql.php');
if(!class_exists('Secure')){
  include_once(dirname(__DIR__) . '/phpwf/plugins/class.secure.php');
}
if(!class_exists('User')){
  include_once(dirname(__DIR__) . '/phpwf/plugins/class.user.php');
}
if(!class_exists('FormHandler')){
  include_once(dirname(__DIR__) . '/phpwf/plugins/class.formhandler.php');
}
if(!class_exists('FPDFWAWISION')){
  if(file_exists(dirname(__DIR__).'/conf/user_defined.php')){
    include_once dirname(__DIR__) . '/conf/user_defined.php';
  }
  if(defined('USEFPDF3') && USEFPDF3 && file_exists(dirname(__DIR__) . '/www/lib/pdf/fpdf_3.php')){
    require_once dirname(__DIR__) . '/www/lib/pdf/fpdf_3.php';
  }else if(defined('USEFPDF2') && USEFPDF2 && file_exists(dirname(__DIR__) . '/www/lib/pdf/fpdf_2.php')){
    require_once dirname(__DIR__) . '/www/lib/pdf/fpdf_2.php';
  }else{
    require_once dirname(__DIR__) . '/www/lib/pdf/fpdf.php';
  }
}
if(!class_exists('PDF_EPS')){
  include_once(dirname(__DIR__) . '/www/lib/pdf/fpdf_final.php');
}
if(!class_exists('IMAP')){
  include_once(dirname(__DIR__) . '/www/lib/imap.inc.php');
}
if(!class_exists('erpAPI')){
  include_once(dirname(__DIR__) . '/www/lib/class.erpapi.php');
}
if(!class_exists('Remote')){
  include_once(dirname(__DIR__) . '/www/lib/class.remote.php');
}
include_once(dirname(__DIR__) . '/www/lib/class.httpclient.php');
if(!class_exists('AES')){
  $aes = '';
  $phpversion = phpversion();
  if($phpversion{0} == '7' && (int)$phpversion{2} > 0) $aes = '2';
  if($aes == 2 && is_file(dirname(__DIR__) . '/www/lib/class.aes' . $aes . '.php')){
    include_once(dirname(__DIR__) . '/www/lib/class.aes' . $aes . '.php');
  }else{
    include_once(dirname(__DIR__) . '/www/lib/class.aes.php');
  }
}
include_once(dirname(__DIR__) . '/www/plugins/phpmailer/class.phpmailer.php');
include_once(dirname(__DIR__) . '/www/plugins/phpmailer/class.smtp.php');

$classes = array('briefpapier','lieferschein','auftrag','anfrage','gutschrift','bestellung','rechnung',
  'mahnwesen');
foreach($classes as $class)
{
  if(file_exists(dirname(__DIR__) . '/www/lib/dokumente/class.'.$class.'_custom.php')
    && !class_exists(ucfirst($class).($class==='briefpapier'?'':'PDF').'Custom')
  )
  {
    include_once(dirname(__DIR__) . '/www/lib/dokumente/class.'.$class.'_custom.php');
  }elseif(file_exists(dirname(__DIR__) . '/www/lib/dokumente/class.'.$class.'.php')
    && !class_exists(ucfirst($class).($class==='briefpapier'?'':'PDF'))
  )
  {
    include_once(dirname(__DIR__) . '/www/lib/dokumente/class.'.$class.'.php');
  }
}

/*
include_once(__DIR__."/../www/lib/dokumente/class.briefpapier.php");
include_once(__DIR__."/../www/lib/dokumente/class.lieferschein.php");
include_once(__DIR__."/../www/lib/dokumente/class.auftrag.php");
include_once(__DIR__."/../www/lib/dokumente/class.angebot.php");
if(file_exists(__DIR__."/../www/lib/dokumente/class.anfrage.php"))include_once(__DIR__."/../www/lib/dokumente/class.anfrage.php");
include_once(__DIR__."/../www/lib/dokumente/class.gutschrift.php");
include_once(__DIR__."/../www/lib/dokumente/class.bestellung.php");
include_once(__DIR__."/../www/lib/dokumente/class.rechnung.php");
include_once(__DIR__."/../www/lib/dokumente/class.mahnwesen.php");
include_once(__DIR__."/../phpwf/class.application_core.php");
include_once(__DIR__."/../www/lib/ShopimporterBase.php");
*/
if(!class_exists('WawiString')){
  include_once(dirname(__DIR__) . '/phpwf/plugins/class.string.php');
}
if(!class_exists('SMTP')){
  require_once(dirname(__DIR__) . '/www/plugins/phpmailer/class.smtp.php');
}
if(!class_exists('PHPMailer')){
  require_once(dirname(__DIR__) . '/www/plugins/phpmailer/class.phpmailer.php');
}
if(!class_exists('app_t2'))
{
  class app_t2 extends ApplicationCore {
    var $DB;
    var $erp;
    var $User;
    var $mail;
    var $remote;
    var $Secure;

    public function GetLandLang($isocode)
    {
      $flipped = array_flip($this->GetLaender());
      if(isset($flipped[$isocode])){
        $land = $flipped[$isocode];
      }
      else
      {
        $land = 'unkown';
      }
      return $land;
    }

    public function GetLaender()
    {
      $laender = array(
        'Afghanistan'  => 'AF',
        '&Auml;gypten'  => 'EG',
        'Albanien'  => 'AL',
        'Algerien'  => 'DZ',
        'Andorra'  => 'AD',
        'Angola'  => 'AO',
        'Anguilla'  => 'AI',
        'Antarktis'  => 'AQ',
        'Antigua und Barbuda'  => 'AG',
        '&Auml;quatorial Guinea'  => 'GQ',
        'Argentinien'  => 'AR',
        'Armenien'  => 'AM',
        'Aruba'  => 'AW',
        'Aserbaidschan'  => 'AZ',
        '&Auml;thiopien'  => 'ET',
        'Australien'  => 'AU',
        'Bahamas'  => 'BS',
        'Bahrain'  => 'BH',
        'Bangladesh'  => 'BD',
        'Barbados'  => 'BB',
        'Belgien'  => 'BE',
        'Belize'  => 'BZ',
        'Benin'  => 'BJ',
        'Bermudas'  => 'BM',
        'Bhutan'  => 'BT',
        'Birma'  => 'MM',
        'Bolivien'  => 'BO',
        'Bosnien-Herzegowina'  => 'BA',
        'Botswana'  => 'BW',
        'Bouvet Inseln'  => 'BV',
        'Brasilien'  => 'BR',
        'Britisch-Indischer Ozean'  => 'IO',
        'Brunei'  => 'BN',
        'Bulgarien'  => 'BG',
        'Burkina Faso'  => 'BF',
        'Burundi'  => 'BI',
        'Chile'  => 'CL',
        'China'  => 'CN',
        'Christmas Island'  => 'CX',
        'Cook Inseln'  => 'CK',
        'Costa Rica'  => 'CR',
        'D&auml;nemark'  => 'DK',
        'Deutschland'  => 'DE',
        'Djibuti'  => 'DJ',
        'Dominika'  => 'DM',
        'Dominikanische Republik'  => 'DO',
        'Ecuador'  => 'EC',
        'El Salvador'  => 'SV',
        'Elfenbeink&uuml;ste'  => 'CI',
        'Eritrea'  => 'ER',
        'Estland'  => 'EE',
        'Falkland Inseln'  => 'FK',
        'F&auml;r&ouml;er Inseln'  => 'FO',
        'Fidschi'  => 'FJ',
        'Finnland'  => 'FI',
        'Frankreich'  => 'FR',
        'Franz&ouml;sisch Guyana'  => 'GF',
        'Franz&ouml;sisch Polynesien'  => 'PF',
        'Franz&ouml;sisches S&uuml;d-Territorium'  => 'TF',
        'Gabun'  => 'GA',
        'Gambia'  => 'GM',
        'Georgien'  => 'GE',
        'Ghana'  => 'GH',
        'Gibraltar'  => 'GI',
        'Grenada'  => 'GD',
        'Griechenland'  => 'GR',
        'Gr&ouml;nland'  => 'GL',
        'Großbritannien'  => 'UK',
        'Großbritannien (UK)'  => 'GB',
        'Guadeloupe'  => 'GP',
        'Guam'  => 'GU',
        'Guatemala'  => 'GT',
        'Guinea'  => 'GN',
        'Guinea Bissau'  => 'GW',
        'Guyana'  => 'GY',
        'Haiti'  => 'HT',
        'Heard und McDonald Islands'  => 'HM',
        'Honduras'  => 'HN',
        'Hong Kong'  => 'HK',
        'Indien'  => 'IN',
        'Indonesien'  => 'ID',
        'Irak'  => 'IQ',
        'Iran'  => 'IR',
        'Irland'  => 'IE',
        'Island'  => 'IS',
        'Israel'  => 'IL',
        'Italien'  => 'IT',
        'Jamaika'  => 'JM',
        'Japan'  => 'JP',
        'Jemen'  => 'YE',
        'Jordanien'  => 'JO',
        'Jugoslawien'  => 'YU',
        'Kaiman Inseln'  => 'KY',
        'Kambodscha'  => 'KH',
        'Kamerun'  => 'CM',
        'Kanada'  => 'CA',
        'Kap Verde'  => 'CV',
        'Kasachstan'  => 'KZ',
        'Kenia'  => 'KE',
        'Kirgisistan'  => 'KG',
        'Kiribati'  => 'KI',
        'Kokosinseln'  => 'CC',
        'Kolumbien'  => 'CO',
        'Komoren'  => 'KM',
        'Kongo'  => 'CG',
        'Kongo, Demokratische Republik'  => 'CD',
        'Kosovo'  => 'KO',
        'Kroatien'  => 'HR',
        'Kuba'  => 'CU',
        'Kuwait'  => 'KW',
        'Laos'  => 'LA',
        'Lesotho'  => 'LS',
        'Lettland'  => 'LV',
        'Libanon'  => 'LB',
        'Liberia'  => 'LR',
        'Libyen'  => 'LY',
        'Liechtenstein'  => 'LI',
        'Litauen'  => 'LT',
        'Luxemburg'  => 'LU',
        'Macao'  => 'MO',
        'Madagaskar'  => 'MG',
        'Malawi'  => 'MW',
        'Malaysia'  => 'MY',
        'Malediven'  => 'MV',
        'Mali'  => 'ML',
        'Malta'  => 'MT',
        'Marianen'  => 'MP',
        'Marokko'  => 'MA',
        'Marshall Inseln'  => 'MH',
        'Martinique'  => 'MQ',
        'Mauretanien'  => 'MR',
        'Mauritius'  => 'MU',
        'Mayotte'  => 'YT',
        'Mazedonien'  => 'MK',
        'Mexiko'  => 'MX',
        'Mikronesien'  => 'FM',
        'Mocambique'  => 'MZ',
        'Moldavien'  => 'MD',
        'Monaco'  => 'MC',
        'Mongolei'  => 'MN',
        'Montenegro'  => 'ME',
        'Montserrat'  => 'MS',
        'Namibia'  => 'NA',
        'Nauru'  => 'NR',
        'Nepal'  => 'NP',
        'Neukaledonien'  => 'NC',
        'Neuseeland'  => 'NZ',
        'Nicaragua'  => 'NI',
        'Niederlande'  => 'NL',
        'Niederl&auml;ndische Antillen'  => 'AN',
        'Niger'  => 'NE',
        'Nigeria'  => 'NG',
        'Niue'  => 'NU',
        'Nord Korea'  => 'KP',
        'Norfolk Inseln'  => 'NF',
        'Norwegen'  => 'NO',
        'Oman'  => 'OM',
        '&Ouml;sterreich'  => 'AT',
        'Pakistan'  => 'PK',
        'Pal&auml;stina'  => 'PS',
        'Palau'  => 'PW',
        'Panama'  => 'PA',
        'Papua Neuguinea'  => 'PG',
        'Paraguay'  => 'PY',
        'Peru'  => 'PE',
        'Philippinen'  => 'PH',
        'Pitcairn'  => 'PN',
        'Polen'  => 'PL',
        'Portugal'  => 'PT',
        'Puerto Rico'  => 'PR',
        'Qatar'  => 'QA',
        'Reunion'  => 'RE',
        'Ruanda'  => 'RW',
        'Rum&auml;nien'  => 'RO',
        'Ru&szlig;land'  => 'RU',
        'Saint Lucia'  => 'LC',
        'Sambia'  => 'ZM',
        'Samoa'  => 'AS',
        'Samoa'  => 'WS',
        'San Marino'  => 'SM',
        'Sao Tome'  => 'ST',
        'Saudi Arabien'  => 'SA',
        'Schweden'  => 'SE',
        'Schweiz'  => 'CH',
        'Senegal'  => 'SN',
        'Serbien'  => 'RS',
        'Seychellen'  => 'SC',
        'Sierra Leone'  => 'SL',
        'Singapur'  => 'SG',
        'Slowakei -slowakische Republik-'  => 'SK',
        'Slowenien'  => 'SI',
        'Solomon Inseln'  => 'SB',
        'Somalia'  => 'SO',
        'South Georgia, South Sandwich Isl.'  => 'GS',
        'Spanien'  => 'ES',
        'Sri Lanka'  => 'LK',
        'St. Helena'  => 'SH',
        'St. Kitts Nevis Anguilla'  => 'KN',
        'St. Pierre und Miquelon'  => 'PM',
        'St. Vincent'  => 'VC',
        'S&uuml;d Korea'  => 'KR',
        'S&uuml;dafrika'  => 'ZA',
        'Sudan'  => 'SD',
        'Surinam'  => 'SR',
        'Svalbard und Jan Mayen Islands'  => 'SJ',
        'Swasiland'  => 'SZ',
        'Syrien'  => 'SY',
        'Tadschikistan'  => 'TJ',
        'Taiwan'  => 'TW',
        'Tansania'  => 'TZ',
        'Thailand'  => 'TH',
        'Timor'  => 'TP',
        'Togo'  => 'TG',
        'Tokelau'  => 'TK',
        'Tonga'  => 'TO',
        'Trinidad Tobago'  => 'TT',
        'Tschad'  => 'TD',
        'Tschechische Republik'  => 'CZ',
        'Tunesien'  => 'TN',
        'T&uuml;rkei'  => 'TR',
        'Turkmenistan'  => 'TM',
        'Turks und Kaikos Inseln'  => 'TC',
        'Tuvalu'  => 'TV',
        'Uganda'  => 'UG',
        'Ukraine'  => 'UA',
        'Ungarn'  => 'HU',
        'Uruguay'  => 'UY',
        'Usbekistan'  => 'UZ',
        'Vanuatu'  => 'VU',
        'Vatikan'  => 'VA',
        'Venezuela'  => 'VE',
        'Vereinigte Arabische Emirate'  => 'AE',
        'Vereinigte Staaten von Amerika'  => 'US',
        'Vietnam'  => 'VN',
        'Virgin Island (Brit.)'  => 'VG',
        'Virgin Island (USA)'  => 'VI',
        'Wallis et Futuna'  => 'WF',
        'Wei&szlig;ru&szlig;land'  => 'BY',
        'Westsahara'  => 'EH',
        'Zentralafrikanische Republik'  => 'CF',
        'Zimbabwe'  => 'ZW',
        'Zypern'  => 'CY'
      );
      return $laender;
    }
  }
}
//ENDE

if(empty($app) || !class_exists('ApplicationCore') || !($app instanceof ApplicationCore)) {
  $app = new app_t2();
}
if(empty($app->Conf)){
  $conf = ConfigLoader::load();
  $app->Conf = $conf;
}
if(empty($app->DB) || empty($app->DB->connection)) {
  $app->DB = new DB($app->Conf->WFdbhost, $app->Conf->WFdbname, $app->Conf->WFdbuser, $app->Conf->WFdbpass, $app, $app->Conf->WFdbport);
}
if(empty($app->erp)) {
  if(class_exists('erpAPICustom')) {
    $erp = new erpAPICustom($app);
  }
  else {
    $erp = new erpAPI($app);
  }
  $app->erp = $erp;
}
$app->String         = new WawiString();
if(class_exists('RemoteCustom'))
{
  $app->remote = new RemoteCustom($app);
}else{
  $app->remote = new Remote($app);
}
$app->Secure = new Secure($app);
$app->User = new User($app);
if(!defined('FPDF_FONTPATH'))
{
  define('FPDF_FONTPATH',dirname(__DIR__) . '/www/lib/pdf/font/');
}


//ENDE


$benutzername = $app->erp->Firmendaten("benutzername");
$passwort = $app->erp->Firmendaten("passwort");
$host = $app->erp->Firmendaten("host");
$port = $app->erp->Firmendaten("port");
$mailssl = $app->erp->Firmendaten("mailssl");
$noauth = $app->erp->Firmendaten("noauth");
$mailanstellesmtp = $app->erp->Firmendaten("mailanstellesmtp");

// mail
$app->mail = new PHPMailer($app);
$app->mail->CharSet = "UTF-8";
$app->mail->PluginDir=dirname(__DIR__).'/www/plugins/phpmailer/';

if($mailanstellesmtp=="1"){
  $app->mail->IsMail();
} else {
  $app->mail->IsSMTP();

  if($noauth=="1"){
    $app->mail->SMTPAuth = false;
  }
  else {
    $app->mail->SMTPAuth   = true;
  }

  if($mailssl==1){
    $app->mail->SMTPSecure = "tls";                 // sets the prefix to the servier
  }
  elseif ($mailssl==2){
    $app->mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
  }

  $app->mail->Host       = $host;

  $app->mail->Port       = $port;                   // set the SMTP port for the GMAIL server

  $app->mail->Username   = $benutzername;  // GMAIL username
  $app->mail->Password   = $passwort;            // GMAIL password
}

$app->FormHandler = new FormHandler($app);

$app->DB->Update("UPDATE prozessstarter SET mutexcounter = mutexcounter + 1 WHERE mutex = 1 AND (parameter = 'importvorlage') AND aktiv = 1");
if(!$app->DB->Select("SELECT id FROM prozessstarter WHERE mutex = 0 AND parameter = 'importvorlage' AND aktiv = 1")) {
  return;
}

$app->DB->Update("UPDATE prozessstarter SET letzteausfuerhung=NOW(), mutex = 1,mutexcounter=0 WHERE parameter = 'importvorlage' AND `aktiv` = 1");

/** @var Importvorlage $importVorlage */
$importVorlage = $app->erp->LoadModul('importvorlage');
$importVorlage->doCronjob();
$app->DB->Update("UPDATE prozessstarter SET letzteausfuerhung=NOW(), mutex = 0,mutexcounter=0 WHERE parameter = 'importvorlage' AND `aktiv` = 1");
