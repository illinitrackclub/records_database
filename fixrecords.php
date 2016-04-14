<?php
//This file will update the new records db in the events table. Works as of 4-12-2013

//start the session and include user data
session_start();	
include_once('includes/config.php');
include_once('includes/functions.php');
connectToDB();
	
	
//get new records table

	$query = "SELECT id, name, record_w_id, record_w_date, record_m_id, record_m_date from `records_events` WHERE 1 ORDER BY id ASC";

	try 
	{
		$qprep = $db->prepare($query);
		$qprep->execute();
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
	
	$records = $qprep->fetchAll(PDO::FETCH_ASSOC);

	//error_reporting(E_ALL);
		
	foreach($records as $r)
	{
		//Get overall top performance for men and women
		$mPerf = getResults(false, false, 1, $r['id'], 1);
		$wPerf = getResults(false, false, 2, $r['id'], 1);
		
		//get records from new table. Check if not empty
		if($r['record_m_id'] !== 0)
			$mNew = getResults($r['record_m_id']);
		
		
		if($r['record_w_id'] !== 0)
			$wNew = getResults($r['record_w_id']);
			
		//Process records in new table
		$mMs = $mNew['seconds']*100+$mNew['ms'];
		$wMs = $wNew['seconds']*100+$wNew['ms'];
		$mMark = $mNew['mark'];
		$wMark = $wNew['mark'];
		
		//Process overall top performances
		$mMsTop = $mPerf['seconds']*100+$mPerf['ms'];
		$wMsTop = $wPerf['seconds']*100+$wPerf['ms'];
		$mMarkTop = $mPerf['mark'];
		$wMarkTop = $wPerf['mark'];
		
		//compare men and update if necessary.
		if(($mMs == 0) && ($mMark == 0) &&($mMsTop == 0) && ($mMarkTop ==0))
		{
			//Existing record is zero and there are no new records to fill it. Alert the user.
			echo "$r[name] does not have any performances assosciated with it. No changes have been made.<br />";
		}
		else if($mPerf['id'] == $r['record_m_id'])
		{
			//The record in the new table is correct
			echo "The men's record for $r[name] is up to date. No changes have been made.<br />";
		
		}
		else if(($mMsTop < $mMs) || (($mMarkTop > $mMark)))
		{
			//New performance found
			echo "<span style=\"color:green;\">A new men's record is found. $r[name] has been updated with performance ID of $mPerf[id]</span><br />";
		
			//build query
			$query2 = "UPDATE `records_events` SET `record_m_id` =  $mPerf[id], `record_m_date` =  '$mPerf[date]', `modified` = NOW() WHERE `id` = $r[id];";
			
			try 
			{
				$db->prepare($query2)->execute();
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}	
		}
		else
		{
			//not sure how got here
			echo"<span style=\"color:red;\"><strong>Not sure how you got here, probably a bug in the code</strong></span><br />";
			echo"";
		}
			
		//Do the women now
		if(($wMs == 0) && ($wMark == 0) &&($wMsTop == 0) && ($wMarkTop ==0))
		{
			//Existing record is zero and there are no new records to fill it. Alert the user.
			echo "$r[name] does not have any performances assosciated with it. No changes have been made.<br />";
		}
		else if($wPerf['id'] == $r['record_w_id'])
		{
			//The record in the new table is correct
			echo "The women's record for $r[name] is up to date. No changes have been made.<br />";
		
		}
		else if(($wMsTop < $wMs) || (($wMarkTop > $wMark)))
		{
			//New performance found
			echo "<span style=\"color:green;\">A new women's record is found. $r[name] has been updated with performance ID of $wPerf[id]</span><br />";
		
			//build query
			$query2 = "UPDATE `records_events` SET `record_w_id` =  $wPerf[id], `record_w_date` =  '$wPerf[date]', `modified` = NOW() WHERE `id` = $r[id];";
			
			try 
			{
				$db->prepare($query2)->execute();
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}	
		}
		else
		{
			//not sure how got here
			echo"<span style=\"color:red;\"><strong>Not sure how you got here, probably a bug in the code</strong></span><br />";
		}
	}

//Get current records


//compare




?>