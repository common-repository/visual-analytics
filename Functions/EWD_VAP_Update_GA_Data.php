<?php
function EWD_VAP_Update_GA_Data() {

$Client_ID = get_option('EWD_VAP_Client_ID');
$Client_Secret = get_option('EWD_VAP_Client_Secret');
$Developer_Key = get_option('EWD_VAP_Developer_Key');
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_AnalyticsService.php';

$client = new Google_Client();
$client->setApplicationName('Google+ PHP Starter Application');
// Visit https://code.google.com/apis/console?api=plus to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId($Client_ID);
$client->setClientSecret($Client_Secret);
$client->setRedirectUri(site_url() . "/wp-admin/admin.php?page=EWD-VAP-options");
$client->setDeveloperKey($Developer_Key);
$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
$client->setUseObjects(true);

if (isset($_GET['code'])) {
  $client->authenticate();
  update_option('GA_Token', $client->getAccessToken());
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	update_option('EWD_VAP_GA_Update_Datetime', time());
}

$Token = get_option('GA_Token');
if ($Token) {
  $client->setAccessToken($Token);
}

if (!$client->getAccessToken()) {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";

} else {
  $analytics = new Google_AnalyticsService($client);
	runMainDemo($analytics);
}

}

function runMainDemo(&$analytics) {
  global $wpdb;
	global $ewd_vap_ga_data_table_name;
	
	try {

    // Step 2. Get the user's first view (profile) ID.
    $profileId = getFirstProfileId($analytics);

    if (isset($profileId)) {

      // Step 3. Query the Core Reporting API.
      $results = getResults($analytics, $profileId);

      // Step 4. Output the results.
      printResults($results);
    }

  } catch (apiServiceException $e) {
    // Error from the API.
    print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();

  } catch (Exception $e) {
    print 'There wan a general error : ' . $e->getMessage();
  }
}

function getFirstprofileId(&$analytics) {
  $accounts = $analytics->management_accounts->listManagementAccounts();

  if (count($accounts->getItems()) > 0) {
    $items = $accounts->getItems();
    $firstAccountId = $items[0]->getId();

    $webproperties = $analytics->management_webproperties
        ->listManagementWebproperties($firstAccountId);

    if (count($webproperties->getItems()) > 0) {
      $items = $webproperties->getItems();
      $firstWebpropertyId = $items[0]->getId();

      $profiles = $analytics->management_profiles
          ->listManagementProfiles($firstAccountId, $firstWebpropertyId);

      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();
        return $items[0]->getId();

      } else {
        throw new Exception('No views (profiles) found for this user.');
      }
    } else {
      throw new Exception('No webproperties found for this user.');
    }
  } else {
    throw new Exception('No accounts found for this user.');
  }
}

function getResults(&$analytics, $profileId) {
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       date('Y-m-d', time()-3600*24*365),
       date('Y-m-d'),
       'ga:visitors, ga:pageviews',
			 array('dimensions' => 'ga:date,ga:country',
			 			 'max-results' => 10000,
						 'sort' => '-ga:date'));
}

function printResults(&$results) {
  global $wpdb;
	global $ewd_vap_ga_data_table_name;
	
	if (count($results->getRows()) > 0) {
    $profileName = $results->getProfileInfo()->getProfileName();
    $rows = $results->getRows();
    $visits = $rows[0][0];
		
		foreach ($rows as $Day) {
				$DayN = substr($Day[0], 6);
				$MonthN = substr($Day[0], 4, 2);
				$YearN = substr($Day[0], 0, 4);
				$MYSQLDate = $YearN . "-" . $MonthN . "-" . $DayN;
				$Country = $Day[1];
				$Visitors = $Day[2];
				$PageViews = $Day[3];
				
				$ID = $wpdb->get_row($wpdb->prepare("SELECT GA_Data_ID FROM $ewd_vap_ga_data_table_name WHERE GA_Date='%s' AND GA_Country='%s'", $MYSQLDate, $Country));
				if ($wpdb->num_rows == 0) {
					  $wpdb->insert($ewd_vap_ga_data_table_name,
						array(
								'GA_Date' => $MYSQLDate,
								'GA_Country' => $Country,
								'GA_Visitors' => $Visitors,
								'GA_Pageviews' => $PageViews
						));		
				}
				else {
						$wpdb->update($ewd_vap_ga_data_table_name,
						array(
								'GA_Date' => $MYSQLDate,
								'GA_Country' => $Country,
								'GA_Visitors' => $Visitors,
								'GA_Pageviews' => $PageViews
						),
						array('GA_Data_ID' => $ID->GA_Data_ID,)
						);		
				}
		}
		
  } else {
    print '<p>No results found.</p>';
  }
}

