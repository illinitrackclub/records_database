<?php
/* ITC Results db - 
 *
 *
 */
 
 
 
/*
 * getPeople will return the information for the specified person, or for all people
 * if no ID is specified.
 */
	function getPeople($id=FALSE, $limitLow=FALSE, $limitHigh=FALSE)
	{
		global $db;
		$people = array();
		$data = array();
		
		if ($id === FALSE)
			$query = "SELECT * FROM `records_people` ORDER BY `last` ASC";
		else
		{
			$query = "SELECT * FROM `records_people` where `id` = ?";
			$data[] = $id;			
		}
		
		
		//limit the results, if specified. 
		if(($limitLow===FALSE) && ($limitHigh!==FALSE))
		{
			$query .= " LIMIT 0, $limitHigh";
		}
		else if(($limitLow !==FALSE) && ($limitHigh !==FALSE))
		{
			$query .= " LIMIT $limitLow, $limitHigh";
		}
		
		try 
		{
			$qprep = $db->prepare($query);
			$qprep->execute($data);
    	}
		catch(PDOException $e)
    	{
    		echo $e->getMessage();
    	}
		if ($id == FALSE)
			$people = $qprep->fetchAll(PDO::FETCH_ASSOC);
		else
			$people = $qprep->fetch(PDO::FETCH_ASSOC);
		
		return $people;		
	}

/*
 * getEvents will return a specific event info, events for a season, or all events
 * if no ID is specified.
 */
	function getEvents($id=FALSE, $season=FALSE, $relay=FALSE)
	{
		global $db;
		$events = array();
		$data = array();
		
		if (($id === FALSE) && ($season === FALSE) && ($relay===FALSE))
			$query = "SELECT * FROM `records_events` ORDER BY  `id` ASC";
		elseif ($id != FALSE)
		{
			$query = "SELECT * FROM `records_events` where `id` = ?";
			$data[] = $id;
		}
		elseif (($id == FALSE) && ($season != FALSE))
		{
			if($relay === FALSE)
			{
				$query = "SELECT * FROM `records_events` where `season` = ?";
				$data[] = $season;
			}
			else
			{
				$query = "SELECT * FROM `records_events` where `season` = ? AND `relay` = '1'";
				$data[] = $season;
			}
		}
		else
		{
			echo "ERROR! Incorrect parameters entered. Please check the query.";		
		}
		
		try 
		{
			$qprep = $db->prepare($query);
			$qprep->execute($data);
    	}
		catch(PDOException $e)
    	{
    		echo $e->getMessage();
    	}
		if ($id == FALSE)
			$events = $qprep->fetchAll(PDO::FETCH_ASSOC);
		else
			$events = $qprep->fetch(PDO::FETCH_ASSOC);
		
		return $events;		
	}
	
/*
 * function addPerson() will create a new person in the db.
 */
	function addPerson($first, $last, $sex, $gradyr, $email, $alumni, $elite)
	{
		global $db;
		$ts = date("Y-m-d H:i:s", time());
		$data = array();
		
		$data[] = $first;
		$data[] = $last;
		$data[] = $sex;
		$data[] = $gradyr;
		$data[] = $email;
		$data[] = $alumni;
		$data[] = $elite;
		$data[] = $ts;
		
		$query = "INSERT INTO `records_people` VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);";
			
		try 
		{
			$db->prepare($query)->execute($data);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	
		if(!$e)
			return TRUE;
		else
			return FALSE;
	}

/*
 * function addMeet() will create a new meet entry in the db.
 */
	function addMeet($year, $season, $name, $start, $end, $host, $loc, $course, $results, $photos, $notes, $splits)
	{
		global $db;
		$ts = date("Y-m-d H:i:s", time());
		$data = array();
		
		$data[] = $year;
		$data[] = $season;
		$data[] = $name;
		$data[] = $start;
		$data[] = $end;
		$data[] = $host;
		$data[] = $loc;
		$data[] = $course;
		$data[] = $results;
		$data[] = $photos;
		$data[] = $splits;
		$data[] = $notes;
		$data[] = $ts;
		
		$query = "INSERT INTO `records_meets` VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			
		try 
		{
			$db->prepare($query)->execute($data);
		}
		catch(PDOException $e)
		{
			var_dump($e->getMessage());
		}
	
		if(!$e)
			return TRUE;
		else
			return FALSE;
	}
	
/*
 * function addPerformance() will create a new meet entry in the db.
 * NOTE: since MySQL cannot handle fractions of a time, the times are split into
 *  2 parts: an HH:MM:SS portion in seconds in 'seconds' and a ss in 'ms'. 
 */
	function addPerformance($name_id, $meet_id, $date, $event, $seconds, $ms, $mark, $wind, $postgrad, $unattached)
	{
		global $db;
		$ts = date("Y-m-d H:i:s", time());
		$data = array();
		
		//get the sex of the athlete. 1=male, 2=female
		$info = getPeople($name_id);
		if($info['sex'] == 'm')
			$gender = 1;
		else
			$gender = 2;
		
		//print_r($gender);
		
		//get the season from the meet
		$res = getMeet($meet_id);
		$season = $res['season'];
		
		//get the year from the date
		$pieces = explode('-', $date);
		$year = $pieces[0];
		
		//Fill misc values
		if(empty($postgrad))
			$postgrad = 0;
		if(empty($unattached))
			$unattached = 0;
			
		$data[] = $name_id;
		$data[] = $gender;
		$data[] = $year;
		$data[] = $season;
		$data[] = $date;		
		$data[] = $meet_id;
		$data[] = $event;
		$data[] = $seconds;
		$data[] = $ms;
		$data[] = $mark;
		$data[] = $wind;
		$data[] = $postgrad;
		$data[] = $unattached;
		$data[] = $ts;
		
		$query = "INSERT INTO `records_performances` VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			
		try 
		{
			$db->prepare($query)->execute($data);
			$newId = $db->lastInsertId();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		
		//Check if the new performance is a club record. If so, update the records_events table.
		$eventArr = getEvents($event);
		if($gender ==1)
			$record =  getResults($eventArr['record_m_id']);
		else
			$record = getResults($eventArr['record_w_id']);		
		
		$recordMS = $record['seconds']*100 + $record['ms'];
		$recordMark = $record['mark'];
		$newMS = $seconds*100+$ms;
		$newMark = $mark;
		$newRecord = 0;		//used for return		
		
		//If faster time or farther mark, update the record
		if((($newMS > 0)&&($newMS < $recordMS)) || (($newMark > 0)&&($newMark > $recordMark)) || empty($record))
		{
			//update 'records_events' with the new performance id & date set
			if($gender == 1) //male
				$query = "UPDATE `records_events` SET `record_m_id` =  $newId, `record_m_date` =  '$date', `modified` = NOW() WHERE `id` = $event;";
			else
				$query = "UPDATE `records_events` SET `record_w_id` =  $newId, `record_w_date` =  '$date', `modified` = NOW() WHERE `id` = $event;";
		
			try 
			{
				$db->prepare($query)->execute();
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
			$newRecord = 1;
		}
	
		//return a code to the user. 0 = error, 1 = success, 2 = success and a new record set
		if(!$e)
		{
			if($newRecord == 1)
				return 2;
			else
				return 1;
		}
		else
			return 0;
	}	

/*
* printMsg() will print a message to the top of the screen based on the type. Success = green, fail = red.
* default type is a successful message (green box).
*/
	function printMsg($msg, $type=FALSE)
	{
		if($type == 'error')
			echo "<div class=\"alert alert-error\">";
		else if ($type == 'warning')
			echo "<div class=\"alert alert-warning\">";
		else if ($type == 'info')
			echo "<div class=\"alert alert-info\">";
		else
			echo "<div class=\"alert alert-success\">";		
		echo " <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>$msg<br /></div>";	
	}
	
/*
 * function displayMessages will print out any messages that have been passed into it.
 * The messages must be passed as arrays. For individual messages, use printMsg()
 */
	function displayMessages($success = FALSE, $error = FALSE, $warning = FALSE, $info = FALSE)
	{
		if(!empty($success))
		{
			foreach($success as $s)
				printMsg($s);		
		}
		if(!empty($error))
		{
			foreach($error as $e)
				printMsg($e, 'error');		
		}
		if(!empty($warning))
		{
			foreach($warning as $w)
				printMsg($w);				
		}
		if(!empty($info))
		{
			foreach($info as $i)
				printMsg($i);				
		}	
	}
	
	//Get results
/*
 * This function will get various results based on params passed. If nothing is specified all performances will be returned.
 * 
 */
function getResults($id=FALSE, $nameID=FALSE, $gender=FALSE, $eventID=FALSE, $num=FALSE)
{	
	global $db;
	$results = array();
	$data = array();
	
	$query = "SELECT * FROM `records_performances`";
	
	//if($gender != FALSE) 
		//$query .= " WHERE `gender` = $gender";
		
	if($id!==FALSE)
	{
		$query .= " where `id` = ?";
		$data[] = $id;		
	}
	else if($nameID!==FALSE)
	{
		$query .= " where `name_id` = ?";
		$data[] = $nameID;		
	}
	else if($eventID !== FALSE)
	{
		$query .= " where `event_id` = ?";
		$data[] = $eventID;			
	}
	
	if($gender !== FALSE)
	{
		if(($id !== FALSE) || ($nameID !== FALSE) || ($eventID !== FALSE))
		{
			$query .= " AND `gender` = $gender";
		}
		else
		 $query .= " where `gender` = $gender";;
	}
	
	//order fastest to slowest
	//NOTE: NEED TO MODIFY TO ALSO ORDER BY MS (INCASE OF A CLOSE TIME) OR MARK;
	$query .= " ORDER BY `seconds` ASC, `ms` ASC, `mark` DESC"; 
	
	if($num !== FALSE)
	{
		$query .= " LIMIT $num";		
	}
			
	try 
		{
			$qprep = $db->prepare($query);
			$qprep->execute($data);
    	}
		catch(PDOException $e)
    	{
    		echo $e->getMessage();
    	}
		if (($id == FALSE) && $num != 1)
			$results = $qprep->fetchAll(PDO::FETCH_ASSOC);
		else
			$results = $qprep->fetch(PDO::FETCH_ASSOC);
		
		return $results;	
}

/*
 * function getMeet will return a list of all meets or info from a specific meet.
 */
function getMeet($id = FALSE, $year = FALSE, $season = FALSE, $num=FALSE, $desc=FALSE)
{
	global $db;
	$meets = array();
	$data = array();
		
	if($id !== FALSE)
	{
		$query = "SELECT * FROM `records_meets` WHERE `id` = ?";
		$data[] = $id;	
	}
	else if(($year !== FALSE) && ($season !== FALSE))
	{
		$query = "SELECT * FROM `records_meets` WHERE `year` = ?, `season` = ?";
		$data[] = $year;
		$data[] = $season;
	}
	else
		$query = "SELECT * FROM `records_meets`";

	if($desc === true)
		$query .= " ORDER BY `date_start` DESC";
	else
		$query .= " ORDER BY `date_start` ASC";

	if($num !== FALSE)
	{
		$data[] = $num;
		$query .=" LIMIT $num";
	}	
	
	try 
		{
			$qprep = $db->prepare($query);
			$qprep->execute($data);
    	}
		catch(PDOException $e)
    	{
    		echo $e->getMessage();
    	}
		if ($id == FALSE)
			$meets = $qprep->fetchAll(PDO::FETCH_ASSOC);
		else
			$meets = $qprep->fetch(PDO::FETCH_ASSOC);
		
		return $meets;	
}

/*
 * This function will generate the header on the user side of the website. The $page variable is
 *		used to specify the active page 
 */
function printNav($page)
	{
	$b1=$b2=$b3=$b4=$b5=$b6='';
	$admin = false;
	$active = 'class="active"';
		switch($page)
		{
			case 'admin-index':
				$admin = true;
				break;
			case 'admin-add':
				$admin=true;
				break;
			case 'index':
				$b1 = $active;
				break;
			case 'profile':
				$b2 = $active;
				break;
			case 'records':
				$b3 = $active;
				break;
			case 'performances':
				$b4 = $active;
				break;
			case 'results':
				$b5 = $active;
				break;
			case 'login':
				$b6 = $active;
			default:
		}
		
		if($admin == True)
			$pfx = "..";
		else
			$pfx = ".";

		echo "
		 <!-- Navigation --> 
          <div class=\"navbar-static-top\">
           <div class=\"navbar navbar-inverse\">
            <div class=\"navbar-inner\">
              <div class=\"container\" style=\"width:780px;\">
                <a class=\"btn btn-navbar\" data-toggle=\"collapse\" data-target=\".nav-collapse\">
                  <span class=\"icon-bar\"></span>
                  <span class=\"icon-bar\"></span>
                  <span class=\"icon-bar\"></span>
                </a>
                <div class=\"nav-collapse\">
                  <ul class=\"nav\">
                    <li $b1><a href=\"$pfx\index.php\">Home</a></li>
                    <li $b2><a href=\"$pfx\profile.php\">Profiles</a></li>
                    <li $b3><a href=\"$pfx\\records.php\">Club Records</a></li>
                    <li $b4 class=\"dropdown\">
                      <a href=\"$pfx\performances.php\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">Performance Lists<b class=\"caret\"></b></a>
                      <ul class=\"dropdown-menu\">
                        <li><a href=\"$pfx\performances.php?s=xc\">Cross Country</a></li>
                        <li><a href=\"$pfx\performances.php?s=it\">Indoor Track</a></li>
                        <li><a href=\"$pfx\performances.php?s=ot\">Outdoor Track</a></li>
                      </ul>
                    </li>
                    <li $b5 class=\"dropdown\">
                      <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">Meet Results <b class=\"caret\"></b></a>
                      <ul class=\"dropdown-menu\">
                        <li><a href=\"$pfx\\results.php?s=xc\">Cross Country</a></li>
                        <li><a href=\"$pfx\\results.php?s=it\">Indoor Track</a></li>
                        <li><a href=\"$pfx\\results.php?s=ot\">Outdoor Track</a></li>
                        <li class=\"divider\"></li>
                        <li><a href=\"$pfx\\results.php?s=all\">All</a></li>
                      </ul>
                    </li>
                  </ul>
        
                  <ul class=\"nav pull-right\">
                    <li $b6><a href=\"$pfx\\login.php\">Log In</a></li>
                    <li class=\"divider-vertical\"></li>
                  </ul>
                </div><!-- /.nav-collapse -->
              </div>
            </div><!-- /navbar-inner -->
          </div><!-- /navbar -->
          </div><!-- /navbar-static-top -->
		";
		
		
	}

/*
 * This is a small function used with usort() to sort an assosc array by performance ID's
 */
function perfSort($a, $b)
{
		if($a["perf_id"] < $b["perf_id"])
			return 1;
		else
			return -1;
}	
	
/*
 * This function will return the newest club records. It will return an array with the id of the performance and the date the performance was set. 
 * if $asc == TRUE, then this will sort the records from oldest to newest, and it will return the oldest club records.
 * sorting performed at the end 
 */
 function getRecentRecords($num = FALSE, $asc = FALSE)
 {
	//error_reporting(E_ALL);
	global $db;
	$data = array();
	$records = array();
	$performances = array();
	
	$query = "SELECT id AS event_id, name AS event_name, record_w_id AS perf_id, record_w_date AS dates FROM `records_events` WHERE record_w_id != 0 UNION ALL SELECT id AS event_id, name AS event_name, record_m_id AS perf_id, record_m_date AS dates from `records_events` WHERE record_m_id != 0";
	
	//sort by asc or desc depending on params
	if($asc != FALSE)
		$query .= " ORDER BY dates ASC";
	else
		$query .= " ORDER BY dates DESC";
	
	//number of results
	if($num !== false)
	{
		$query .= " LIMIT $num;";	
	}
		
	try 
	{
		$qprep = $db->prepare($query);
		$qprep->execute($data);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
	
	//Somewhere in here change the fetch so that it creates an assosc array but has the key of the array be the perf id
	if ($num > 1)
		$records = $qprep->fetchAll(PDO::FETCH_ASSOC);
	else
		$records = $qprep->fetch(PDO::FETCH_ASSOC);		

	//Sort the results by perf_id. This will keep the results consistent with the rest of the queries, since they all order by id desc
	//This might be able to be done in the original query, but I'm not entirely sure.
	usort($records, "perfSort");
 
	//process get the names, times and marks assosciated with each record, put into an array, and return it.
	$idList;
	foreach ($records as $r)
	{
		if(empty($idList))
			$idList = "$r[perf_id]";
		else
			$idList .=", $r[perf_id]";
	}
	if ($idList == NULL)
		return "No records";
	$query2 = "SELECT name_id, seconds, ms, mark, date from `records_performances` WHERE id IN ($idList) ORDER BY id DESC";
		
	try 
	{
		$qprep = $db->prepare($query2);
		$qprep->execute();
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
	
	if ($num > 1)
		$performances = $qprep->fetchAll(PDO::FETCH_ASSOC);
	else
		$performances = $qprep->fetch(PDO::FETCH_ASSOC);
		
	//get the names for each performance. The resulting assoc array will have name_id as the key value
	$idList2;
	foreach($performances as $p)
	{
		if(empty($idList2))
			$idList2 = "$p[name_id]";
		else
			$idList2 .=", $p[name_id]";
	}
	
	$query3 = "SELECT id, first, last FROM `records_people` WHERE id IN($idList2);";
	try 
	{
		$qprep = $db->prepare($query3);
		$qprep->execute();
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
	
	$names = array();
	while($row = $qprep->fetch(PDO::FETCH_ASSOC))
	{
		$names[$row['id']] = array('first'=>$row['first'], 'last'=>$row['last']);
	}
	
	//Smush it all together into a single array.	
	$newRecords = array();
	foreach($records as $k=>$r)
	{
		$newRecords[] = array('event_id'=>$r['event_id'],
								'event_name'=>$r['event_name'],
								'perf_id'=>$r['perf_id'],
								'date'=>$r['dates'],
								'name_id'=>$performances[$k]['name_id'],
								'first'=>$names[$performances[$k]['name_id']]['first'],
								'last'=>$names[$performances[$k]['name_id']]['last'],
								'seconds'=>$performances[$k]['seconds'],
								'ms'=>$performances[$k]['ms'],
								'mark'=>$performances[$k]['mark']);
	}	
	
	return $newRecords;
 }
 
 /*
  * processTime will take seconds, ms, and eventId and then format it in the necessary way for output
  * example: XC times do not display fractions of seconds, some times don't display leading zeroes, etc
  *
  * perhaps in the future update this to formatPerf where a string will be returned, either with the mark
  *	
  * NOTE: No clue how this handles times > 60 minutes. Probably not well. Will have to fix in the future
  * if I want to use with marathons, halfs, etc
  */
function formatTime($seconds, $ms, $eventId=false)
{
	//Format minutes w/o leading zeroes.
	if($seconds >= 60)
		$time = (int)date('i', $seconds).':'.date('s', $seconds);
	else
		$time = (int)date('s', $seconds);
		
	//format ms with leading zeroes if applicable
	$ms = str_pad($ms, 2, '0', STR_PAD_LEFT); 
	
	//This switch will only add ms to a time when necessary
	switch($eventId)
	{
		case 7:
		case 8:
		case 9:
		case 10:
		case 52:
			break;
		default:
			$time .= ".$ms";
	}
	
	return $time;
}

/*
 * formatPerf will take seconds, ms, mark, and eventId as inputs. It will output a formatted string
 * that includes the performance and the appropriate unit (m, s, etc)
 *
 * NOTE: No clue how this handles times > 60 minutes. Probably not well. Will have to fix in the future
 * if I want to use with marathons, halfs, etc
 */

function formatPerf($seconds, $ms, $mark, $eventId=false)
{
	$perf;
	//first determine if mark or time
	if($mark>0)
	{		//this is a mark
		$perf = "$mark m";
	}
	else	//must be a time
	{
		//Format minutes w/o leading zeroes.
		if($seconds >= 60)
			$time = (int)date('i', $seconds).':'.date('s', $seconds);
		else
			$time = (int)date('s', $seconds);
			
		//format ms with leading zeroes if applicable
		$ms = str_pad($ms, 2, '0', STR_PAD_LEFT); 
		
		//This switch will only add ms to a time when necessary
		switch($eventId)
		{
			case 7:
			case 8:
			case 9:
			case 10:
			case 52:
				break;
			default:
				$time .= ".$ms";
		}
		$perf = "$time";
	}

	return $perf;
}

/*
 * sSort is a function to help with uksort(). This will order an array into events by XC, IT, OT.
 */
function sSort($a, $b)
	{
		if($a == "xc")
			return -1;
		else if($a == "ot")
			return 1;
		else if($b == "xc")
			return 1;
		else
			return -1;
	}

/*
 *	getRecords() will get the club records for each event. It returns an array split up by season,
 *  and then event, for every records. This gets the records from the events table. 
 */
	function getRecords()
	{
		global $db;
		$records = array();
		$performances = array();
		$idList;
		$idList2;
		$names;
		
		//First get the whole events table, sorted by season
		$query1 = "SELECT * FROM `records_events` order by distance desc";
		$qprep = $db->prepare($query1);
		$qprep->execute();
		
		//now group results into assosc array by season
		while($row = $qprep->fetch(PDO::FETCH_ASSOC))
		{
			$records[$row['season']][$row['id']] = $row;
			//This will make a list of performance ID's to look up later
			if(empty($idList))
				$idList = "$row[record_w_id], $row[record_m_id]";
			else
				$idList .= ", $row[record_w_id], $row[record_m_id]";
		}
		
		//Next we will get the performances that were record setting
		$query2 = "SELECT * FROM `records_performances` WHERE id IN($idList)";
		$qprep = $db->prepare($query2);
		$qprep->execute();
		
		while($row = $qprep->fetch(PDO::FETCH_ASSOC))
		{
			$performances[$row['id']] = $row;
			//Generate the list of names to look up
			if(empty($idList2))
				$idList2 = "$row[name_id]";
			else
				$idList2 .= ", $row[name_id]";
		}
		
		//Look up the names of the record holders.
		$query3 = "SELECT id, first, last from `records_people` WHERE id IN($idList2)";
		$qprep = $db->prepare($query3);
		$qprep->execute();
		
		while($row = $qprep->fetch(PDO::FETCH_ASSOC))
		{
			$name[$row['id']] = $row;
		}
		
		
		//Put this all together into nice format
		foreach($records as $k1=>$r)
		{
			//loop through each season and add the data. Skip events that have no record (id = 0);
			foreach($r as $k2=>$s)
			{
				if($s['record_w_id'] != 0)
				{
					$records["$k1"]["$k2"]['record_w'] = $performances[$s['record_w_id']];
					$records["$k1"]["$k2"]['record_w']['first'] = $name[$performances[$s['record_w_id']]['name_id']]['first'];
					$records["$k1"]["$k2"]['record_w']['last'] = $name[$performances[$s['record_w_id']]['name_id']]['last'];
				}
				else
					$records["$k1"]["$k2"]['record_w'] = "";
				if($s['record_m_id'] != 0)
				{
					$records["$k1"]["$k2"]['record_m'] = $performances[$s['record_m_id']];
					$records["$k1"]["$k2"]['record_m']['first'] = $name[$performances[$s['record_m_id']]['name_id']]['first'];
					$records["$k1"]["$k2"]['record_m']['last'] = $name[$performances[$s['record_m_id']]['name_id']]['last'];
				}
				else
					$records["$k1"]["$k2"]['record_m'] = "";
			}
		}
		
		//Sort the array so it goes XC, IT, OT
		uksort($records, "sSort");
		
		return $records;
	}
?>