<?php

2

date_default_timezone_set('Asia/Jakarta');

3

require_once("sdata-modules.php");

4

/**

5

* @Author: Eka Syahwan

6

* @Date: 2017-12-11 17:01:26

7

* @Last Modified by: Eka Syahwan

8

* @Last Modified time: 2018-08-17 15:13:34

9

*/

10

11

12

##############################################################################################################

13

$config['deviceCode'] 		= '868174030762326';

14

$config['tk'] 				= 'ACE0-_5aFSdBca_kvb7lDHOnHCeXYbCj1SpxdHRodw&token=4ecbSOYVWRZZpa9UcNftIRqy-jNjBlxmgkt1GH-wwawjemI5b_bdxKvT-HPnMAmBLLPdLBRQYY1A9Kc';

15

$config['token'] 			= '4ecbSOYVWRZZpa9UcNftIRqy-jNjBlxmgkt1GH-wwawjemI5b_bdxKvT-HPnMAmBLLPdLBRQYY1A9Kc';

16

$config['uuid'] 			= '530d6a57150245bab8b0e3e4c52cbc22';

17

$config['sign'] 			= 'cdf0edc0814706b77c690a366ddf108b';

18

$config['android_id'] 		= '112c35414e294c4b';

19

##############################################################################################################

20

21

22

for ($x=0; $x <1; $x++) { 

23

	$url 	= array(); 

24

	for ($cid=0; $cid <20; $cid++) { 

25

		for ($page=0; $page <10; $page++) { 

26

			$url[] = array(

27

				'url' 	=> 'http://api.beritaqu.net/content/getList?cid='.$cid.'&page='.$page,

28

				'note' 	=> 'optional', 

29

			);

30

		}

31

		$ambilBerita = $sdata->sdata($url); unset($url);unset($header);

32

		foreach ($ambilBerita as $key => $value) {

33

			$jdata = json_decode($value[respons],true);

34

			foreach ($jdata[data][data] as $key => $dataArtikel) {

35

				$artikel[] = $dataArtikel[id];

36

			}

37

		}

38

		$artikel = array_unique($artikel);

39

		echo "[+] Mengambil data artikel (CID : ".$cid.") ==> ".count(array_unique($artikel))."\r\n";

40

	}

41

	while (TRUE) {

42

		$timeIn30Minutes = time() + 30*60;

43

		$rnd 	= array_rand($artikel); 

44

		$id 	= $artikel[$rnd];

45

		$url[] = array(

46

			'url' 	=> 'http://api.beritaqu.net/timing/read',

47

			'note' 	=> $rnd, 

48

		);

49

		$header[] = array(

50

			'post' => 'OSVersion=8.0.0&android_channel=google&android_id='.$config['android_id'].'&content_id='.$id.'&content_type=1&deviceCode='.$config['deviceCode'].'&device_brand=samsung&device_ip=114.124.239.'.rand(0,255).'&device_version=SM-A730F&dtu=001&lat=&lon=&network=wifi&pack_channel=google&time='.$timeIn30Minutes.'&tk='.$config['tk'].'&token='.$config['token'].'&uuid='.$config['uuid'].'&version=10047&versionName=1.4.7&sign='.$config['sign'], 

51

		);

52

		$respons = $sdata->sdata($url , $header); 

53

		unset($url);unset($header);

54

		foreach ($respons as $key => $value) {

55

			$rjson = json_decode($value[respons],true);

56

			echo "[+][".$id." (Live : ".count($artikel).")] Message : ".$rjson['message']." | Poin : ".$rjson['data']['amount']." | Read Second : ".$rjson['data']['current_read_second']."\r\n";

57

			if($rjson[code] == '-20003' || $rjson['data']['current_read_second'] == '330' || $rjson['data']['amount'] == 0){

58

				unset($artikel[$value[data][note]]);

59

			}

60

		}

61

		if(count($artikel) == 0){

62

			sleep(30);

63

			break;

64

		}

65

		sleep(5);

66

	}

67

	$x++;

68

}

