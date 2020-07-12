<?php
class outlook {


      public function newMail($subject, $recipients, $body, $send = true,$format = 'text',$attachment = '') {
        if (!defined("olMailItem")) {define("olMailItem",0);}
        $objMail  = new COM("outlook.application") or die("Unable to start Outlook");
        //just to check you are connected.
        //echo "Loaded MS Outlook, version {$objMail ->Version}\n";
        $oMsg = $objMail->CreateItem(olMailItem);
        //$oMsg->Display;
        $oMsg->Subject=$subject;
        if($format == 'text'){
          $oMsg->Body=$body;
        } else {
          $oMsg->HTMLBody=$body;
        }
        $oMsg->To=($recipients);

        //$oMsg->Save();
        if($send){
          $oMsg->Display;
          $oMsg->Send();
        } else {
          $oMsg->Display;
        }
      }
      public function newCalendar(){
        //https://msdn.microsoft.com/en-us/library/office/ff868714.aspx
        if (!defined("olMailItem")) {define("olMailItem",0);}
        $objCal  = new COM("outlook.application") or die("Unable to start Outlook");

        $oCal = $objCal->CreateItem(3);
        //$oCal->Recipients->Add("craig.davison@nationalgrid.com");

        var_dump($oCal->Recipients);

        //$oCal->Location = 'Test Room';
        //$oCal->To=('craig.davison@nationalgrid.com');
        $oCal->Subject = 'Strategy Meeting';
        $oCal->StartDate = '9/24/2017 13:30:00';
        //$oCal->EndDate = '9/24/2017 14:30:00';
        //$oCal->Duration = '90';
        $oCal->Display;
        $oCal->Body = 'Some stuff about this event';
        //$oCal->Send();
      }
}
