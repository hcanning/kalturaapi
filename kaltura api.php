

<?php

  require_once('lib/KalturaClient.php');

  //set access codes to your Kaltura install

  $config = new KalturaConfiguration(1234567);

  $config->serviceUrl = 'https://www.kaltura.com';

  $client = new KalturaClient($config);

  $ks = $client->session->start(

    "365734657384657834657365",

    "me@youremail.com",

    KalturaSessionType::ADMIN,

    1234567);

  $client->setKS($ks);

  $filter = new KalturaBaseEntryFilter();
  $filter->categoriesMatchAnd = "Zoom Recordings";
   $filter->statusEqual = KalturaEntryStatus::READY;
  $filter->typeEqual = KalturaEntryType::MEDIA_CLIP;
  $pager = new KalturaFilterPager();
  $pager->pageSize = 500; //very important 
  $baseEntry = new KalturaBaseEntry(); 


try {
      $roomEntries = $client->baseEntry->listAction($filter, $pager);
       foreach ($roomEntries->objects as $entry) {

          $entryTags = $entry->tags;
          if (strpos($entryTags, "room_") !== false) {
          //set dtime zone for UNIX Timezone conversion
          date_default_timezone_set("America/New_York");  
          $entryId= $entry->id; 
          $entryName= $entry->name; 
          //apply UNIX time stamp to var
          $entryCreated = $entry->createdAt; 
          //switch administrator username
          $baseEntry->userId = "jdoe";
          $roomEntries = $client->baseEntry->update($entryId, $baseEntry);
          //append Kaltura creation time 
          $baseEntry->name = $entryName . " - " . date('m.d.Y - g:i A',$entryCreated); 
          $roomEntries = $client->baseEntry->update($entryId, $baseEntry);  

          $baseEntry->categories = "Zoom Processed";
          $roomEntries = $client->baseEntry->update($entryId, $baseEntry); 
                                                    } //close if
        
                                              } //close for each

      } catch (Exception $e)

     {

    echo $e->getMessage(); 

   } //close try
//feedback
   

?>





